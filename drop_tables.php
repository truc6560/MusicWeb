<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

Schema::dropIfExists('notification_user');
Schema::dropIfExists('notifications');

echo "Tables dropped successfully.\n";
