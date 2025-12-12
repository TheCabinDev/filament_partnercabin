<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? '',
                'message' => $notification->data['message'] ?? '',
                'type' => $notification->data['type'] ?? 'info',
                'action_url' => $notification->data['action_url'] ?? null,
                'is_read' => $notification->read_at !== null,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Notifications retrieved successfully.',
            'data' => [
                'current_page' => $notifications->currentPage(),
                'data' => $data,
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    public function unreadCount(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        Log::info('Marking notification as read', ['id' => $id, 'user_id' => $request->user()->id]);

        // Cari notification milik user
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or does not belong to you.',
            ], 404);
        }

        $notification->markAsRead();

        Log::info('Notification marked as read successfully', ['id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
            'data' => [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? '',
                'message' => $notification->data['message'] ?? '',
                'type' => $notification->data['type'] ?? 'info',
                'is_read' => true,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ],
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Log::info('All notifications marked as read', ['count' => $count, 'user_id' => $request->user()->id]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
            'count' => $count,
        ]);
    }

    public function delete(Request $request, $id)
    {
        Log::info('Deleting notification', ['id' => $id, 'user_id' => $request->user()->id]);

        // Cari notification milik user
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or does not belong to you.',
            ], 404);
        }

        $notification->delete();

        Log::info('Notification deleted successfully', ['id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }
}
