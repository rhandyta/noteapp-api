<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $email = $request->input('email');
            if(!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'messages' => 'Unauthenticated',
                    'code' => 401
                ]);
            }
            $user = User::where('email', '=', $email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'code' => 200,
                'type' => 'Bearer',
                "user" => $user,
                'token' => $token
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }
}
