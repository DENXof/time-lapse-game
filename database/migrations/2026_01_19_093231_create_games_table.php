<?php
// МИГРАЦИЯ СОЗДАНИЯ ИГР
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
            $table->string('platform')->nullable(); // ← убрал ->after()
            $table->string('cover_image')->nullable();
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');

            $table->index('release_year');
            $table->index('genre_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
