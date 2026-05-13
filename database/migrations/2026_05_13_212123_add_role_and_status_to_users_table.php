<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'moderator', 'admin'])->default('user');
            $table->enum('status', ['active', 'banned'])->default('active');
            $table->timestamp('banned_until')->nullable();
            $table->string('ban_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'banned_until', 'ban_reason']);
        });
    }
};
