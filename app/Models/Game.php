<?php
namespace App\Models;
// Определение пространства имён для модели

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Импорт трейта для фабричного создания моделей

use Illuminate\Database\Eloquent\Model;
// Импорт базового класса Eloquent Model

use Illuminate\Support\Str;
// Импорт класса для работы со строками

class Game extends Model
    // Объявление класса Game, наследующего Eloquent Model
{
    use HasFactory;
    // Подключение трейта для создания тестовых данных

    protected $fillable = [
        // Массив полей, доступных для массового заполнения
        'title',              // Название игры
        'slug',               // URL-идентификатор
        'release_year',       // Год выпуска
        'developer',          // Разработчик
        'publisher',          // Издатель
        'description',        // Полное описание
        'short_description',  // Краткое описание
        'cover_image',        // Путь к файлу обложки
        'platform',           // Платформа
        'genre_id'            // Внешний ключ для связи с жанром
    ];

    protected $appends = ['era_style', 'decade'];
    // Массив виртуальных атрибутов, добавляемых к модели

    protected static function boot()
    // Переопределение метода boot для добавления событий модели
    {
        parent::boot();
        // Вызов родительского метода boot

        static::creating(function ($game) {
            // Регистрация обработчика события creating
            // Выполняется перед сохранением новой записи
            if (empty($game->slug)) {
                // Проверка отсутствия значения в поле slug
                $game->slug = Str::slug($game->title);
                // Генерация slug из названия игры
            }
        });
    }

    public function genre()
    // Определение отношения принадлежности к жанру
    {
        return $this->belongsTo(Genre::class);
        // Возвращает отношение "многие к одному" с моделью Genre
    }

    public function getEraStyleAttribute()
    // Аксессор для виртуального атрибута era_style
    {
        return match (true) {
            // Использование match для определения стиля по году
            $this->release_year < 1985 => 'pixel',
            // Возвращает 'pixel' для игр до 1985 года
            $this->release_year < 1995 => '16bit',
            // Возвращает '16bit' для игр 1985-1994
            $this->release_year < 2005 => '3d-early',
            // Возвращает '3d-early' для игр 1995-2004
            default => 'modern'
        // Возвращает 'modern' для всех остальных
        };
    }

    public function getDecadeAttribute()
    // Аксессор для виртуального атрибута decade
    {
        return floor($this->release_year / 10) * 10;
        // Вычисление десятилетия на основе года выпуска
    }

    public function incrementViews()
    // Метод для увеличения счётчика просмотров
    {
        $this->increment('views_count');
        // Инкрементирует поле views_count в базе данных
    }
}
