<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'TimeLapse Games', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'История игровой индустрии в одном месте', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_keywords', 'value' => 'игры, история игр, видеоигры, ретро', 'type' => 'text', 'group' => 'general'],
            ['key' => 'telegram_url', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'discord_url', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'maintenance_mode', 'value' => false, 'type' => 'boolean', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
