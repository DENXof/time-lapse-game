<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('game_era', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('era_id')->constrained()->onDelete('cascade');
            $table->text('significance')->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'era_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_era');
    }
};
