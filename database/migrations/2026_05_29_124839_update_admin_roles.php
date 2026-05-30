<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Сначала обновляем существующие записи
        DB::table('admins')->where('role', 'super_admin')->update(['role' => 'super_admin']);
        DB::table('admins')->where('role', 'admin')->update(['role' => 'admin']);
        DB::table('admins')->where('role', 'moderator')->update(['role' => 'admin']); // Модераторов делаем админами

        // Меняем enum поле
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin'])->default('admin')->change();
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'moderator'])->default('admin')->change();
        });
    }
};
