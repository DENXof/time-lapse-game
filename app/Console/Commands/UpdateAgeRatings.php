<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;

class UpdateAgeRatings extends Command
{
    protected $signature = 'games:update-age-ratings';
    protected $description = 'Обновить возрастной рейтинг для всех игр';

    public function handle()
    {
        $games = Game::all();
        $updated = 0;

        foreach ($games as $game) {
            $oldRating = $game->age_rating;
            $newRating = $this->detectAgeRating($game->title, $game->description);

            if ($oldRating !== $newRating) {
                $game->age_rating = $newRating;
                $game->save();
                $this->line("Обновлён: {$game->title} — {$oldRating} → {$newRating}");
                $updated++;
            }
        }

        $this->info("Обновлено игр: {$updated}");
    }

    private function detectAgeRating($title, $description)
    {
        $text = strtolower($title . ' ' . $description);

        $keywords18 = [
            'sex',
            'sexual',
            'nude',
            'naked',
            'porn',
            'erotic',
            'hentai',
            'mature',
            'adult',
            'nsfw',
            'gore',
            'bloody',
            'violence',
            'murder',
            'kill',
            'corpse',
            'torture',
            'rape',
            'drug',
            'alcohol',
            'horror',
            'scary',
            'blood'
        ];

        $keywords16 = [
            'war',
            'battle',
            'fight',
            'weapon',
            'gun',
            'shoot',
            'death',
            'dead',
            'dark',
            'evil',
            'demon',
            'hell',
            'violent'
        ];

        $keywords12 = [
            'fantasy',
            'magic',
            'adventure',
            'quest',
            'monster',
            'dragon',
            'sword',
            'spell',
            'wizard',
            'hero'
        ];

        foreach ($keywords18 as $keyword) {
            if (str_contains($text, $keyword))
                return '18+';
        }

        foreach ($keywords16 as $keyword) {
            if (str_contains($text, $keyword))
                return '16+';
        }

        foreach ($keywords12 as $keyword) {
            if (str_contains($text, $keyword))
                return '12+';
        }

        return '0+';
    }
}
