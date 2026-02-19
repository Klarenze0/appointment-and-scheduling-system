<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register a new CLIENT account.
     * Staff and Admin accounts are created by Admin only.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'], // auto-hashed by User model cast
            'phone'    => $validated['phone'] ?? null,
            'role'     => 'client', // public registration is always client
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user'  => $this->formatUser($user),
            'token' => $token,
        ], 'Registration successful.', 201);
    }

    /**
     * Login any user (admin, staff, client).
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check credentials
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if account is soft-deleted (deactivated)
        if ($user->trashed()) {
            return $this->error('This account has been deactivated. Please contact support.', 403);
        }

        // Revoke previous tokens (single session per user)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user'  => $this->formatUser($user),
            'token' => $token,
        ], 'Login successful.');
    }

    /**
     * Logout — revoke the current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }

    /**
     * Return the currently authenticated user's profile.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'staff') {
            $user->load('staffProfile');
        }

        return $this->success($this->formatUser($user));
    }

    /**
     * Change password for authenticated user.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($validated['current_password'], $request->user()->password)) {
            return $this->error('Current password is incorrect.', 422);
        }

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        // Revoke all tokens so user must log in again
        $request->user()->tokens()->delete();

        return $this->success(null, 'Password changed successfully. Please log in again.');
    }

    /**
     * Format user data for API responses.
     * Controls exactly what fields are returned — never expose sensitive data.
     */
    private function formatUser(User $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'role'         => $user->role,
            'phone'        => $user->phone,
            'avatar'       => $user->avatar,
            'staff_profile'=> $user->staffProfile,
        ];
    }
}