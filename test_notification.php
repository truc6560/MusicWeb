<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;

// Create test notification
$notification = Notification::create([
    'title' => 'Test Thông Báo 🔔',
    'content' => 'Đây là thông báo test để kiểm tra hệ thống hoạt động.',
    'type' => 'info',
    'is_active' => true,
]);

// Send to all users
$notification->sendToAllUsers();

echo "Test notification created successfully!\n";
echo "Notification ID: " . $notification->id . "\n";

$userCount = User::count();
echo "Sent to: " . $userCount . " users\n";
