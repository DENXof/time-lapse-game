<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SteamService
{
    /**
     * Поиск Steam App ID по названию игры
     */
    public function searchAppId($gameTitle)
    {
        $cacheKey = 'steam_search_' . md5($gameTitle);

        return Cache::remember($cacheKey, 86400, function () use ($gameTitle) {
            $response = Http::get('https://store.steampowered.com/api/storesearch', [
                'term' => $gameTitle,
                'cc' => 'ru',
                'l' => 'russian'
            ]);

            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                return [
                    'app_id' => $response['items'][0]['id'],
                    'name' => $response['items'][0]['name'],
                    'url' => 'https://store.steampowered.com/app/' . $response['items'][0]['id']
                ];
            }

            return null;
        });
    }

    /**
     * Получить детальную информацию об игре из Steam по App ID
     */
    public function getSteamGameDetails($appId)
    {
        $cacheKey = 'steam_game_details_' . $appId;

        return Cache::remember($cacheKey, 86400, function () use ($appId) {
            $response = Http::get('https://store.steampowered.com/api/appdetails', [
                'appids' => $appId,
                'cc' => 'ru',
                'l' => 'russian'
            ]);

            if ($response->successful() && isset($response[$appId]['data'])) {
                $data = $response[$appId]['data'];

                // Получаем год выпуска
                $releaseDate = $data['release_date']['date'] ?? '';
                $releaseYear = date('Y');

                if (preg_match('/\d{4}/', $releaseDate, $matches)) {
                    $releaseYear = (int) $matches[0];
                }

                if ($releaseYear < 1980 || $releaseYear > date('Y') + 5) {
                    $releaseYear = date('Y');
                }

                return [
                    'release_year' => $releaseYear,
                    'developer' => $data['developers'][0] ?? 'Unknown',
                    'publisher' => $data['publishers'][0] ?? 'Unknown',
                    'description' => $data['short_description'] ?? 'Описание отсутствует',
                ];
            }

            return null;
        });
    }
}
