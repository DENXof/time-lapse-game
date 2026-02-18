<?php
//ГЛАВНЫЙ СИДЕР, КОТОРЫЙ ЗАПУСКАЕТ ОСТАЛЬНЫЕ
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Выводим красивое сообщение в консоль (зеленым цветом)
        $this->command->info('🔄 Запуск сидеров...');
        $this->command->info('=====================================');
        $this->call([
            GenreSeeder::class, //Жанры
            EraSeeder::class,   //Эпохи
            GameSeeder::class,  //Игры
        ]);

        // Сообщаем, что всё готово
        $this->command->info('=====================================');
        $this->command->info('🎉 Все сидеры успешно выполнены!');
    }
}
