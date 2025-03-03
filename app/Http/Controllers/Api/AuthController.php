<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->department_id) {
                $user->load('department');
            }
            
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }

    /**
     * register
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255', 'unique:users'],
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'department_id' => ['nullable', 'exists:departments,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'department_id' => $request->department_id,
                'role' => 'student',
                'active' => true
            ]);

            if ($user->department_id) {
                $user->load('department');
            }

            return response()->json([
                'message' => 'User registered successfully',
                'data' => [
                    'user' => new UserResource($user),
                    'redirect_url' => '/login'
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
