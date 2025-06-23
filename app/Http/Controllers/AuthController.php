<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
   
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'role_id'      => $data['role_id'],
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Successful registration',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Successful login',
            'user'    => $user,
            'token'   => $token
        ]);
    }
}