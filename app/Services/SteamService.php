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
}
