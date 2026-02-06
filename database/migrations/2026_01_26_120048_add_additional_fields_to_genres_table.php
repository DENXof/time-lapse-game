<?php
// database/migrations/2026_01_26_120048_add_additional_fields_to_genres_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Добавляем недостающие поля в таблицу genres (если их нет)
        Schema::table('genres', function (Blueprint $table) {
            // Добавляем sort_order если нет
            if (!Schema::hasColumn('genres', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('icon');
            }

            // Добавляем is_active если нет
            if (!Schema::hasColumn('genres', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }
        });

        // 2. Делаем поле name уникальным (безопасно)
        // Сначала убираем возможные дубликаты
        \DB::statement('UPDATE genres SET name = CONCAT(name, "_", id) WHERE id IN (
            SELECT id FROM (
                SELECT id, ROW_NUMBER() OVER (PARTITION BY name ORDER BY id) as rn
                FROM genres
            ) t WHERE rn > 1
        )');

        // Затем добавляем уникальное ограничение
        Schema::table('genres', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });

        // 3. Создаем связующую таблицу game_genre если не существует
        if (!Schema::hasTable('game_genre')) {
            Schema::create('game_genre', function (Blueprint $table) {
                $table->id();
                $table->foreignId('game_id')->constrained()->onDelete('cascade');
                $table->foreignId('genre_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['game_id', 'genre_id']);
            });
        }
    }

    public function down(): void
    {
        // Откат изменений
        Schema::dropIfExists('game_genre');

        Schema::table('genres', function (Blueprint $table) {
            // Удаляем уникальное ограничение
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('genres');

            if (array_key_exists('genres_name_unique', $indexes)) {
                $table->dropUnique(['name']);
            }

            // Удаляем добавленные колонки если они существуют
            if (Schema::hasColumn('genres', 'sort_order')) {
                $table->dropColumn('sort_order');
            }

            if (Schema::hasColumn('genres', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
