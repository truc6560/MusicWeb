<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function getNotifications(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $user = auth()->user();
        
        // Get user's notifications
        $notifications = $user->notifications()
            ->where('is_active', true)
            ->orderByPivot('created_at', 'desc')
            ->take(10)
            ->get();

        // Map notification data
        $notificationData = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'content' => $notification->content,
                'image_url' => $notification->image_url,
                'type' => $notification->type,
                'is_read' => (bool) $notification->pivot->is_read,
                'created_at' => $notification->created_at->format('H:i d/m/Y'),
                'created_at_raw' => $notification->created_at,
            ];
        });

        // Count unread notifications
        $unreadCount = $user->notifications()
            ->wherePivot('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notificationData->toArray(),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        
        auth()->user()->notifications()->updateExistingPivot($notificationId, [
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        auth()->user()->unreadNotifications()->get()->each(function ($notification) {
            auth()->user()->notifications()->updateExistingPivot($notification->id, [
                'is_read' => true,
                'read_at' => now(),
            ]);
        });

        return response()->json(['success' => true]);
    }
}

