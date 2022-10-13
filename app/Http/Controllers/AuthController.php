<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Wrong email or password.'
            ], 404);
        }
        else {
            $user = User::where('email', $request->email)->firstOrFail();
            return response()->json([
                'success' => true,
                'token' => $user->createToken('token')->plainTextToken
            ]);
        }
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }

    public function user()
    {
        return response()->json([
            'data' => auth()->user()
        ]);
    }

    public function verify($userId)
    {
        /** @var User $user */
        $user = User::findOrFail($userId);

        if ($user->hasVerifiedEmail()) {
            return 'Already verified';
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return 'Success verified';
    }
}
