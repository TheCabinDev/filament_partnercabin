<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use App\Notifications\PartnerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $partner = Partners::where('email', $request->email)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$partner) {
            Log::info('ProfileController|404', ['email' => $request->email]);

            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terdaftar',
            ], 404);
        }

        if (!Hash::check($request->password, $partner->password)) {
            Log::info('ProfileController|401', ['email' => $request->email]);
            return response()->json([
                'success' => false,
                'message' => 'Password salah',
            ], 401);
        }

        if ($partner->status === 'INACTIVE') {
            Log::info('ProfileController|403', ['email' => $request->email]);
            return response()->json([
                'success' => false,
                'message' => 'Akun belum aktif. Silahkan hubungi admin',
            ], 403);
        }

        $token = $partner->createToken('partner_token')->plainTextToken;
        Log::info('ProfileController|200', ['email' => $request->email]);

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
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $request->user()->only( // Use 'only' to prevent leaking sensitive data
                'id',
                'name',
                'phone',
                'destination_bank',
                'account_number',
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
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if (!$request->filled('name') && !$request->filled('phone')) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal satu field harus diisi.',
            ], 422);
        }

        $partner = auth()->user();

        // Update hanya field yang dikirim dan tidak null
        if ($request->filled('name')) {
            $partner->name = $request->name;
        }

        if ($request->filled('phone')) {
            $partner->phone = $request->phone;
        }

        $partner->save();

        Log::info('Sending notification to partner: ' . $partner->id);

        try {
            $partner->notify(new PartnerNotification(
                'Profile Updated',
                'Your profile has been updated successfully.',
                null,
                'success'
            ));

            Log::info('Notification sent successfully');
        } catch (\Exception $e) {
            Log::error('Notification failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'phone' => $partner->phone,
                'email' => $partner->email,
                'image_profile' => $partner->image_profile,
                'status' => $partner->status,
            ],
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        // 1. Validasi input: password lama, password baru, dan konfirmasi
        $request->validate([
            'current_password' => 'required', // Memerlukan middleware 'password' di Laravel
            'new_password' => 'required|string|min:8',
        ]);

        $partner = auth()->user();

        // 2. Verifikasi password lama
        if (!Hash::check($request->current_password, $partner->password)) {
            return response()->json(['error' => 'Kata sandi lama salah.'], 403);
        }

        if ($partner->status === 'INACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum aktif. Silahkan hubungi admin',
            ], 403);
        }

        // 3. Update password baru
        $partner->password = Hash::make($request->new_password);
        $partner->save();

        try {
            $partner->notify(new PartnerNotification(
                'Password Changed',
                'Kata sandi Anda telah berhasil diubah. Jika ini bukan Anda, silakan hubungi dukungan segera.',
                null,
                'warning'
            ));
        } catch (\Exception $e) {
            Log::error('Notification failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return response()->json(['message' => 'Kata sandi berhasil diubah.'], 200);
    }
}
