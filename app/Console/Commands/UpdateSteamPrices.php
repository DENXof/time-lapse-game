<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\SteamService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateSteamPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:update-prices
                            {--force : Force update all games even if they have manual price}
                            {--app-id= : Update only specific app ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Steam prices for all games';

    /**
     * Execute the console command.
     */
    public function handle(SteamService $steamService)
    {
        $this->info('Starting Steam prices update...');

        // Получаем игры для обновления
        $query = Game::whereNotNull('steam_app_id');

        if (!$this->option('force')) {
            // Обновляем только те игры, у которых нет ручной цены
            $query->whereNull('manual_price');
        }

        if ($appId = $this->option('app-id')) {
            $query->where('steam_app_id', $appId);
            $this->info("Updating only app ID: {$appId}");
        }

        $games = $query->get();

        if ($games->isEmpty()) {
            $this->warn('No games found to update.');
            return 0;
        }

        $this->info("Found {$games->count()} games to update.");

        $updated = 0;
        $failed = 0;
        $progressBar = $this->output->createProgressBar($games->count());
        $progressBar->start();

        foreach ($games as $game) {
            try {
                // Пробуем получить цену из разных регионов
                $price = $steamService->getPrice($game->steam_app_id);

                if ($price) {
                    $game->manual_price = $price;
                    $game->save();
                    $updated++;
                    $this->line(" ✓ {$game->title}: {$price}");
                } else {
                    $failed++;
                    $this->line(" ✗ {$game->title}: price not found");
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Failed to update price for {$game->title}: " . $e->getMessage());
                $this->line(" ✗ {$game->title}: error - " . $e->getMessage());
            }

            // Задержка между запросами, чтобы не забанили
            sleep(1);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Update completed!");
        $this->info("✓ Updated: {$updated}");
        $this->error("✗ Failed: {$failed}");

        return 0;
    }
}
