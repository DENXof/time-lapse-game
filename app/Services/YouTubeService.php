<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    protected $apiKey;
    protected $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    /**
     * Поиск трейлера игры на YouTube
     */
    public function searchTrailer($gameTitle, $releaseYear = null)
    {
        if (!$this->apiKey) {
            return null;
        }

        $cacheKey = 'youtube_trailer_' . md5($gameTitle . $releaseYear);

        return Cache::remember($cacheKey, 86400, function () use ($gameTitle, $releaseYear) {
            $query = $gameTitle . ' ' . ($releaseYear ? $releaseYear . ' ' : '') . 'trailer game';

            $response = Http::get($this->baseUrl . '/search', [
                'part' => 'snippet',
                'q' => $query,
                'maxResults' => 1,
                'type' => 'video',
                'key' => $this->apiKey,
                'videoCategoryId' => 20, // Gaming category
            ]);

            if ($response->successful() && isset($response['items'][0])) {
                $video = $response['items'][0];
                return [
                    'id' => $video['id']['videoId'],
                    'title' => $video['snippet']['title'],
                    'thumbnail' => $video['snippet']['thumbnails']['high']['url'],
                    'embed_url' => 'https://www.youtube.com/embed/' . $video['id']['videoId']
                ];
            }

            return null;
        });
    }

    /**
     * Получить трейлер по ID видео
     */
    public function getVideoInfo($videoId)
    {
        if (!$this->apiKey) {
            return null;
        }

        $cacheKey = 'youtube_video_' . $videoId;

        return Cache::remember($cacheKey, 86400, function () use ($videoId) {
            $response = Http::get($this->baseUrl . '/videos', [
                'part' => 'snippet,statistics',
                'id' => $videoId,
                'key' => $this->apiKey
            ]);

            if ($response->successful() && isset($response['items'][0])) {
                $video = $response['items'][0];
                return [
                    'id' => $videoId,
                    'title' => $video['snippet']['title'],
                    'views' => $video['statistics']['viewCount'] ?? 0,
                    'likes' => $video['statistics']['likeCount'] ?? 0,
                ];
            }

            return null;
        });
    }
}
