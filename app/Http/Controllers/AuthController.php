<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        if(!Auth::attempt($request->only(['email', 'password']))) {
            return [
                'success' => false,
                'error' => 'Invalid data'
            ];
        }
        else {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('token')->plainTextToken;
            return [
                'success' => true,
                'token' => $token
            ];
        }
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }

    public function user(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        return response()->json([
            'data' => $token->tokenable
        ]);
    }
}
