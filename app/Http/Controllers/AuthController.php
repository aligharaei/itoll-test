<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as OClient;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, Closure $next) {
            $this->user = Auth::user();
            return $next($request);

        });
    }

    public function login(LoginRequest $loginRequest)
    {
        $credential = [
            'mobile_number' => $loginRequest->mobile_number,
            'password' => $loginRequest->password
        ];

        if (!auth()->attempt($credential)) {
            return response()->json([
                'message' => 'wrong username or password',
                'data' => null
            ]);
        }

        $user = Auth::user();

        $scope = null;
        switch ($user->type) {
            case 0:
                $scope = 'company';
                break;
            case 1:
                $scope = 'customer';
                break;
            case 2:
                $scope = 'delivery';
                break;
        }

        return $this->getTokenAndRefreshToken(request('mobile_number'), request('password'), $scope);
    }

    public function getTokenAndRefreshToken($mobile, $password, $scope, $branchId = null)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client();

        $response = $http->request('POST', getenv('APP_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $mobile,
                'password' => $password,
                'scope' => $scope,
            ],
        ]);

        $result = json_decode((string)$response->getBody(), true);
        $response = array_merge($result, [
            'scope' => $scope,
            'branch_id' => $branchId
        ]);

        return response()->json([
            'message' => '',
            'data' => $response
        ]);
    }

    public function refreshToken(RefreshTokenRequest $refreshTokenRequest)
    {
        $oClient = OClient::where('password_client', 1)->first();

        $response = Http::asForm()->post(getenv('APP_URL') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshTokenRequest->input('refresh_token'),
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
        ]);

        if (!$response->ok()) {
            return response()->json('Invalid refresh token', 403);
        }

        $result = json_decode($response->getBody(), true);
        return response()->json([
            'message' => null,
            'data' => $result
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'message' => 'successfully logged out',
            'data' => null
        ]);
    }

}
