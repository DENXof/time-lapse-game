<?php

namespace App\Console\Commands;

use App\Services\GameImporterService;
use Illuminate\Console\Command;

class ImportGamesCommand extends Command
{
    protected $signature = 'games:import
                            {--source=steam : Источник (steam, igdb, popular)}
                            {--search= : Поисковый запрос}
                            {--genre= : Жанр для Steam}
                            {--year= : Год для импорта популярных игр}
                            {--limit=50 : Лимит игр}';

    protected $description = 'Импорт игр из внешних API (Steam, IGDB/Twitch)';

    public function handle(GameImporterService $importer)
    {
        $source = $this->option('source');
        $limit = $this->option('limit');

        $this->info("Начинаю импорт игр из {$source}...");

        try {
            switch ($source) {
                case 'steam':
                    $genre = $this->option('genre');
                    if (!$genre) {
                        $this->error('Укажите жанр: --genre=RPG');
                        return 1;
                    }
                    $count = $importer->importFromSteamByGenre($genre, $limit);
                    break;

                case 'igdb':
                    $search = $this->option('search');
                    if (!$search) {
                        $this->error('Укажите поисковый запрос: --search="The Witcher"');
                        return 1;
                    }
                    $count = $importer->importFromIGDB($search, $limit);
                    break;

                case 'popular':
                    $year = $this->option('year') ?? date('Y');
                    $count = $importer->importPopularGamesByYear($year, $limit);
                    break;

                default:
                    $this->error('Неизвестный источник. Используйте: steam, igdb, popular');
                    return 1;
            }

            $this->info("✅ Импортировано игр: {$count}");
            return 0;

        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            return 1;
        }
    }
}
