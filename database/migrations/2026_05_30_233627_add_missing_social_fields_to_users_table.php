<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Проверяем существование колонок перед добавлением
            if (!Schema::hasColumn('users', 'vk')) {
                $table->string('vk')->nullable()->after('telegram');
            }
            if (!Schema::hasColumn('users', 'github')) {
                $table->string('github')->nullable()->after('vk');
            }
            // discord уже существует, не добавляем
            if (!Schema::hasColumn('users', 'steam')) {
                $table->string('steam')->nullable()->after('discord');
            }
            if (!Schema::hasColumn('users', 'twitch')) {
                $table->string('twitch')->nullable()->after('steam');
            }
            if (!Schema::hasColumn('users', 'youtube')) {
                $table->string('youtube')->nullable()->after('twitch');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['vk', 'github', 'steam', 'twitch', 'youtube']);
        });
    }
};
