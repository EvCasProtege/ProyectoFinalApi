<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ResponseHelper;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try{

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8'
            ]);

            //Extraigo las credenciales del request
            $credentials = $request->only('email','password');

            if(!Auth::attempt($credentials) ){
                throw new Exception('Invalid credentials');
            }

            $user = $request->user();

            $token = $user->createToken('auth_token')->plainTextToken;
            $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));

            return ResponseHelper::formatResponse(200,"Succes",[
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiration->toDateTimeString()
            ]);

        }catch(Exception $error){
            return  ResponseHelper::formatResponse(400,"Error",[
                'error' => $error->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        try{
            // Revocar el token actual del usuario
            // $request->user()->currentAccessToken()->delete();
            auth()->user()->tokens()->delete();

            $request->session()->invalidate();

            return ResponseHelper::formatResponse(200,"Succes",[
                'message' => 'User logged out successfully'
            ]);
        }catch(Exception $error){
        return ResponseHelper::formatResponse(400,"Error",[
            'error' => $error->getMessage()
        ], 400);
    };

    }

    public function refresh(Request $request)
    {
        try{
            $user = $request->user();

            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;
            
            $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));

            return ResponseHelper::formatResponse(200,"Succes",[
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiration->toDateTimeString()
            ]);

        }catch(Exception $error){
            return ResponseHelper::formatResponse(400,"Succes",[
                'error' => $error->getMessage()
            ]);
        }
    }
}