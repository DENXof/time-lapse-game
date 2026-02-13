<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🕹️ Добавление игр...');

        $games = [
            [
                'title' => 'Super Mario Bros',
                'slug' => 'super-mario-bros',
                'release_year' => 1985,
                'developer' => 'Nintendo',
                'publisher' => 'Nintendo',
                'description' => 'Классический платформер, определивший жанр на десятилетия вперед.',
                'short_description' => 'Классический платформер',
                'platform' => 'NES, SNES, Game Boy',
                'genre_id' => 1,
            ],
            [
                'title' => 'Doom',
                'slug' => 'doom',
                'release_year' => 1993,
                'developer' => 'id Software',
                'publisher' => 'id Software',
                'description' => 'Первый 3D-шутер, ставший эталоном жанра.',
                'short_description' => 'Культовый 3D-шутер',
                'platform' => 'PC, PS4, Xbox, Nintendo Switch',
                'genre_id' => 2,
            ],
            [
                'title' => 'StarCraft',
                'slug' => 'starcraft',
                'release_year' => 1998,
                'developer' => 'Blizzard Entertainment',
                'publisher' => 'Blizzard Entertainment',
                'description' => 'Космическая стратегия в реальном времени.',
                'short_description' => 'Космическая стратегия',
                'platform' => 'PC',
                'genre_id' => 3,
            ],
            [
                'title' => 'Half-Life',
                'slug' => 'half-life',
                'release_year' => 1998,
                'developer' => 'Valve Corporation',
                'publisher' => 'Sierra Studios',
                'description' => 'Научно-фантастический шутер, перевернувший представление о повествовании.',
                'short_description' => 'Революционный шутер',
                'platform' => 'PC, PS2',
                'genre_id' => 2,
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'slug' => 'witcher-3',
                'release_year' => 2015,
                'developer' => 'CD Projekt Red',
                'publisher' => 'CD Projekt',
                'description' => 'Ролевая игра с открытым миром.',
                'short_description' => 'Шедевр RPG',
                'platform' => 'PC, PS4, PS5, Xbox, Switch',
                'genre_id' => 1,
            ],
        ];

        foreach ($games as $gameData) {
            Game::updateOrCreate(
                ['slug' => $gameData['slug']],
                $gameData
            );
            $this->command->info("   ✅ Добавлена игра: {$gameData['title']}");
        }

        $this->command->info('✅ Игры добавлены!');
    }
}
