<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SteamService
{
    /**
     * Получить цену игры из Steam (с поддержкой регионов)
     */
    public function getPrice($appId, $forceRegion = null)
    {
        // Очищаем app_id от лишних символов
        $appId = preg_replace('/[^0-9]/', '', $appId);

        if (!$appId) {
            return null;
        }

        // Пробуем разные регионы
        $regions = $forceRegion ? [$forceRegion] : ['us', 'eu', 'jp', 'kr', 'gb'];

        foreach ($regions as $cc) {
            $price = $this->fetchPrice($appId, $cc);
            if ($price) {
                // Конвертируем в рубли для наглядности
                $price = $this->convertToRubles($price);
                return $price;
            }
        }

        return null;
    }

    /**
     * Получить цену из Steam API для конкретного региона
     */
    private function fetchPrice($appId, $cc)
    {
        $cacheKey = "steam_price_{$appId}_{$cc}";

        return Cache::remember($cacheKey, 43200, function () use ($appId, $cc) {
            try {
                $response = Http::timeout(10)->get('https://store.steampowered.com/api/appdetails', [
                    'appids' => $appId,
                    'cc' => $cc
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data[$appId]['success']) && $data[$appId]['success'] === true) {
                        $priceData = $data[$appId]['data']['price_overview'] ?? null;
                        if ($priceData && isset($priceData['final_formatted'])) {
                            return $priceData['final_formatted'];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Steam API failed for {$appId} ({$cc}): " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Конвертация в рубли (примерная)
     */
    private function convertToRubles($price)
    {
        // Убираем символ валюты
        $value = preg_replace('/[^0-9.]/', '', $price);
        if (!$value) {
            return $price;
        }

        $value = (float) $value;

        // Курсы валют (можно обновлять из внешнего API)
        $rates = Cache::remember('currency_rates', 3600, function () {
            try {
                $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'USD' => $data['rates']['RUB'] ?? 90,
                        'EUR' => $data['rates']['RUB'] ?? 98,
                        'GBP' => $data['rates']['RUB'] ?? 110,
                        'JPY' => $data['rates']['RUB'] ?? 0.6,
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to fetch exchange rates');
            }

            // Дефолтные курсы
            return ['USD' => 90, 'EUR' => 98, 'GBP' => 110, 'JPY' => 0.6];
        });

        if (str_contains($price, '$')) {
            return round($value * $rates['USD']) . ' ₽';
        }
        if (str_contains($price, '€')) {
            return round($value * $rates['EUR']) . ' ₽';
        }
        if (str_contains($price, '£')) {
            return round($value * $rates['GBP']) . ' ₽';
        }
        if (str_contains($price, '¥')) {
            return round($value * $rates['JPY']) . ' ₽';
        }

        return $price;
    }

    /**
     * Поиск игры в Steam Store
     */
    public function searchGame($gameTitle)
    {
        $cacheKey = 'steam_search_' . md5($gameTitle);

        return Cache::remember($cacheKey, 86400, function () use ($gameTitle) {
            $response = Http::get('https://store.steampowered.com/api/storesearch', [
                'term' => $gameTitle,
                'cc' => 'us',
                'l' => 'english'
            ]);

            if ($response->successful() && !empty($response['items'])) {
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
}
