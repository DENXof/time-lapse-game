<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Запуск сидеров...');
        $this->command->info('=====================================');

        // Вызываем все сидеры в правильном порядке
        $this->call([
            AdminSeeder::class,     // Сначала админ
            GenreSeeder::class,     // Потом жанры
            EraSeeder::class,       // Потом эпохи
            GameSeeder::class,      // Потом игры
        ]);

        $this->command->info('=====================================');
        $this->command->info('🎉 Все сидеры успешно выполнены!');
    }
}
