<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(StoreUserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);


        if (!$user) {
            $response = [
                'Message' => 'can not create this user'
            ];
            return response()->json($response, 201);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'User' => $user,
            'Token' => $token
        ];

        return response()->json($response, 201);
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
                'Message' => 'User name or password is incorect'
            ];

            return response()->json($response, 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message' => 'User successfully login',
            'Token' => $token,
            'User' => $user,
        ];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
