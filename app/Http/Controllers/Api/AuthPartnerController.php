<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partners;
use Illuminate\Support\Facades\Hash;

class AuthPartnerController extends Controller
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
            'message' => 'Logout successful',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'partner' => $request->user(),
        ]);
    }
}
