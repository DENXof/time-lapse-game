<?php
// МИГРАЦИЯ СОЗДАНИЯ ИГР
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    public function up(): void
    {
        // СОЗДАЕМ ТАБЛИЦУ 'games' (игры)
        Schema::create('games', function (Blueprint $table) {

            // ------------------------------------------------------------
            // ОСНОВНАЯ ИНФОРМАЦИЯ ОБ ИГРЕ
            // ------------------------------------------------------------

            // id игры (первичный ключ, авто-инкремент)
            $table->id();

            // Название игры
            $table->string('title');

            // URL-адрес игры
            // unique() - гарантирует, что не будет двух игр с одинаковым URL
            $table->string('slug')->unique();

            // Год выпуска
            $table->integer('release_year');

            // Разработчик
            $table->string('developer');

            // Издатель
            // nullable() - может быть пустым
            $table->string('publisher')->nullable();

            // Полное описание игры
            $table->text('description');

            // Краткое описание (для превью в карточке)
            $table->text('short_description')->nullable();

            // Добавляем новое поле 'platform'
            $table->string('platform')->nullable()->after('short_description');

            // Путь к файлу с обложкой игры (где лежит картинка)
            $table->string('cover_image')->nullable();

            // ------------------------------------------------------------
            // СВЯЗИ С ДРУГИМИ ТАБЛИЦАМИ
            // ------------------------------------------------------------

            // Внешний ключ к таблице genres (жанры)
            // foreignId('genre_id') - создает поле genre_id
            // constrained() - связывает с таблицей genres (по умолчанию)
            // onDelete('cascade') - если удалить жанр, удалятся и все его игры
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            // ------------------------------------------------------------
            // ИНДЕКСЫ (для быстрого поиска)
            // ------------------------------------------------------------

            // Индекс для поля release_year - ускоряет поиск по году
            $table->index('release_year');

            // Индекс для поля genre_id - ускоряет поиск по жанру
            $table->index('genre_id');
        });
    }

    public function down(): void
    {
        // Удаляем таблицу 'games', если она существует
        Schema::dropIfExists('games');
    }
};
