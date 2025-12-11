<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLoginDetailsMail;

class AuthController extends Controller
{
    /**
     * POST /api/auth/register
     */
    public function signup(RegisterRequest $request)
    {
        // Validate & keep the plain password for email
        $data = $request->validated();
        $plainPassword = $data['password'];

        // Create user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'mobile'     => $data['mobile'] ?? null,
            'password'   => Hash::make($plainPassword),
        ]);

        // Create personal access token (sanctum)
        $token = $user->createToken('mobile')->plainTextToken;

        // Send login details email (send synchronously)
        // If you want to queue the mail, use ->queue(...) and implement ShouldQueue on the Mailable.
        Mail::to($user->email)->send(new SendLoginDetailsMail($user, $plainPassword));

        // Return JSON response
        return response()->json([
            'status'  => true,
            'message' => 'Registration successful.',
            'data'    => [
                'user'       => new UserResource($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }


    /**
     * POST /api/auth/login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Optional: Revoke old tokens for single-session login behavior
        $user->tokens()->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful.',
            'data'    => [
                'user'       => new UserResource($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }


    /**
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // revoke current token only
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * GET /api/me
     */
    public function me(Request $request)
    {
        // // Check if user is authenticated
        // if (!$request->user()) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Unauthenticated.',
        //     ], 401);
        // }

        // Authenticated → return user resource
        return response()->json([
            'status'  => true,
            'message' => 'User retrieved successfully.',
            'data'    => new UserResource($request->user()),
        ]);
    }
}
