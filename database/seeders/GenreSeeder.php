<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Добавление жанров...');

        $genres = [
            [
                'name' => 'RPG',
                'slug' => 'rpg',
                'color' => '#28a745',
                'icon' => '⚔️',
                'description' => 'Ролевые игры с глубоким сюжетом',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Шутер',
                'slug' => 'shooter',
                'color' => '#dc3545',
                'icon' => '🔫',
                'description' => 'Игры с акцентом на стрельбу',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Стратегия',
                'slug' => 'strategy',
                'color' => '#007bff',
                'icon' => '♟️',
                'description' => 'Игры, требующие планирования',
                'sort_order' => 3,
                'is_active' => true
            ],
        ];

        foreach ($genres as $genreData) {
            Genre::updateOrCreate(
                ['slug' => $genreData['slug']],
                $genreData
            );
            $this->command->info("   ✅ Добавлен жанр: {$genreData['name']}");
        }

        $this->command->info('✅ Жанры добавлены!');
    }
}
