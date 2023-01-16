<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        try {
            $name = $request->input('name');
            $email = $request->input('email');
            $password = Hash::make($request->input('password'));
    
            User::create([
                'name' => trim($name),
                'email' => trim($email),
                'password' => $password
            ]);
    
            return response()->json([
                'success' => true,
                'message' => "User has been created",
                'code' => 201
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false,
            'message' => $e->getMessage(),
            'code' => $e->getCode()]);
        }

    }
}
