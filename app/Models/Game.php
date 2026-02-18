<?php
//Модель игр

namespace App\Models;

// Подключаем нужные классы
use Illuminate\Database\Eloquent\Factories\HasFactory;  // Для создания тестовых данных
use Illuminate\Database\Eloquent\Model;                 // Базовый класс модели
use Illuminate\Support\Str;                              // Для работы со строками (создание slug)

class Game extends Model
{
    // Подключаем трейт HasFactory
    use HasFactory;

    //РАЗРЕШЕННЫЕ ДЛЯ ЗАПОЛНЕНИЯ ПОЛЯ
    protected $fillable = [
        'title',              // Название игры
        'slug',               // URL-адрес (например "the-witcher-3")
        'release_year',       // Год выпуска
        'developer',          // Разработчик
        'publisher',          // Издатель
        'description',        // Полное описание
        'short_description',  // Краткое описание для превью
        'cover_image',        // Путь к файлу обложки
        'platform',           // Платформа (PC, PS5, Xbox и т.д.)
        'genre_id'            // ID жанра (к какому жанру относится)
    ];

    /**
     * ВИРТУАЛЬНЫЕ ПОЛЯ (APPENDS)
     *
     * Эти поля НЕ хранятся в базе данных, но доступны как обычные свойства
     * Laravel автоматически добавляет их при преобразовании модели в JSON/массив
     */
    protected $appends = ['era_style', 'decade'];

    /**
     * СОБЫТИЯ МОДЕЛИ (BOOT METHOD)
     *
     * Здесь можно задать действия, которые будут выполняться
     * при определенных событиях (создание, обновление, удаление)
     */
    protected static function boot()
    {
        parent::boot(); // Вызываем родительский метод (обязательно)

        /**
         * СОБЫТИЕ: ПЕРЕД СОЗДАНИЕМ ЗАПИСИ
         *
         * Срабатывает автоматически, когда создается новая игра
         * Используется для создания slug из названия, если slug не указан
         */
        static::creating(function ($game) {
            // Если slug пустой (не заполнен в форме)
            if (empty($game->slug)) {
                // Создаем slug из названия
                // Например: "The Witcher 3: Wild Hunt" -> "the-witcher-3-wild-hunt"
                $game->slug = Str::slug($game->title);
            }
        });
    }

    // ============================================
    // СВЯЗИ С ДРУГИМИ МОДЕЛЯМИ
    // ============================================

    /**
     * СВЯЗЬ С ЖАНРОМ (ОДИН-КО-МНОГИМ)
     *
     * Игра принадлежит одному жанру
     * В таблице games есть поле genre_id, которое ссылается на id в таблице genres
     *
     * Как использовать: $game->genre->name (название жанра игры)
     */
    public function genre()
    {
        // belongsTo - "принадлежит одному"
        return $this->belongsTo(Genre::class);
    }

    /**
     * СВЯЗЬ С ЭПОХАМИ (МНОГИЕ-КО-МНОГИМ)
     *
     * Одна игра может относиться к нескольким историческим эпохам
     * Использует промежуточную таблицу 'game_era'
     *
     * Как использовать: $game->eras (коллекция эпох, к которым относится игра)
     */
    public function eras()
    {
        // belongsToMany - связь многие-ко-многим
        return $this->belongsToMany(Era::class, 'game_era')
            // withPivot - добавляет дополнительное поле 'significance' из сводной таблицы
            // significance - значимость игры для эпохи
            ->withPivot('significance')
            // withTimestamps - автоматически заполняет created_at и updated_at в сводной таблице
            ->withTimestamps();
    }

    // ============================================
    // ВИРТУАЛЬНЫЕ АТРИБУТЫ (GETTERS)
    // ============================================

    /**
     * ВИРТУАЛЬНЫЙ АТРИБУТ: СТИЛЬ ЭПОХИ
     *
     * Определяет стиль оформления игры по году выпуска
     * Используется для визуальной стилизации карточек игр
     *
     * Доступен как: $game->era_style
     *
     * @return string
     */
    public function getEraStyleAttribute()
    {
        // Если игра вышла до 1985 года
        if ($this->release_year < 1985)
            return 'pixel';      // Пиксельная графика (как старые аркады)

        // Если игра вышла с 1985 по 1994 год
        if ($this->release_year < 1995)
            return '16bit';      // 16-битная эра (SNES, Sega Genesis)

        // Если игра вышла с 1995 по 2004 год
        if ($this->release_year < 2005)
            return '3d-early';   // Ранняя 3D (PS1, N64, первые 3D игры)

        // Если игра вышла с 2005 года и позже
        return 'modern';          // Современные игры
    }

    /**
     * ВИРТУАЛЬНЫЙ АТРИБУТ: ДЕСЯТИЛЕТИЕ
     *
     * Определяет десятилетие, к которому относится игра
     * Например: 1985 -> 1980, 1993 -> 1990
     *
     * Доступен как: $game->decade
     *
     * @return int
     */
    public function getDecadeAttribute()
    {
        // floor - округление вниз
        // 1985 / 10 = 198.5 -> floor = 198 -> * 10 = 1980
        return floor($this->release_year / 10) * 10;
    }

    // ============================================
    // ДОПОЛНИТЕЛЬНЫЕ МЕТОДЫ
    // ============================================

    /**
     * УВЕЛИЧИТЬ СЧЕТЧИК ПРОСМОТРОВ
     *
     * Вызывается, когда пользователь открывает страницу игры
     * Увеличивает значение в поле views_count на 1
     */
    public function incrementViews()
    {
        // increment('views_count') - добавляет 1 к указанному полю в БД
        $this->increment('views_count');
    }
}
