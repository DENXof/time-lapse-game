<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'release_year',
        'developer',
        'publisher',
        'description',
        'short_description',
        'cover_image',
        'platform',
        'genre_id',
        'views_count',
        'rating_avg',
        'rating_count',
        'steam_app_id',
        'manual_price',
        'prices'
    ];

    protected $casts = [
        'prices' => 'array',
    ];

    protected $appends = ['era_style', 'decade'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->slug)) {
                $game->slug = Str::slug($game->title);
            }
        });
    }

    // ========= СВЯЗИ =========
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ========= АКСЕССОРЫ =========
    public function getEraStyleAttribute()
    {
        return match (true) {
            $this->release_year < 1985 => 'pixel',
            $this->release_year < 1995 => '16bit',
            $this->release_year < 2005 => '3d-early',
            default => 'modern'
        };
    }

    public function getDecadeAttribute()
    {
        return floor($this->release_year / 10) * 10;
    }

    // ========= МЕТОДЫ =========
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function userRating(User $user = null)
    {
        if (!$user)
            return null;
        return $this->ratings()->where('user_id', $user->id)->first();
    }

    public function updateRating()
    {
        $this->rating_avg = $this->ratings()->avg('value') ?? 0;
        $this->rating_count = $this->ratings()->count();
        $this->save();
    }

    public function isFavoritedBy(User $user = null)
    {
        if (!$user)
            return false;
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    // ========= МЕТОДЫ ДЛЯ ЦЕН =========
    public function getPrice($currency = null)
    {
        $currency = $currency ?? session('currency', 'RUB');
        $prices = $this->prices ?? [];

        if (isset($prices[$currency])) {
            // Очищаем цену от символов валюты
            $price = $prices[$currency];
            $cleanedPrice = preg_replace('/[^0-9.,]/', '', $price);
            return !empty($cleanedPrice) ? $cleanedPrice : $price;
        }

        if ($this->manual_price) {
            $cleanedPrice = preg_replace('/[^0-9.,]/', '', $this->manual_price);
            return !empty($cleanedPrice) ? $cleanedPrice : $this->manual_price;
        }

        return null;
    }

    public static function getCurrencySymbol($currency)
    {
        return match ($currency) {
            'RUB' => '₽',
            'USD' => '$',
            'EUR' => '€',
            default => $currency,
        };
    }

    public static function getCurrencies()
    {
        return [
            'RUB' => ['name' => 'Российский рубль', 'symbol' => '₽'],
            'USD' => ['name' => 'Доллар США', 'symbol' => '$'],
            'EUR' => ['name' => 'Евро', 'symbol' => '€'],
        ];
    }
}
