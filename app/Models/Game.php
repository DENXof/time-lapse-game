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
        'age_rating'
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

    /**
     * Получить ссылку на Steam
     */
    public function getSteamUrl()
    {
        if ($this->steam_app_id) {
            return 'https://store.steampowered.com/app/' . $this->steam_app_id;
        }
        return 'https://store.steampowered.com/search?term=' . urlencode($this->title);
    }

    /**
     * Получить цвет бейджа возрастного рейтинга
     */
    public function getAgeRatingBadgeColor()
    {
        return match ($this->age_rating) {
            '0+' => 'bg-success',
            '6+' => 'bg-info',
            '12+' => 'bg-primary',
            '16+' => 'bg-warning',
            '18+' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Автоматическое определение возрастного рейтинга на основе описания и названия
     */
    public function detectAgeRating()
    {
        $text = strtolower($this->title . ' ' . ($this->description ?? ''));

        // Ключевые слова для 18+
        $keywords18 = [
            'sex', 'sexual', 'nude', 'naked', 'porn', 'erotic', 'hentai',
            'mature', 'adult', '18+', 'nsfw', 'gore', 'bloody', 'violence',
            'murder', 'kill', 'corpse', 'torture', 'rape', 'drug', 'alcohol',
            'horror', 'scary', 'blood', 'guts', 'dismember', 'cruel'
        ];

        // Ключевые слова для 16+
        $keywords16 = [
            'war', 'battle', 'fight', 'weapon', 'gun', 'shoot', 'kill',
            'death', 'dead', 'dark', 'evil', 'demon', 'hell', 'monster',
            'violent', 'aggressive', 'combat'
        ];

        // Ключевые слова для 12+
        $keywords12 = [
            'fantasy', 'magic', 'adventure', 'quest', 'monster', 'dragon',
            'sword', 'spell', 'wizard', 'hero', 'villain'
        ];

        // Проверяем 18+
        foreach ($keywords18 as $keyword) {
            if (str_contains($text, $keyword)) {
                return '18+';
            }
        }

        // Проверяем 16+
        foreach ($keywords16 as $keyword) {
            if (str_contains($text, $keyword)) {
                return '16+';
            }
        }

        // Проверяем 12+
        foreach ($keywords12 as $keyword) {
            if (str_contains($text, $keyword)) {
                return '12+';
            }
        }

        // По умолчанию
        return '0+';
    }
}
