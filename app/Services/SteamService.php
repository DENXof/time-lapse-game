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
            // Поиск через Store API
            $response = Http::get('https://store.steampowered.com/api/storesearch', [
                'term' => $gameTitle,
                'cc' => 'ru',
                'l' => 'russian'
            ]);

            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $item = $response['items'][0];
                return [
                    'app_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'] ?? null,
                    'url' => 'https://store.steampowered.com/app/' . $item['id']
                ];
            }

            return null;
        });
    }

    /**
     * Получить цены во всех валютах
     */
    public function getAllPrices($appId)
    {
        $cacheKey = 'steam_prices_' . $appId;

        return Cache::remember($cacheKey, 86400, function () use ($appId) {
            $currencies = ['ru' => 'RUB', 'us' => 'USD', 'eu' => 'EUR'];
            $prices = [];

            foreach ($currencies as $cc => $currency) {
                $response = Http::timeout(10)->get('https://store.steampowered.com/api/appdetails', [
                    'appids' => $appId,
                    'cc' => $cc
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data[$appId]['success']) && $data[$appId]['success'] === true) {
                        $price = $data[$appId]['data']['price_overview']['final_formatted'] ?? null;
                        if ($price) {
                            // Очищаем цену от символов валюты
                            $cleanedPrice = preg_replace('/[^0-9.,]/', '', $price);
                            $prices[$currency] = !empty($cleanedPrice) ? $cleanedPrice : $price;
                        }
                    }
                }
            }

            return $prices;
        });
    }
}
