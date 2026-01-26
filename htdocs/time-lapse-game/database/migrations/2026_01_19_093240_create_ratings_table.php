<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('user_ip');
            $table->tinyInteger('value')->check('value BETWEEN 1 AND 5');
            $table->timestamps();

            $table->unique(['game_id', 'user_ip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
