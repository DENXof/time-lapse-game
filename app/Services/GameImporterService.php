<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameImporterService
{
    protected $steamApiKey;
    protected $twitchClientId;
    protected $twitchClientSecret;
    protected $twitchAccessToken;

    public function __construct()
    {
        $this->steamApiKey = config('services.steam.api_key');
        $this->twitchClientId = config('services.twitch.client_id');
        $this->twitchClientSecret = config('services.twitch.client_secret');
    }

    /**
     * Получить токен доступа Twitch
     */
    protected function getTwitchAccessToken()
    {
        if ($this->twitchAccessToken) {
            return $this->twitchAccessToken;
        }

        if (!$this->twitchClientId || !$this->twitchClientSecret) {
            Log::warning('Twitch credentials not configured');
            return null;
        }

        try {
            $response = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
                'client_id' => $this->twitchClientId,
                'client_secret' => $this->twitchClientSecret,
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->twitchAccessToken = $data['access_token'];
                return $this->twitchAccessToken;
            }

            Log::error('Failed to get Twitch access token: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Twitch token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Импорт популярных игр из Steam (по жанрам)
     */
    public function importFromSteamByGenre($genreName, $limit = 50)
    {
        $genre = Genre::where('name', $genreName)->first();

        if (!$genre) {
            $slug = Str::slug($genreName);
            $originalSlug = $slug;
            $counter = 1;

            while (Genre::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $genre = Genre::create([
                'name' => $genreName,
                'slug' => $slug,
                'color' => '#3498db',
                'icon' => '🎮'
            ]);
        }

        $response = Http::get('https://store.steampowered.com/api/storesearch', [
            'term' => $genreName,
            'cc' => 'ru',
            'l' => 'russian',
            'num' => $limit
        ]);

        if (!$response->successful()) {
            Log::error('Steam API error: ' . $response->body());
            return 0;
        }

        $items = $response['items'] ?? [];
        $imported = 0;

        foreach ($items as $item) {
            if (Game::where('steam_app_id', $item['id'])->exists()) {
                continue;
            }

            $details = $this->getSteamGameDetails($item['id']);
            if (!$details) {
                continue;
            }

            $coverImage = null;
            try {
                $coverImage = $this->downloadSteamImage($item['id']);
            } catch (\Exception $e) {
                Log::warning('Failed to download image for ' . $item['name'] . ': ' . $e->getMessage());
            }

            Game::create([
                'title' => $item['name'],
                'slug' => Str::slug($item['name']),
                'release_year' => $details['release_year'],
                'developer' => $details['developer'] ?? 'Unknown',
                'publisher' => $details['publisher'] ?? 'Unknown',
                'description' => $details['description'] ?? 'Описание отсутствует',
                'platform' => 'PC',
                'genre_id' => $genre->id,
                'steam_app_id' => $item['id'],
                'cover_image' => $coverImage,
                'views_count' => 0,
                'rating_avg' => 0,
                'rating_count' => 0,
            ]);

            $imported++;
        }

        return $imported;
    }

    /**
     * Получить детали игры из Steam
     */
    protected function getSteamGameDetails($appId)
    {
        $response = Http::get('https://store.steampowered.com/api/appdetails', [
            'appids' => $appId,
            'cc' => 'ru',
            'l' => 'russian'
        ]);

        if ($response->successful() && isset($response[$appId]['data'])) {
            $data = $response[$appId]['data'];

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
    }

    /**
     * Скачать обложку из Steam
     */
    protected function downloadSteamImage($appId)
    {
        try {
            $url = "https://cdn.cloudflare.steamstatic.com/steam/apps/{$appId}/header.jpg";
            $contents = file_get_contents($url);
            $filename = "steam_{$appId}.jpg";
            $path = "covers/{$filename}";
            Storage::disk('public')->put($path, $contents);
            return $path;
        } catch (\Exception $e) {
            Log::warning('Failed to download Steam image for app ' . $appId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Импорт игр из IGDB (Twitch)
     */
    public function importFromIGDB($search, $limit = 50)
    {
        $token = $this->getTwitchAccessToken();
        if (!$token) {
            Log::error('Не удалось получить токен Twitch');
            return 0;
        }

        $response = Http::withHeaders([
            'Client-ID' => $this->twitchClientId,
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://api.igdb.com/v4/games', [
            'search' => $search,
            'fields' => 'name,first_release_date,summary,genres.name,cover.image_id,platforms.name,involved_companies.company.name,rating',
            'limit' => $limit,
            'where' => 'version_parent = null & category = 0'
        ]);

        if (!$response->successful()) {
            Log::error('IGDB API error: ' . $response->body());
            return 0;
        }

        $games = $response->json();

        if (!is_array($games) || empty($games)) {
            Log::warning('No games found for search: ' . $search);
            return 0;
        }

        $imported = 0;

        foreach ($games as $gameData) {
            if (!isset($gameData['name'])) {
                continue;
            }

            $title = $gameData['name'];

            if (Game::where('title', $title)->exists()) {
                continue;
            }

            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;

            while (Game::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $genreName = 'Other';
            if (isset($gameData['genres']) && is_array($gameData['genres']) && count($gameData['genres']) > 0) {
                if (is_array($gameData['genres'][0]) && isset($gameData['genres'][0]['name'])) {
                    $genreName = $gameData['genres'][0]['name'];
                } elseif (isset($gameData['genres'][0])) {
                    $genreId = $gameData['genres'][0];
                    $genreResponse = Http::withHeaders([
                        'Client-ID' => $this->twitchClientId,
                        'Authorization' => 'Bearer ' . $token,
                    ])->get('https://api.igdb.com/v4/genres', [
                        'fields' => 'name',
                        'where' => 'id = ' . $genreId
                    ]);
                    if ($genreResponse->successful() && isset($genreResponse->json()[0]['name'])) {
                        $genreName = $genreResponse->json()[0]['name'];
                    }
                }
            }

            $genre = Genre::firstOrCreate(
                ['name' => $genreName],
                ['name' => $genreName, 'color' => '#3498db', 'icon' => '🎮', 'slug' => Str::slug($genreName)]
            );

            $releaseYear = date('Y');
            if (isset($gameData['first_release_date'])) {
                $releaseYear = date('Y', $gameData['first_release_date']);
            }

            $developer = 'Unknown';
            if (isset($gameData['involved_companies']) && is_array($gameData['involved_companies']) && count($gameData['involved_companies']) > 0) {
                if (is_array($gameData['involved_companies'][0]) && isset($gameData['involved_companies'][0]['company']['name'])) {
                    $developer = $gameData['involved_companies'][0]['company']['name'];
                }
            }

            $platforms = ['PC'];
            if (isset($gameData['platforms']) && is_array($gameData['platforms'])) {
                $platforms = [];
                foreach ($gameData['platforms'] as $platform) {
                    if (is_array($platform) && isset($platform['name'])) {
                        $platforms[] = $platform['name'];
                    } elseif (is_numeric($platform)) {
                        $platformResponse = Http::withHeaders([
                            'Client-ID' => $this->twitchClientId,
                            'Authorization' => 'Bearer ' . $token,
                        ])->get('https://api.igdb.com/v4/platforms', [
                            'fields' => 'name',
                            'where' => 'id = ' . $platform
                        ]);
                        if ($platformResponse->successful() && isset($platformResponse->json()[0]['name'])) {
                            $platforms[] = $platformResponse->json()[0]['name'];
                        }
                    }
                }
                if (empty($platforms)) {
                    $platforms = ['PC'];
                }
            }
            $platform = implode(', ', $platforms);

            $coverImage = null;
            if (isset($gameData['cover']['image_id'])) {
                try {
                    $coverImage = $this->downloadIGDBImage($gameData['cover']['image_id']);
                } catch (\Exception $e) {
                    Log::warning('Failed to download IGDB image: ' . $e->getMessage());
                }
            }

            Game::create([
                'title' => $title,
                'slug' => $slug,
                'release_year' => $releaseYear,
                'developer' => $developer,
                'publisher' => $developer,
                'description' => $gameData['summary'] ?? 'Описание отсутствует',
                'platform' => $platform,
                'genre_id' => $genre->id,
                'cover_image' => $coverImage,
                'views_count' => 0,
                'rating_avg' => $gameData['rating'] ?? 0,
                'rating_count' => 0,
            ]);

            $imported++;
        }

        return $imported;
    }

    /**
     * Скачать обложку из IGDB
     */
    protected function downloadIGDBImage($imageId)
    {
        if (!$imageId) {
            return null;
        }

        try {
            $url = "https://images.igdb.com/igdb/image/upload/t_cover_big/{$imageId}.jpg";
            $contents = file_get_contents($url);
            $filename = "igdb_{$imageId}.jpg";
            $path = "covers/{$filename}";
            Storage::disk('public')->put($path, $contents);
            return $path;
        } catch (\Exception $e) {
            Log::warning('Failed to download IGDB image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Массовый импорт популярных игр (по годам) - ИСПРАВЛЕНО
     */
    public function importPopularGamesByYear($year, $limit = 50)
    {
        $token = $this->getTwitchAccessToken();
        if (!$token) {
            Log::error('Не удалось получить токен Twitch');
            return 0;
        }

        $fromTimestamp = strtotime("{$year}-01-01");
        $toTimestamp = strtotime("{$year}-12-31");

        $response = Http::withHeaders([
            'Client-ID' => $this->twitchClientId,
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://api.igdb.com/v4/games', [
            'fields' => 'name,first_release_date,summary,genres.name,cover.image_id,platforms.name,involved_companies.company.name,rating',
            'limit' => $limit,
            'where' => "first_release_date >= {$fromTimestamp} & first_release_date <= {$toTimestamp} & category = 0",
            'sort' => 'rating_desc'
        ]);

        if (!$response->successful()) {
            Log::error('IGDB API error: ' . $response->body());
            return 0;
        }

        $games = $response->json();

        if (!is_array($games) || empty($games)) {
            return 0;
        }

        $imported = 0;

        foreach ($games as $gameData) {
            // Пропускаем игры без названия
            if (!isset($gameData['name'])) {
                continue;
            }

            // Пропускаем игры без даты релиза (ИСПРАВЛЕНИЕ ОШИБКИ)
            if (!isset($gameData['first_release_date'])) {
                continue;
            }

            $title = $gameData['name'];

            if (Game::where('title', $title)->exists()) {
                continue;
            }

            // Генерируем уникальный slug
            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;

            while (Game::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $genreName = 'Other';
            if (isset($gameData['genres']) && is_array($gameData['genres']) && count($gameData['genres']) > 0) {
                if (is_array($gameData['genres'][0]) && isset($gameData['genres'][0]['name'])) {
                    $genreName = $gameData['genres'][0]['name'];
                }
            }

            $genre = Genre::firstOrCreate(
                ['name' => $genreName],
                ['name' => $genreName, 'color' => '#3498db', 'icon' => '🎮', 'slug' => Str::slug($genreName)]
            );

            $releaseYear = date('Y', $gameData['first_release_date']);

            $developer = 'Unknown';
            if (isset($gameData['involved_companies']) && is_array($gameData['involved_companies']) && count($gameData['involved_companies']) > 0) {
                if (is_array($gameData['involved_companies'][0]) && isset($gameData['involved_companies'][0]['company']['name'])) {
                    $developer = $gameData['involved_companies'][0]['company']['name'];
                }
            }

            $platforms = ['PC'];
            if (isset($gameData['platforms']) && is_array($gameData['platforms'])) {
                $platforms = [];
                foreach ($gameData['platforms'] as $platform) {
                    if (is_array($platform) && isset($platform['name'])) {
                        $platforms[] = $platform['name'];
                    }
                }
                if (empty($platforms)) {
                    $platforms = ['PC'];
                }
            }
            $platform = implode(', ', $platforms);

            $coverImage = null;
            if (isset($gameData['cover']['image_id'])) {
                try {
                    $coverImage = $this->downloadIGDBImage($gameData['cover']['image_id']);
                } catch (\Exception $e) {
                    Log::warning('Failed to download IGDB image: ' . $e->getMessage());
                }
            }

            Game::create([
                'title' => $title,
                'slug' => $slug,
                'release_year' => $releaseYear,
                'developer' => $developer,
                'publisher' => $developer,
                'description' => $gameData['summary'] ?? 'Описание отсутствует',
                'platform' => $platform,
                'genre_id' => $genre->id,
                'cover_image' => $coverImage,
                'views_count' => 0,
                'rating_avg' => $gameData['rating'] ?? 0,
                'rating_count' => 0,
            ]);

            $imported++;
        }

        return $imported;
    }
}
