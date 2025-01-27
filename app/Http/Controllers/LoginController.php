<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ResponseHelper;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8'
            ]);

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                throw new \Exception('Invalid credentials');
            }

            $user = Auth::user();
            $expiration = JWTAuth::factory()->getTTL() * 60;
            $time = now()->addSeconds($expiration);

            return ResponseHelper::formatResponse(200, "Success", [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'time_server' => now()->toDateTimeString(),
                'expires_at_human' => $time->toDateTimeString(),
                'expires_at' => $time->timestamp

            ]);

        } catch (\Exception $error) {
            return ResponseHelper::formatResponse(400, "Error", [
                'error' => $error->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return ResponseHelper::formatResponse(200, "Success", [
                'message' => 'User logged out successfully'
            ]);
        } catch (\Exception $error) {
            return ResponseHelper::formatResponse(400, "Error", [
                'error' => $error->getMessage()
            ]);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            $expiration = JWTAuth::factory()->getTTL() * 60;
            $time = now()->addSeconds($expiration);

            return ResponseHelper::formatResponse(200, "Success", [
                'token' => $newToken,
                'token_type' => 'Bearer',  
                'time_server' => now(),        
                'expires_at_human' => $time->toDateTimeString(),
                'expires_at' => $time->timestamp
            ]);

        } catch (\Exception $error) {
            return ResponseHelper::formatResponse(400, "Error", [
                'error' => $error->getMessage()
            ]);
        }
    }
}