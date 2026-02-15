<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateGamesFromIGDB extends Command
{
    protected $signature = 'games:update-igdb';
    protected $description = 'Обновление игр через IGDB API';

    private $clientId = '7jsjmcz4lu12pmh75b2zm6343j4lyx';
    private $clientSecret = '6515xe28ygdx5aqjve7b1f2z7u74e5';
    private $accessToken = null;

    public function handle()
    {
        $this->info('🔄 Получаем токен доступа...');
        $this->getAccessToken();

        $this->info('🔄 Начинаем обновление игр через IGDB...');

        $games = Game::all();
        $bar = $this->output->createProgressBar($games->count());
        $bar->start();

        foreach ($games as $game) {
            $this->updateGameFromIGDB($game);
            $bar->advance();
            sleep(1);
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Обновление завершено!');
    }

    private function getAccessToken()
    {
        $response = Http::post('https://id.twitch.tv/oauth2/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials'
        ]);

        if ($response->failed()) {
            $this->error('❌ Не удалось получить токен!');
            exit;
        }

        $this->accessToken = $response->json()['access_token'];
        $this->info('✅ Токен получен');
    }

    private function updateGameFromIGDB($game)
    {
        try {
            // Поиск игры с проверкой похожести
            $searchResponse = Http::withHeaders([
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->accessToken
            ])->withBody(
                    'fields name,summary,first_release_date,platforms.name,cover.url; search "' . $game->title . '"; limit 10;',
                    'text/plain'
                )->post('https://api.igdb.com/v4/games');

            if ($searchResponse->failed() || empty($searchResponse->json())) {
                $this->warn("⚠️ Не найдено: {$game->title}");
                return;
            }

            $results = $searchResponse->json();
            $bestMatch = null;
            $bestScore = 0;

            foreach ($results as $result) {
                similar_text(strtolower($game->title), strtolower($result['name']), $percent);
                if ($percent > $bestScore) {
                    $bestScore = $percent;
                    $bestMatch = $result;
                }
            }

            if ($bestScore < 60) {
                $this->warn("⚠️ Нет точного совпадения для: {$game->title} (совпадение {$bestScore}%)");
                return;
            }

            $gameData = $bestMatch;
            $updateData = [];

            // Описание
            if (!empty($gameData['summary'])) {
                $updateData['description'] = $gameData['summary'];
                $updateData['short_description'] = substr($gameData['summary'], 0, 200) . '...';
            }

            // Платформы
            if (!empty($gameData['platforms'])) {
                $platforms = collect($gameData['platforms'])
                    ->pluck('name')
                    ->take(5)
                    ->implode(', ');
                $updateData['platform'] = $platforms;
            }

            // Год с проверкой на корректность
            if (!empty($gameData['first_release_date']) && $gameData['first_release_date'] > 0) {
                $timestamp = $gameData['first_release_date'];
                // Проверяем, не в секундах ли уже
                if ($timestamp > 10000000000) { // Больше 10 миллиардов — значит в миллисекундах
                    $timestamp = $timestamp / 1000;
                }
                $year = (int) date('Y', $timestamp);
                // Проверяем, что год реалистичный (1900-2026)
                if ($year >= 1900 && $year <= 2026) {
                    $updateData['release_year'] = $year;
                } else {
                    $this->warn("⚠️ Некорректный год для {$game->title}: {$year}, оставляем {$game->release_year}");
                }
            }

            // ===== ОБЛОЖКА =====
            if (!empty($gameData['cover']['url'])) {
                // Меняем размер на большой
                $imageUrl = str_replace('t_thumb', 't_cover_big', $gameData['cover']['url']);
                $imageUrl = 'https:' . $imageUrl;

                $imageContents = Http::timeout(10)->get($imageUrl)->body();
                $imageName = 'covers/' . $game->slug . '.jpg';
                Storage::disk('public')->put($imageName, $imageContents);
                $updateData['cover_image'] = $imageName;

                $this->line("   🖼️ Обложка скачана для {$game->title}");
            }

            if (!empty($updateData)) {
                $game->update($updateData);
                $this->line(" ✅ Обновлено: {$game->title} (совпадение {$bestScore}%, найдено как {$gameData['name']})");
            }

        } catch (\Exception $e) {
            $this->error("❌ Ошибка с игрой {$game->title}: " . $e->getMessage());
        }
    }
}
