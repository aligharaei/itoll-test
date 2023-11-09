<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptCargoRequest;
use App\Http\Requests\ChangeDeliveryStatusRequest;
use App\Models\Cargo;
use App\Repositories\CargoRepository;
use App\Repositories\UserRepository;

class DeliveryController extends Controller
{
    protected $cargoRepository;
    protected $userRepository;

    public function __construct(CargoRepository $cargoRepository, UserRepository $userRepository)
    {
        $this->cargoRepository = $cargoRepository;
        $this->userRepository = $userRepository;
    }
    public function index()
    {
        $cargos = $this->cargoRepository->getReadyToAccept();
        return response()->json([
            'message' => null,
            'data' => $cargos
        ]);
    }

    public function acceptCargo(AcceptCargoRequest $acceptCargoRequest)
    {
        $delivery = $this->userRepository->getAuthUser();
        $cargo_id = $acceptCargoRequest->cargo_id;
        $acceptance = $this->cargoRepository->acceptCargo($cargo_id, $delivery->id);
        if ($acceptance) {
            return response()->json([
                'message' => "The Cargo is accept to deliver. go to origin and pickup that",
                'data' => null
            ]);
        }
        return response()->json([
            'message' => "Cargo acceptance failed!",
            'data' => null
        ],400);
    }

    public function changeDeliveryStatus(ChangeDeliveryStatusRequest $changeDeliveryStatusRequest)
    {
        $delivery = $this->userRepository->getAuthUser();
        $cargo_id = $changeDeliveryStatusRequest->cargo_id;
        $changeStatus = $this->cargoRepository->changeDeliveryStatus($cargo_id, $delivery->id, $changeDeliveryStatusRequest->status);
        $status = Cargo::translateStatus($this->cargoRepository->getCargoStatus($cargo_id));

        if ($changeStatus) {
            return response()->json([
                'message' => "The Cargo's status changed to '$status' ",
                'data' => null
            ]);
        }
        return response()->json([
            'message' => "Cargo change status failed!",
            'data' => null
        ],400);
    }
}
