<?php

namespace App\Repositories;

use App\Models\Cargo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargoRepository
{
    public function all()
    {
        return Cargo::all();
    }

    public function addNewDeliveryRequest($cargoData)
    {
        return Cargo::create([
            'origin_lat' => $cargoData['origin_lat'],
            'origin_long' => $cargoData['origin_long'],
            'origin_address' => $cargoData['origin_address'],
            'sender_name' => $cargoData['sender_name'],
            'sender_mobile' => $cargoData['sender_mobile'],
            'destination_lat' => $cargoData['destination_lat'],
            'destination_long' => $cargoData['destination_long'],
            'destination_address' => $cargoData['destination_address'],
            'receiver_name' => $cargoData['receiver_name'],
            'receiver_mobile' => $cargoData['receiver_mobile'],
            'customer_id' => Auth::user()->id,
            'tracking_code' => Cargo::trackCodeGenerator()
        ]);
    }

    public function getCargoByCode($trackingCode)
    {
        $cargo = Cargo::where('tracking_code', $trackingCode)->first();
        if ($cargo) {
            return $cargo;
        }

        return false;
    }

    public function getCargoStatus($cargoId)
    {
        $cargo = Cargo::find($cargoId);
        if ($cargo) {
            return $cargo->status;
        }

        return false;
    }

    public function getReadyToAccept()
    {
        return Cargo::where('status', Cargo::READY_TO_DELIVER)->get();
    }

    public function cancellation($trackingCode, $customerId)
    {
        $cargo = Cargo::where('tracking_code', $trackingCode)->first();
        if ($cargo->customer_id != $customerId) {
            return false;
        }
        if ($cargo && ($cargo->status == Cargo::READY_TO_DELIVER || $cargo->status == Cargo::NEW_DELIVERY_REQUEST)) {
            $cargo->status = Cargo::CANCELED;
            $cargo->save();

            return true;
        }
        return false;
    }

    public function acceptCargo($cargoId, $deliveryId)
    {
        $cargo = Cargo::find($cargoId);

        if ($cargo && $cargo->status == Cargo::READY_TO_DELIVER && $cargo->delivery_id == null) {

            DB::beginTransaction();

            try {
                DB::table('users')->where('id', $deliveryId)->lockForUpdate()->get();
                DB::table('cargos')->where('id', $cargoId)->lockForUpdate()->get();


                if ($cargo->status === Cargo::ACCEPT_BY_DELIVERY) {
                    DB::rollBack();
                    return false;
                }

                $cargo->status = Cargo::ACCEPT_BY_DELIVERY;
                $cargo->delivery_id = $deliveryId;
                $cargo->save();

                DB::commit();

                return true;
            } catch (Exception $e) {

                DB::rollBack();
                return false;
            }
        }
        return false;
    }

    public function changeDeliveryStatus($cargoId, $deliveryId, $status)
    {
        if (in_array($status, [Cargo::NEW_DELIVERY_REQUEST, Cargo::READY_TO_DELIVER, Cargo::CANCELED])) {
            return false;
        }
        $cargo = Cargo::find($cargoId);
        if ($cargo && $cargo->delivery_id && $cargo->delivery_id == $deliveryId) {
            $cargo->status = $status;
            $cargo->save();

            return true;
        }
    }

    public function makeVisible($cargoId, $status)
    {
        $cargo = Cargo::find($cargoId);
        if ($cargo && $cargo->delivery_id == null) {
            $cargo->status = $status;
            $cargo->save();

            return true;
        }
        return false;
    }
}
