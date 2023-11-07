<?php

namespace App\Repositories;

use App\Models\Cargo;
use Illuminate\Support\Facades\Auth;

class CargoRepository
{
    public function All()
    {
        return Cargo::all();
    }

    public function store($cargoData)
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

    public function readyToAccept()
    {
        return Cargo::where('status', Cargo::READY_TO_DELIVER)->get();
    }

    public function cancellation($trackingCode)
    {
        $cargo = Cargo::where('tracking_code', $trackingCode)->first();
        if ($cargo && $cargo->status == Cargo::READY_TO_DELIVER) {
            $cargo->status = Cargo::CANCELED;
            $cargo->save();

            return true;
        }

        return false;
    }
}
