<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::group(['middleware' => ['auth:api', 'scope:customer,company,delivery']], function () {
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'customer' ,'middleware' => ['auth:api', 'scope:customer']], function () {
    Route::resource('cargos', CustomerController::class)->only(['show', 'store']);
    Route::post('cargos/cancel', [CustomerController::class, 'cancelDeliveryRequest']);
});

Route::group(['prefix' => 'delivery' ,'middleware' => ['auth:api', 'scope:delivery']], function () {
    Route::resource('cargos', DeliveryController::class)->only(['index']);
    Route::post('cargos/accept', [DeliveryController::class, 'acceptCargo']);
});

