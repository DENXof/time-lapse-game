<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Era;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [  // Изменили 'home' на 'home.index'
            'featuredGames' => Game::with('genre')->latest()->take(3)->get(),
            'popularGenres' => Genre::withCount('games')->orderBy('games_count', 'desc')->take(3)->get(),
            'eras' => Era::all(),
            'stats' => [
                'total_games' => Game::count(),
                'total_genres' => Genre::count(),
                'total_eras' => Era::count(),
            ],
            'message' => Game::count() . ' игр доступно для просмотра!'
        ]);
    }

    public function timeline()
    {
        $eras = [
            [
                'id' => 1,
                'name' => 'Эра Протогейминга и Мейнфреймов',
                'years' => '1950-е — начало 1970-х',
                'description' => 'Не игры как продукт, а научные эксперименты и демонстрации возможностей вычислительной техники.',
                'platforms' => ['EDSAC', 'PDP-1', 'IBM 701'],
                'key_games' => ['OXO / Noughts and Crosses (1952)', 'Tennis for Two (1958)', 'Spacewar! (1962)'],
                'color' => 'bg-primary',
                'icon' => 'fas fa-microchip',
                'end_reason' => 'Появление первого коммерческого аркадного автомата Computer Space (1971)'
            ],
            [
                'id' => 2,
                'name' => 'Эра 8-битных Домашних Компьютеров',
                'years' => 'конец 1970-х — середина 1980-х',
                'description' => 'PC — это многофункциональные машины для энтузиастов. Игры — одно из главных, но не единственное их применение.',
                'platforms' => ['Apple II', 'ZX Spectrum', 'Commodore 64', 'IBM PC (5150 с CGA)', 'Atari 8-bit'],
                'key_games' => ['Zork (1980)', 'Ultima I (1981)', 'Elite (1984)', 'King\'s Quest (1984)'],
                'color' => 'bg-success',
                'icon' => 'fas fa-desktop',
                'end_reason' => 'Приход более мощных 16-битных компьютеров'
            ],
            [
                'id' => 3,
                'name' => 'Золотой Век MS-DOS и Расцвет Жанров',
                'years' => 'вторая половина 1980-х — середина 1990-х',
                'description' => 'PC становится доминирующей игровой платформой для сложных, "умных" игр. Расцветают стратегии, квесты, сложные RPG.',
                'platforms' => ['IBM PC с DOS', 'VGA/Sound Blaster'],
                'key_games' => ['Monkey Island (1990)', 'Civilization (1991)', 'Ultima VII (1992)', 'Doom (1993)', 'X-COM (1994)'],
                'color' => 'bg-warning',
                'icon' => 'fas fa-chess',
                'end_reason' => 'Появление Windows 95 и аппаратных 3D-ускорителей'
            ],
            [
                'id' => 4,
                'name' => 'Эра 3D-Ускорителей и Онлайн-Мультиплеера',
                'years' => 'вторая половина 1990-х — середина 2000-х',
                'description' => 'Технологический прорыв. Переход к настоящей полигональной 3D-графике. Рождение массового онлайн-гейминга.',
                'platforms' => ['Windows 95/98/XP', '3dfx Voodoo', 'NVIDIA GeForce', 'ATI Radeon'],
                'key_games' => ['Quake (1996)', 'StarCraft (1998)', 'Half-Life (1998)', 'Counter-Strike (1999)', 'World of Warcraft (2004)'],
                'color' => 'bg-danger',
                'icon' => 'fas fa-cube',
                'end_reason' => 'Кризис стоимости разработки AAA-игр'
            ],
            [
                'id' => 5,
                'name' => 'Эра Цифровой Дистрибуции и Инди-Ренессанса',
                'years' => 'конец 2000-х — середина 2010-х',
                'description' => 'Демократизация разработки и распространения. Инди-разработчики находят свою нишу через цифровые магазины.',
                'platforms' => ['Windows 7/10', 'Steam', 'GOG'],
                'key_games' => ['Minecraft (2009)', 'The Witcher 3 (2015)', 'Undertale (2015)', 'Dota 2 (2013)'],
                'color' => 'bg-info',
                'icon' => 'fas fa-download',
                'end_reason' => 'Начало войны эксклюзивов в цифровых магазинах'
            ],
            [
                'id' => 6,
                'name' => 'Современная Эра: Сервисы, Подписки и Технологический Потолок',
                'years' => 'вторая половина 2010-х — настоящее время',
                'description' => 'Игры — услуга (Games as a Service). Доминирование экосистем (Steam, Epic, Xbox/PC Game Pass).',
                'platforms' => ['Windows 10/11', 'Steam', 'Epic Store', 'Xbox Game Pass'],
                'key_games' => ['Fortnite (2017)', 'Red Dead Redemption 2 (2019)', 'Baldur\'s Gate 3 (2023)', 'Alan Wake 2 (2023)'],
                'color' => 'bg-dark',
                'icon' => 'fas fa-cloud',
                'end_reason' => 'Интеграция ИИ, VR/AR, конвергенция платформ'
            ]
        ];

        return view('timeline', compact('eras'));
    }
}
