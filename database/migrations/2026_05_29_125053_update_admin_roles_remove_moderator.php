<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Обновляем существующих модераторов до обычных админов
        DB::table('admins')->where('role', 'moderator')->update(['role' => 'admin']);

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
