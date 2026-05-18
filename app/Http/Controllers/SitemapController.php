<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\User;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        // Главная страница
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Страница "Игры"
        $sitemap->add(Url::create('/games')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        // Страница "Таймлайн"
        $sitemap->add(Url::create('/timeline')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        // Топ-100
        $sitemap->add(Url::create('/top')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        // Новинки
        $sitemap->add(Url::create('/new-releases')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8));

        // Календарь
        $sitemap->add(Url::create('/calendar')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.7));

        // Все игры (динамически)
        $games = Game::all();
        foreach ($games as $game) {
            $sitemap->add(Url::create('/games/' . $game->slug)
                ->setLastModificationDate($game->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7));
        }

        // Все жанры
        $genres = Genre::all();
        foreach ($genres as $genre) {
            $sitemap->add(Url::create('/games?genre=' . $genre->id)
                ->setLastModificationDate($genre->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.6));
        }

        return response($sitemap->render(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
