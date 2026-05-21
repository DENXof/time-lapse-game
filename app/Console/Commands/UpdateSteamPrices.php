<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\SteamService;
use Illuminate\Console\Command;

class UpdateSteamPrices extends Command
{
    protected $signature = 'steam:update-prices {--force : Принудительное обновление}';
    protected $description = 'Обновление цен игр из Steam API';

    public function handle(SteamService $steamService)
    {
        $this->info('🔄 Начинаю обновление цен из Steam...');

        $games = Game::whereNotNull('steam_app_id')
            ->where('steam_app_id', '!=', '')
            ->get();

        if ($games->isEmpty()) {
            $this->warn('Нет игр с указанным Steam App ID');
            return 0;
        }

        $this->info("Найдено игр: " . $games->count());

        $bar = $this->output->createProgressBar($games->count());
        $bar->start();

        foreach ($games as $game) {
            try {
                $prices = $steamService->getAllPrices($game->steam_app_id);

                if (!empty($prices)) {
                    $game->prices = $prices;
                    $game->save();
                }
            } catch (\Exception $e) {
                $this->error("\nОшибка для {$game->title}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Обновление цен завершено!');

        return 0;
    }
}
