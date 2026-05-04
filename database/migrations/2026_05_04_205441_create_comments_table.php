<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->boolean('is_approved')->default(true); // для модерации
            $table->timestamps();

            // Индексы для быстрого поиска
            $table->index(['game_id', 'created_at']);
            $table->index(['user_id']);
            $table->index(['parent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
