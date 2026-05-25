<?php

namespace App\Services;

use App\Jobs\DiscordNotificationJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    /**
     * Отправить embed сообщение в Discord (асинхронно через очередь)
     */
    public function sendAsync($embed)
    {
        if (!$this->webhookUrl) {
            Log::error('Discord webhook URL not configured');
            return false;
        }

        DiscordNotificationJob::dispatch($embed);
        return true;
    }

    /**
     * Отправить embed сообщение в Discord (синхронно)
     */
    public function send($embed)
    {
        if (!$this->webhookUrl) {
            Log::error('Discord webhook URL not configured');
            return false;
        }

        if (is_array($embed) && isset($embed['title'])) {
            $data = [
                'username' => 'TimeLapse Games',
                'embeds' => [$embed]
            ];
        } else {
            $data = [
                'username' => 'TimeLapse Games',
                'content' => $embed
            ];
        }

        try {
            $response = Http::post($this->webhookUrl, $data);

            if ($response->successful()) {
                Log::info('Discord notification sent successfully');
                return true;
            }

            Log::error('Discord notification failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Discord exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Уведомление о новой игре
     */
    public function notifyNewGame($game)
    {
        $embed = [
            'title' => '🎮 Новая игра добавлена!',
            'description' => "**{$game->title}**\n\n" . substr(strip_tags($game->description), 0, 200),
            'color' => 0x00ff00,
            'url' => route('games.show', $game->slug),
            'fields' => [
                [
                    'name' => '📅 Год выпуска',
                    'value' => $game->release_year,
                    'inline' => true
                ],
                [
                    'name' => '🎭 Жанр',
                    'value' => $game->genre->name ?? 'Не указан',
                    'inline' => true
                ],
                [
                    'name' => '👨‍💻 Разработчик',
                    'value' => $game->developer,
                    'inline' => true
                ]
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        if ($game->cover_image) {
            $embed['thumbnail'] = ['url' => Storage::url($game->cover_image)];
        }

        return $this->sendAsync($embed);
    }

    /**
     * Уведомление о новом достижении
     */
    public function notifyAchievement($user, $achievement)
    {
        $embed = [
            'title' => '🏆 Новое достижение!',
            'description' => "**{$user->name}** получил достижение **{$achievement->name}**!\n\n{$achievement->description}",
            'color' => 0xffaa00,
            'fields' => [
                [
                    'name' => '⭐ Очки',
                    'value' => $achievement->points,
                    'inline' => true
                ]
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        return $this->sendAsync($embed);
    }

    /**
     * Уведомление о новом комментарии
     */
    public function notifyComment($comment)
    {
        $embed = [
            'title' => '💬 Новый комментарий',
            'description' => "**{$comment->user->name}** оставил комментарий к игре **{$comment->game->title}**:\n\n" . substr($comment->content, 0, 500),
            'color' => 0x00aaff,
            'url' => route('games.show', $comment->game->slug) . '#comment-' . $comment->id,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($comment->user->avatar) {
            $embed['thumbnail'] = ['url' => Storage::url($comment->user->avatar)];
        }

        return $this->sendAsync($embed);
    }
}
