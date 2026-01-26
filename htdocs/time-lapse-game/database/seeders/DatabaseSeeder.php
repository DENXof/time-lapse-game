<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Game;
use App\Models\Era;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Начинаем заполнение базы данных...');

        // 1. Жанры
        $genres = [
            ['name' => 'RPG', 'slug' => 'rpg', 'color' => '#28a745', 'icon' => '⚔️'],
            ['name' => 'Шутер', 'slug' => 'shooter', 'color' => '#dc3545', 'icon' => '🔫'],
            ['name' => 'Стратегия', 'slug' => 'strategy', 'color' => '#007bff', 'icon' => '♟️'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }

        $this->command->info('✅ Создано ' . Genre::count() . ' жанров');

        // 2. Эпохи
        $eras = [
            [
                'name' => '1980-е: Золотая эра аркад',
                'slug' => '1980s',
                'start_year' => 1980,
                'end_year' => 1989,
                'description' => 'Расцвет аркадных автоматов',
                'characteristics' => '8-битная графика',
                'color_primary' => '#e52521',
                'color_secondary' => '#ffd700',
                'font_family' => 'monospace'
            ],
            [
                'name' => '1990-е: 3D-революция',
                'slug' => '1990s',
                'start_year' => 1990,
                'end_year' => 1999,
                'description' => 'Переход к трехмерной графике',
                'characteristics' => 'Полигональная графика',
                'color_primary' => '#333333',
                'color_secondary' => '#666666',
                'font_family' => 'sans-serif'
            ],
        ];

        foreach ($eras as $era) {
            Era::create($era);
        }

        $this->command->info('✅ Создано ' . Era::count() . ' исторических периодов');

        // 3. Игры (МИНИМАЛЬНЫЙ набор)
        $games = [
            [
                'title' => 'Super Mario Bros',
                'slug' => 'super-mario-bros',
                'release_year' => 1985,
                'developer' => 'Nintendo',
                'description' => 'Классический платформер',
                'genre_id' => 1,
            ],
            [
                'title' => 'Doom',
                'slug' => 'doom',
                'release_year' => 1993,
                'developer' => 'id Software',
                'description' => 'Первый 3D-шутер',
                'genre_id' => 2,
            ],
            [
                'title' => 'StarCraft',
                'slug' => 'starcraft',
                'release_year' => 1998,
                'developer' => 'Blizzard',
                'description' => 'Космическая стратегия',
                'genre_id' => 3,
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }

        $this->command->info('✅ Создано ' . Game::count() . ' игр');
        $this->command->info('🎉 База данных успешно заполнена!');
    }
}
