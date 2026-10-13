<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registrationnn
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'learner',
        ]);

        $token = $user->createToken('dealora-auth')->plainTextToken;

        return response()->json([
            'message' => 'Welcome to Dealora! Your account has been created.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Login
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken('dealora-auth')->plainTextToken;

        return response()->json([
            'message' => 'Welcome back to Dealora!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }


    public function user(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return response()->json([
            'user' => $user,
        ], 200);
    }

    // Logoutttt
    public function logout(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $accessToken = $user->currentAccessToken();

        if ($accessToken instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $accessToken->delete();
        }

        return response()->json([
            'message' => 'You have successfully logged out.',
        ], 200);
    }
}
