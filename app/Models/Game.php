<?php
namespace App\Models;   // Определение пространства имён для модели

use Illuminate\Database\Eloquent\Factories\HasFactory;  // Импорт трейта для фабричного создания моделей
use Illuminate\Database\Eloquent\Model; // Импорт базового класса Eloquent Model
use Illuminate\Support\Str; // Импорт класса для работы со строками

class Game extends Model    // Объявление класса Game, наследующего Eloquent Model
{
    use HasFactory; // Подключение трейта для создания тестовых данных

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

    protected $appends = ['era_style', 'decade'];   // Массив виртуальных атрибутов, добавляемых к модели

    protected static function boot()    // Переопределение метода boot для добавления событий модели
    {
        parent::boot(); // Вызов родительского метода boot

        static::creating(function ($game) { // Регистрация обработчика события creating
            // Выполняется перед сохранением новой записи
            if (empty($game->slug)) {   // Проверка отсутствия значения в поле slug
                $game->slug = Str::slug($game->title);  // Генерация slug из названия игры
            }
        });
    }

    // ============================================
    // СВЯЗИ С ДРУГИМИ ТАБЛИЦАМИ
    // ============================================

    /**
     * Связь "многие к одному" с моделью Genre
     * Игра принадлежит одному жанру
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Связь "один ко многим" с таблицей оценок
     * У игры может быть много оценок
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Связь "многие ко многим" с таблицей избранного
     * Позволяет получить всех пользователей, которые добавили эту игру в избранное
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // ============================================
    // АКСЕССОРЫ (ВИРТУАЛЬНЫЕ АТРИБУТЫ)
    // ============================================

    public function getEraStyleAttribute()  // Аксессор для виртуального атрибута era_style
    {
        return match (true) {   // Использование match для определения стиля по году
            $this->release_year < 1985 => 'pixel',  // Возвращает 'pixel' для игр до 1985 года
            $this->release_year < 1995 => '16bit',  // Возвращает '16bit' для игр 1985-1994
            $this->release_year < 2005 => '3d-early',   // Возвращает '3d-early' для игр 1995-2004
            default => 'modern' // Возвращает 'modern' для всех остальных
        };
    }

    public function getDecadeAttribute()    // Аксессор для виртуального атрибута decade
    {
        return floor($this->release_year / 10) * 10;    // Вычисление десятилетия на основе года выпуска
    }

    // ============================================
    // МЕТОДЫ ДЛЯ РАБОТЫ С ДАННЫМИ
    // ============================================

    /**
     * Увеличивает счётчик просмотров игры
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Получить оценку, которую поставил конкретный пользователь
     *
     * @param User|null $user Пользователь (если null - возвращает null)
     * @return Rating|null
     */
    public function userRating(User $user = null)
    {
        if (!$user)
            return null;
        return $this->ratings()->where('user_id', $user->id)->first();
    }

    /**
     * Обновляет средний рейтинг игры на основе всех оценок
     * Вызывается после добавления/изменения оценки
     */
    public function updateRating()
    {
        $this->rating_avg = $this->ratings()->avg('value') ?? 0;
        $this->rating_count = $this->ratings()->count();
        $this->save();
    }
}
