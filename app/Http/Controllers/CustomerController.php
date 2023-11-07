<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCargoRequest;
use App\Http\Requests\CancelCargoRequest;
use App\Models\Cargo;
use App\Repositories\CargoRepository;
use App\Repositories\UserRepository;

class CustomerController extends Controller
{
    protected $cargoRepository;
    protected $userRepository;

    public function __construct(CargoRepository $cargoRepository, UserRepository $userRepository)
    {
        $this->cargoRepository = $cargoRepository;
        $this->userRepository = $userRepository;
    }

    public function show($trackingCode)
    {
        $data = $this->cargoRepository->getCargoByCode($trackingCode);
        if (!$data) {
            return response()->json([
                'message' => 'cargo not found!',
                'data' => null
            ], 400);
        }
        return response()->json([
            'message' => null,
            'data' => $data
        ]);
    }

    public function store(AddCargoRequest $addCargoRequest)
    {
        $cargo = $this->cargoRepository->store($addCargoRequest->all());

        if (!$cargo) {
            return response()->json([
                'message' => 'creation failed!',
                'data' => null
            ], 400);
        }
        return response()->json([
            'message' => 'creation successful.You can check the status of your cargo with the tracking code',
            'data' => [
                'tracking_code' => $cargo->tracking_code
            ]
        ]);
    }

    public function cancelDeliveryRequest(CancelCargoRequest $cancelCargoRequest)
    {
        $cargo = $this->cargoRepository->cancellation($cancelCargoRequest->tracking_code);
        if ($cargo) {
            return response()->json([
                'message' => 'cargo canceled successfully',
                'data' => null
            ]);
        }

        return response()->json([
            'message' => 'cargo cancellation failed!',
            'data' => null
        ], 400);
    }
}
