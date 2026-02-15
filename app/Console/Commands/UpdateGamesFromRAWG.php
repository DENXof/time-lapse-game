<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateGamesFromRAWG extends Command
{
    protected $signature = 'games:update-rawg';
    protected $description = 'Обновление игр через RAWG API';

    private $apiKey = '741bdfe6f72b4a78bddff4b0a4d3d7b4';

    public function handle()
    {
        $this->info('🔄 Начинаем обновление игр через RAWG...');

        $games = Game::all();
        $bar = $this->output->createProgressBar($games->count());
        $bar->start();

        foreach ($games as $game) {
            $this->updateGameFromRAWG($game);
            $bar->advance();
            sleep(1);
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Обновление завершено!');
    }

    private function updateGameFromRAWG($game)
    {
        try {
            // Оригинальное название
            $searchName = $game->title;

            // Убираем спецсимволы
            $cleanName = preg_replace('/[^\p{L}\p{N}\s]/u', '', $game->title);

            // Берём только первое слово для поиска (если название длинное)
            $firstWord = explode(' ', $cleanName)[0];

            // Массив вариантов поиска
            $searchQueries = [
                $game->title,           // Оригинал
                $cleanName,              // Без спецсимволов
                $firstWord,              // Только первое слово
                str_replace(' ', '%20', $game->title) // С заменой пробелов
            ];

            // Убираем дубликаты
            $searchQueries = array_unique($searchQueries);

            $found = false;
            $gameData = null;

            foreach ($searchQueries as $query) {
                if (empty($query)) continue;

                $response = Http::get('https://api.rawg.io/api/games', [
                    'key' => $this->apiKey,
                    'search' => $query,
                    'page_size' => 5,
                    'search_precise' => false,
                    'search_exact' => false
                ]);

                if ($response->failed() || empty($response->json()['results'])) {
                    continue;
                }

                $results = $response->json()['results'];

                // Ищем наиболее похожее название
                foreach ($results as $result) {
                    similar_text(strtolower($game->title), strtolower($result['name']), $percent);
                    if ($percent > 70) { // Если совпадение больше 70%
                        $gameData = $result;
                        $found = true;
                        break 2;
                    }
                }

                // Если ничего не нашли, берём первый результат
                if (!$found && !empty($results)) {
                    $gameData = $results[0];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->warn("⚠️ Не найдено: {$game->title}");
                return;
            }

            $updateData = [];

            // Описание
            if (!empty($gameData['description_raw'])) {
                $updateData['description'] = $gameData['description_raw'];
                $updateData['short_description'] = substr($gameData['description_raw'], 0, 200) . '...';
            }

            // Платформы
            if (!empty($gameData['platforms'])) {
                $platforms = collect($gameData['platforms'])
                    ->pluck('platform.name')
                    ->take(5)
                    ->implode(', ');
                $updateData['platform'] = $platforms;
            }

            // Разработчик
            if (!empty($gameData['developers'])) {
                $updateData['developer'] = $gameData['developers'][0]['name'];
            }

            // Издатель
            if (!empty($gameData['publishers'])) {
                $updateData['publisher'] = $gameData['publishers'][0]['name'];
            }

            // Год
            if (!empty($gameData['released'])) {
                $updateData['release_year'] = (int) substr($gameData['released'], 0, 4);
            }

            // Рейтинг
            if (!empty($gameData['rating'])) {
                $updateData['rating_avg'] = $gameData['rating'];
                $updateData['rating_count'] = $gameData['ratings_count'] ?? 0;
            }

            // Обложка
            if (!empty($gameData['background_image'])) {
                try {
                    $imageContents = Http::timeout(10)->get($gameData['background_image'])->body();
                    $imageName = 'covers/' . $game->slug . '.jpg';
                    Storage::disk('public')->put($imageName, $imageContents);
                    $updateData['cover_image'] = $imageName;
                } catch (\Exception $e) {}
            }

            if (!empty($updateData)) {
                $game->update($updateData);
                $this->line(" ✅ Обновлено: {$game->title} (найдено как {$gameData['name']})");
            }

        } catch (\Exception $e) {
            $this->error("❌ Ошибка с игрой {$game->title}: " . $e->getMessage());
        }
    }
}
