<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
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
            $expiration = $this->getIntegerExpiration() * 60;
            $time = Carbon::now()->addSeconds($expiration)->setTimezone('America/El_Salvador');

            return ResponseHelper::formatResponse(200, "Success", [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'time_server' => Carbon::now()->setTimezone('America/El_Salvador')->format('d/m/Y H:i:s'),
                'expires_at_human' => $time->format('d/m/Y H:i:s'),
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
            $expiration = $this->getIntegerExpiration()  * 60;
            $time = Carbon::now()->addSeconds($expiration)->setTimezone('America/El_Salvador');

            return ResponseHelper::formatResponse(200, "Success", [
                'token' => $newToken,
                'token_type' => 'Bearer',  
                'time_server' => Carbon::now()->setTimezone('America/El_Salvador')->format('d/m/Y H:i:s'),        
                'expires_at_human' => $time->format('d/m/Y H:i:s'),
                'expires_at' => $time->timestamp
            ]);

        } catch (\Exception $error) {
            return ResponseHelper::formatResponse(400, "Error", [
                'error' => $error->getMessage()
            ]);
        }
    }

    private function getIntegerExpiration(){
        $experation = 1;
        $integerE = JWTAuth::factory()->getTTL();
        if($integerE - 1 >0 ){
            $experation = $integerE - 1;
        }
        return $experation;
    }
}