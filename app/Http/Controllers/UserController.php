<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UserController extends Controller
{
    
    public function index()
    {
        return ResponseHelper::formatResponse(200,"Succes",User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $var = User::create($request->validated(),['password' => bcrypt($request->password), "created_at" => now(), "updated_at" => now()]);
        return ResponseHelper::formatResponse(201,"Succes",$var);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return ResponseHelper::formatResponse(200,"Succes",$usuario);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $usuario->fill($request->validated());
        if ($usuario->isDirty()) {
            $usuario->save();
        }
        return ResponseHelper::formatResponse(200,"Succes",$usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        if ($usuario->reviews->count() > 0) {
            return response()->json(['message' => 'Cannot delete user with reviews'], 400);
        }
        User::where('id', $usuario->id)->delete();
        
        return ResponseHelper::formatResponse(204,"Succes",null);
    }

    public function authUser()
    {
        return ResponseHelper::formatResponse(200,"Succes",auth()->user());
    }
    

}
