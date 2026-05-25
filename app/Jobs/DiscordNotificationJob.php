<?php

namespace App\Jobs;

use App\Services\DiscordService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DiscordNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $embed;

    public function __construct($embed)
    {
        $this->embed = $embed;
    }

    public function handle(DiscordService $discordService)
    {
        $discordService->send($this->embed);
    }
}
