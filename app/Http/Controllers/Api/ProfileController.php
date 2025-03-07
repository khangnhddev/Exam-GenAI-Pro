<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user profile.
     */
    public function index()
    {
        $user = auth()->user();
        $certificates = Certificate::where('user_id', $user->id)->get();

        return view('user.profile', compact('user', 'certificates'));
    }

    /**
     * Display the user certificate.
     */
    public function showCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('user.certificate', compact('certificate'));
    }

    /**
     * user.value
     */
    public function show(Request $request)
    {
        // Load relationships if needed
        $user = $request->user()->load(['certificates', 'examAttempts']);
        return new ProfileResource($user);
    }

    /** 
     * Update the user profile.
    */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Remove null values from validated data
        $updateData = array_filter($validated, function ($value) {
            return !is_null($value);
        });

        $user->update($updateData);

        return new ProfileResource($user);
    }

    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'different:current_password', 
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
                'new_password_confirmation' => ['required', 'same:new_password'],
            ]);

            $user = $request->user();

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'The provided password does not match your current password.',
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            return response()->json([
                'message' => 'Password updated successfully',
                'data' => new ProfileResource($user)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Password update failed:', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to update password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
