<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SyncUsersSchema extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 100)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id', 100)->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'reset_token')) {
                $table->string('reset_token', 100)->nullable()->after('google_id');
            }
            if (!Schema::hasColumn('users', 'password_hash')) {
                $table->string('password_hash')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->unique()->after('password_hash');
            }
            if (!Schema::hasColumn('users', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->integer('status')->default(1)->after('avatar_url');
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(0)->after('status');
            }
            if (!Schema::hasColumn('users', 'registration_date')) {
                $table->timestamp('registration_date')->nullable()->after('is_admin');
            }
        });

        if (Schema::hasColumn('users', 'password') && Schema::hasColumn('users', 'password_hash')) {
            DB::table('users')->whereNotNull('password')->update(['password_hash' => DB::raw('password')]);
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('users', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'reset_token')) {
                $table->dropColumn('reset_token');
            }
            if (Schema::hasColumn('users', 'password_hash')) {
                $table->dropColumn('password_hash');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'avatar_url')) {
                $table->dropColumn('avatar_url');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
            if (Schema::hasColumn('users', 'registration_date')) {
                $table->dropColumn('registration_date');
            }
        });
    }
}
