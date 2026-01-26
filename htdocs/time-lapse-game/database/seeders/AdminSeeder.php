<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Создание администратора...');

        // Проверяем, нет ли уже администратора с таким email
        $existingAdmin = Admin::where('email', 'admin@timelapse.com')->first();

        if ($existingAdmin) {
            $this->command->info('✅ Администратор уже существует:');
            $this->command->info("   👤 Имя: {$existingAdmin->name}");
            $this->command->info("   📧 Email: {$existingAdmin->email}");
            $this->command->info("   🆔 ID: {$existingAdmin->id}");
            return;
        }

        // Создаем администратора
        $admin = Admin::create([
            'name' => 'Администратор TimeLapse',
            'email' => 'admin@timelapse.com',
            'password' => Hash::make('admin123'), // Пароль: admin123
        ]);

        $this->command->info('🎉 Администратор успешно создан!');
        $this->command->info(' ');
        $this->command->info('📋 Данные для входа:');
        $this->command->info('   👤 Имя: ' . $admin->name);
        $this->command->info('   📧 Email: ' . $admin->email);
        $this->command->info('   🔑 Пароль: admin123');
        $this->command->info('   🆔 ID: ' . $admin->id);
        $this->command->info(' ');
        $this->command->info('⚠️  ВАЖНО:');
        $this->command->info('   • Смените пароль после первого входа!');
        $this->command->info('   • Админка доступна по: /admin/login');
        $this->command->info('   • Для выхода: /admin/logout');
        $this->command->info(' ');
        $this->command->info('🚀 Готово! Теперь вы можете войти в админ-панель.');
    }
}
