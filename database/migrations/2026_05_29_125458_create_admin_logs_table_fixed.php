<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Удаляем старую таблицу если существует
        Schema::dropIfExists('admin_logs');

        // Создаём новую таблицу
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('action');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Внешний ключ на таблицу admins
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('cascade');

            // Индексы
            $table->index(['admin_id', 'created_at']);
            $table->index(['action']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
};
