<?php

namespace App\Console\Commands;

use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportGenresCommand extends Command
{
    protected $signature = 'genres:import {--source=steam : Источник жанров (steam, igdb)}';
    protected $description = 'Импорт жанров из внешних API';

    public function handle()
    {
        $source = $this->option('source');

        if ($source === 'steam') {
            $this->importFromSteam();
        } elseif ($source === 'igdb') {
            $this->importFromIGDB();
        } else {
            $this->error('Неизвестный источник. Используйте: steam, igdb');
            return 1;
        }

        return 0;
    }

    protected function importFromSteam()
    {
        $this->info('Импорт жанров из Steam...');

        // Список жанров Steam (основные)
        $steamGenres = [
            'Action' => ['color' => '#e74c3c', 'icon' => '⚔️'],
            'Adventure' => ['color' => '#e67e22', 'icon' => '🗺️'],
            'RPG' => ['color' => '#9b59b6', 'icon' => '🐉'],
            'Strategy' => ['color' => '#3498db', 'icon' => '♟️'],
            'Simulation' => ['color' => '#1abc9c', 'icon' => '🎮'],
            'Sports' => ['color' => '#2ecc71', 'icon' => '⚽'],
            'Indie' => ['color' => '#f39c12', 'icon' => '🎨'],
            'Casual' => ['color' => '#1abc9c', 'icon' => '🎲'],
            'MMO' => ['color' => '#e74c3c', 'icon' => '🌍'],
            'Racing' => ['color' => '#3498db', 'icon' => '🏎️'],
            'Fighting' => ['color' => '#e74c3c', 'icon' => '🥊'],
            'Puzzle' => ['color' => '#f1c40f', 'icon' => '🧩'],
            'Platformer' => ['color' => '#e67e22', 'icon' => '🎮'],
            'Shooter' => ['color' => '#c0392b', 'icon' => '🔫'],
            'Survival' => ['color' => '#2c3e50', 'icon' => '🏕️'],
            'Horror' => ['color' => '#8e44ad', 'icon' => '👻'],
            'Open World' => ['color' => '#27ae60', 'icon' => '🌎'],
            'Sandbox' => ['color' => '#f39c12', 'icon' => '🏖️'],
            'Building' => ['color' => '#d35400', 'icon' => '🏗️'],
            'Management' => ['color' => '#16a085', 'icon' => '📊'],
            'Educational' => ['color' => '#3498db', 'icon' => '📚'],
            'Arcade' => ['color' => '#f1c40f', 'icon' => '🕹️'],
            'Visual Novel' => ['color' => '#9b59b6', 'icon' => '📖'],
            'Card Game' => ['color' => '#e74c3c', 'icon' => '🃏'],
            'Battle Royale' => ['color' => '#c0392b', 'icon' => '👑'],
            'MOBA' => ['color' => '#2980b9', 'icon' => '🎯'],
            'Rhythm' => ['color' => '#e67e22', 'icon' => '🎵'],
            'Fitness' => ['color' => '#2ecc71', 'icon' => '💪'],
        ];

        $imported = 0;
        $skipped = 0;

        foreach ($steamGenres as $name => $data) {
            $slug = Str::slug($name);
            $originalSlug = $slug;
            $counter = 1;

            while (Genre::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            if (Genre::where('name', $name)->exists()) {
                $this->line("⏭️ Жанр '{$name}' уже существует");
                $skipped++;
                continue;
            }

            Genre::create([
                'name' => $name,
                'slug' => $slug,
                'color' => $data['color'],
                'icon' => $data['icon'],
            ]);

            $this->line("✅ Добавлен жанр: {$name} {$data['icon']}");
            $imported++;
        }

        $this->newLine();
        $this->info("📊 Итог: добавлено {$imported} жанров, пропущено {$skipped}");
    }

    protected function importFromIGDB()
    {
        $this->info('Импорт жанров из IGDB...');

        // Получаем токен Twitch
        $twitchClientId = config('services.twitch.client_id');
        $twitchClientSecret = config('services.twitch.client_secret');

        if (!$twitchClientId || !$twitchClientSecret) {
            $this->error('Twitch API ключи не настроены!');
            return;
        }

        $tokenResponse = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
            'client_id' => $twitchClientId,
            'client_secret' => $twitchClientSecret,
            'grant_type' => 'client_credentials',
        ]);

        if (!$tokenResponse->successful()) {
            $this->error('Не удалось получить токен Twitch');
            return;
        }

        $token = $tokenResponse->json()['access_token'];

        // Получаем жанры из IGDB
        $response = Http::withHeaders([
            'Client-ID' => $twitchClientId,
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://api.igdb.com/v4/genres', [
            'fields' => 'name',
            'limit' => 500,
        ]);

        if (!$response->successful()) {
            $this->error('Ошибка получения жанров из IGDB');
            return;
        }

        $genres = $response->json();
        $imported = 0;

        $colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#c0392b'];

        foreach ($genres as $genreData) {
            $name = $genreData['name'];

            if (Genre::where('name', $name)->exists()) {
                continue;
            }

            $slug = Str::slug($name);
            $originalSlug = $slug;
            $counter = 1;

            while (Genre::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Genre::create([
                'name' => $name,
                'slug' => $slug,
                'color' => $colors[array_rand($colors)],
                'icon' => '🎮',
            ]);

            $this->line("✅ Добавлен жанр: {$name}");
            $imported++;
        }

        $this->newLine();
        $this->info("📊 Добавлено жанров из IGDB: {$imported}");
    }
}
