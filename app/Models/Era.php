<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Подключаем базовый класс Model

class Era extends Model
{
    use HasFactory; // Подключаем трейт HasFactory
    //РАЗРЕШЕННЫЕ ДЛЯ ЗАПОЛНЕНИЯ ПОЛЯ
    protected $fillable = [
        'name',              // Название эпохи (например "Эра 8-битных компьютеров")
        'slug',              // URL-адрес (например "8bit-era")
        'start_year',        // Год начала эпохи
        'end_year',          // Год окончания эпохи
        'description',       // Описание эпохи (что происходило)
        'characteristics',   // Характерные особенности (технологии, тренды)
        'transition',        // Как произошел переход к следующей эпохе
        'color_primary',     // Основной цвет для оформления (например #e52521)
        'color_secondary',   // Дополнительный цвет для градиентов
        'font_family',       // Шрифт для стилизации под эпоху
        'background_image'   // Фоновое изображение (путь к файлу)
    ];
    /**
     * Продолжительность эпохи
     * @return int
     */
    public function getDurationAttribute()
    {
        // Вычисляем разницу между годом окончания и началом
        return $this->end_year - $this->start_year;
    }
}
