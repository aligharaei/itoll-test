<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisibleCargoRequest;
use App\Models\Cargo;
use App\Repositories\CargoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
        $cargos = $this->cargoRepository->all();
        return response()->json([
            'message' => null,
            'data' => $cargos
        ]);
    }
    public function makeCargosVisible(VisibleCargoRequest $visibleCargoRequest)
    {
        $visible = $this->cargoRepository->changeStatus($visibleCargoRequest->cargo_id, Cargo::READY_TO_DELIVER);
        if ($visible) {
            return response()->json([
                'message' => 'the cargo selected is visible to deliveries',
                'data' => null
            ]);
        }
        return response()->json([
            'message' => 'there was an error to visible the cargo',
            'data' => null
        ]);
    }
}
