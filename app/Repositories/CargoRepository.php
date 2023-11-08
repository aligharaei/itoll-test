<?php

namespace App\Repositories;

use App\Models\Cargo;
use Illuminate\Support\Facades\Auth;

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
            $cargo->status = Cargo::ACCEPT_BY_DELIVERY;
            $cargo->delivery_id = $deliveryId;
            $cargo->save();

            return true;
        }
        return false;
    }
}
