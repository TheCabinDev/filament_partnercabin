<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->input('keys.p256dh'),
            $request->input('keys.auth')
        );

        return response()->json([
            'success' => true,
            'message' => 'Push subscription saved successfully.',
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $request->user()->deletePushSubscription($request->endpoint);

        return response()->json([
            'success' => true,
            'message' => 'Push subscription deleted successfully.',
        ]);
    }

    public function getVapidPublicKey()
    {
        return response()->json([
            'success' => true,
            'public_key' => config('webpush.vapid.public_key'),
        ]);
    }
}
