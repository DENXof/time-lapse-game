<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MassImportCommand extends Command
{
    protected $signature = 'games:mass-import {--limit=50}';
    protected $description = 'Массовый импорт игр из Steam по всем популярным жанрам';

    public function handle()
    {
        $limit = $this->option('limit');
        $genres = [
            'Action', 'Adventure', 'Strategy', 'Simulation', 'Sports',
            'Indie', 'Casual', 'MMO', 'Racing', 'Fighting', 'Puzzle',
            'Platformer', 'Shooter', 'RPG', 'Survival', 'Horror'
        ];

        $this->info("Начинаю массовый импорт игр...");

        foreach ($genres as $genre) {
            $this->info("Импорт жанра: {$genre}");
            try {
                Artisan::call("games:import", [
                    '--source' => 'steam',
                    '--genre' => $genre,
                    '--limit' => $limit
                ]);
                $this->line(Artisan::output());
            } catch (\Exception $e) {
                $this->error("Ошибка при импорте {$genre}: " . $e->getMessage());
            }
        }

        $this->info("✅ Массовый импорт завершён!");
        return 0;
    }
}
