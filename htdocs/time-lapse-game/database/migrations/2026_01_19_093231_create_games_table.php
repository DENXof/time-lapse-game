<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('release_year');
            $table->string('developer');
            $table->string('publisher')->nullable();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('platforms')->nullable();
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->float('rating_avg')->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();

            $table->index('release_year');
            $table->index('genre_id');
            $table->index('rating_avg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
