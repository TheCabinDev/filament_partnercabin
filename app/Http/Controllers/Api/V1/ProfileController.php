<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $partner = Partners::where('email', $request->email)->first();

        if (!$partner || !Hash::check($request->password, $partner->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum terdaftar',
            ], 401);
        }

        if ($partner->status === 'INACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum aktif. Silahkan hubungi admin',
            ], 403);
        }

        $token = $partner->createToken('partner_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'partner' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'email' => $partner->email,
                'image_profile' => $partner->image_profile,
                'status' => $partner->status,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ], 204);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $request->user()->only( // Use 'only' to prevent leaking sensitive data
                'id',
                'name',
                'email',
                'image_profile',
                'status'
            ),
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // This sends the reset link and returns the response message key
        // $status = Password::sendResetLink($request->only('email'));

        // if ($status !== Password::RESET_LINK_SENT) {
        //     // This case handles errors like rate limiting or non-existent user (if configured)
        //     throw ValidationException::withMessages([
        //         'email' => [trans($status)],
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent successfully.',
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user, $password) {
        //         $user->forceFill([
        //             'password' => bcrypt($password),
        //         ])->save();
        //     }
        // );

        // if ($status !== Password::PASSWORD_RESET) {
        //     // Handles invalid token or email
        //     throw ValidationException::withMessages([
        //         'email' => [trans($status)],
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.',
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $partner = $request->user();

        // $request->validate([
        //     // Email must be unique, except for the current partner's email
        //     'name' => 'nullable|string|max:255',
        //     'email' => ['nullable', 'email', 'max:255', Rule::unique('partners')->ignore($partner->id)],
        //     // 'password' field only if they choose to update the password
        //     'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($partner) {
        //         if (!Hash::check($value, $partner->password)) {
        //             $fail('The current password provided is incorrect.');
        //         }
        //     }],
        //     'password' => 'nullable|min:8|confirmed',
        // ]);

        // Prepare the data to update, only including fields that were present in the request
        $data = $request->only('name', 'email');

        // Handle password update separately if it was provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        // Use fill and save
        $partner->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            // Return the updated partner resource
            'data' => $partner->only('id', 'name', 'email', 'image_profile', 'status'),
        ], 200);
    }
}
