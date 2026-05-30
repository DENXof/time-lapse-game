<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Genre;
use App\Services\SteamService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportPopularGamesCommand extends Command
{
    protected $signature = 'games:import-popular';
    protected $description = 'Импорт популярных игр (CS, Dota, Silent Hill, Resident Evil, BioShock и т.д.)';

    public function handle(SteamService $steamService)
    {
        $this->info('🚀 Начинаю импорт популярных игр...');
        $this->newLine();

        $popularGames = [
            ['title' => 'Counter-Strike 2', 'steam_id' => 730],
            ['title' => 'Dota 2', 'steam_id' => 570],
            ['title' => 'Silent Hill 2', 'steam_id' => 2124480],
            ['title' => 'Silent Hill: Homecoming', 'steam_id' => 19000],
            ['title' => 'Resident Evil 4', 'steam_id' => 2050650],
            ['title' => 'Resident Evil 2', 'steam_id' => 883710],
            ['title' => 'Resident Evil 3', 'steam_id' => 952060],
            ['title' => 'Resident Evil 7', 'steam_id' => 418370],
            ['title' => 'BioShock', 'steam_id' => 7670],
            ['title' => 'BioShock 2', 'steam_id' => 8850],
            ['title' => 'BioShock Infinite', 'steam_id' => 8870],
            ['title' => 'Half-Life 2', 'steam_id' => 220],
            ['title' => 'Portal', 'steam_id' => 400],
            ['title' => 'Portal 2', 'steam_id' => 620],
            ['title' => 'Team Fortress 2', 'steam_id' => 440],
            ['title' => 'Left 4 Dead 2', 'steam_id' => 550],
            ['title' => 'Dead Space', 'steam_id' => 17470],
            ['title' => 'Dead Space 2', 'steam_id' => 47780],
            ['title' => 'Call of Duty: Modern Warfare', 'steam_id' => 1938090],
            ['title' => 'Call of Duty: Modern Warfare 2', 'steam_id' => 10180],
            ['title' => 'The Elder Scrolls V: Skyrim', 'steam_id' => 489830],
            ['title' => 'Fallout 4', 'steam_id' => 377160],
            ['title' => 'Cyberpunk 2077', 'steam_id' => 1091500],
            ['title' => 'Red Dead Redemption 2', 'steam_id' => 1174180],
            ['title' => 'Grand Theft Auto V', 'steam_id' => 271590],
            ['title' => 'Minecraft', 'steam_id' => null], // Не в Steam
            ['title' => 'World of Warcraft', 'steam_id' => null],
            ['title' => 'League of Legends', 'steam_id' => null],
            ['title' => 'Valorant', 'steam_id' => null],
            ['title' => 'Fortnite', 'steam_id' => null],
        ];

        $imported = 0;
        $skipped = 0;

        // Получаем или создаём жанр для популярных игр
        $defaultGenre = Genre::firstOrCreate(
            ['name' => 'Popular'],
            ['name' => 'Popular', 'color' => '#e74c3c', 'icon' => '🔥', 'slug' => 'popular']
        );

        foreach ($popularGames as $gameData) {
            $title = $gameData['title'];

            if (Game::where('title', $title)->exists()) {
                $this->warn("⏭️ Игра '{$title}' уже существует");
                $skipped++;
                continue;
            }

            // Если есть Steam ID, пытаемся получить данные из Steam
            if ($gameData['steam_id']) {
                $details = $steamService->getSteamGameDetails($gameData['steam_id']);
                if ($details) {
                    Game::create([
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'release_year' => $details['release_year'],
                        'developer' => $details['developer'],
                        'publisher' => $details['publisher'],
                        'description' => $details['description'],
                        'platform' => 'PC',
                        'genre_id' => $defaultGenre->id,
                        'steam_app_id' => $gameData['steam_id'],
                        'views_count' => 0,
                        'rating_avg' => 0,
                        'rating_count' => 0,
                    ]);
                    $this->info("✅ Добавлена игра: {$title} (данные из Steam)");
                    $imported++;
                    continue;
                }
            }

            // Если нет Steam ID или не удалось получить данные
            Game::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'release_year' => date('Y'),
                'developer' => 'Unknown',
                'publisher' => 'Unknown',
                'description' => 'Популярная игра. Описание отсутствует.',
                'platform' => 'PC',
                'genre_id' => $defaultGenre->id,
                'views_count' => 0,
                'rating_avg' => 0,
                'rating_count' => 0,
            ]);
            $this->info("✅ Добавлена игра: {$title} (без данных из Steam)");
            $imported++;
        }

        $this->newLine();
        $this->info("📊 ИТОГИ ИМПОРТА:");
        $this->info("   ✅ Добавлено игр: {$imported}");
        $this->info("   ⏭️ Пропущено (дубликаты): {$skipped}");
        $this->newLine();
        $this->info("🎉 Импорт популярных игр завершён!");

        return 0;
    }
}
