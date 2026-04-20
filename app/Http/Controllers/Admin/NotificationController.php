<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string',
            'type' => 'required|in:info,success,warning,error',
            'send_to' => 'required|in:all,admins',
        ]);

        $notification = Notification::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_url' => $validated['image_url'],
            'type' => $validated['type'],
            'is_active' => true,
        ]);

        if ($validated['send_to'] === 'all') {
            $notification->sendToAllUsers();
        } else {
            $notification->sendToAdmins();
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được tạo và gửi thành công!');
    }

    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string',
            'type' => 'required|in:info,success,warning,error',
            'is_active' => 'boolean',
        ]);

        $notification->update($validated);
        return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được cập nhật!');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được xóa!');
    }

    public function resend(Notification $notification)
    {
        $notification->sendToAllUsers();
        return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được gửi lại!');
    }
}

