<?php
// МОДЕЛЬ ЖАНРЫ ИГР

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    // Подключаем трейт HasFactory
    use HasFactory;

    /**
     * РАЗРЕШЕННЫЕ ДЛЯ ЗАПОЛНЕНИЯ ПОЛЯ
     *
     * $fillable - это "белый список" полей, которые можно заполнять через формы
     * Защита от массового присвоения (когда хакер пытается подсунуть лишние поля)
     */
    protected $fillable = [
        'name',         // Название жанра (например "RPG")
        'slug',         // URL-адрес (например "rpg")
        'description',  // Описание жанра
        'icon',         // Иконка (эмодзи или HTML-код)
        'sort_order',   // Порядок сортировки (чем меньше число, тем выше в списке)
        'is_active'     // Активен ли жанр (true - показывать, false - скрыть)
    ];

    /**
     * ПРЕОБРАЗОВАНИЕ ТИПОВ
     *
     * Указываем Laravel, как автоматически преобразовывать поля
     * при чтении из базы данных
     */
    protected $casts = [
        'is_active' => 'boolean',   // Преобразуем в true/false (а не 1/0)
        'sort_order' => 'integer'   // Преобразуем в целое число
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    /**
     * СОБЫТИЯ МОДЕЛИ (BOOT METHOD)
     *
     * Здесь можно задать действия, которые будут выполняться
     * при определенных событиях (создание, обновление)
     */
    protected static function boot()
    {
        parent::boot(); // Вызываем родительский метод (обязательно)

        /**
         * СОБЫТИЕ: ПЕРЕД СОЗДАНИЕМ ЗАПИСИ
         *
         * Срабатывает автоматически, когда создается новый жанр
         */
        static::creating(function ($genre) {
            // Если slug не заполнен вручную
            if (empty($genre->slug)) {
                // Создаем slug из названия
                // Например: "Ролевая игра" -> "rolevaya-igra"
                $genre->slug = \Str::slug($genre->name);
            }
        });

        /**
         * СОБЫТИЕ: ПЕРЕД ОБНОВЛЕНИЕМ ЗАПИСИ
         *
         * Срабатывает автоматически, когда обновляется существующий жанр
         */
        static::updating(function ($genre) {
            // isDirty('name') - проверяет, изменилось ли поле name
            // Если имя изменилось И slug пустой
            if ($genre->isDirty('name') && empty($genre->slug)) {
                // Обновляем slug в соответствии с новым названием
                $genre->slug = \Str::slug($genre->name);
            }
        });
    }

    // ============================================
    // SCOPES - ПОМОЩНИКИ ДЛЯ ЗАПРОСОВ
    // ============================================

    /**
     * SCOPЕ: ТОЛЬКО АКТИВНЫЕ ЖАНРЫ
     *
     * Позволяет легко отфильтровать только активные жанры
     *
     * Как использовать:
     * $activeGenres = Genre::active()->get(); // Только активные жанры
     *
     * @param $query Объект запроса
     * @return mixed
     */
    public function scopeActive($query)
    {
        // where('is_active', true) - только те, где is_active = true
        return $query->where('is_active', true);
    }

    /**
     * SCOPЕ: СОРТИРОВКА ЖАНРОВ
     *
     * Сортирует жанры сначала по порядку сортировки, затем по названию
     *
     * Как использовать:
     * $genres = Genre::sorted()->get(); // Отсортированные жанры
     *
     * @param $query Объект запроса
     * @return mixed
     */
    public function scopeSorted($query)
    {
        // orderBy('sort_order') - сначала по порядку (меньше = выше)
        // orderBy('name') - затем по алфавиту
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ============================================
    // ВИРТУАЛЬНЫЕ АТРИБУТЫ (GETTERS)
    // ============================================

    /**
     * ВИРТУАЛЬНЫЙ АТРИБУТ: КОЛИЧЕСТВО ИГР В ЖАНРЕ
     *
     * Динамически подсчитывает, сколько игр в этом жанре
     * НЕ хранится в базе данных, а вычисляется "на лету"
     *
     * Как использовать:
     * $genre = Genre::find(1);
     * $count = $genre->games_count; // Сколько игр в этом жанре
     *
     * @return int
     */
    public function getGamesCountAttribute()
    {
        // games() - обращаемся к связи
        // count() - считаем количество связанных записей
        return $this->games()->count();
    }
}
