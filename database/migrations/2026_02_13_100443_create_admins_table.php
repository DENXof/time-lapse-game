<?php
//МИГРАЦИЯ ДЛЯ СОЗДАНИЯ ТАБЛИЦЫ АДМИНИСТРАТОРОВ
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // СОЗДАЕМ ТАБЛИЦУ 'admins' (администраторы)
        Schema::create('admins', function (Blueprint $table) {

            // ------------------------------------------------------------
            // ПОЛЯ ТАБЛИЦЫ
            // ------------------------------------------------------------

            // id администратора (первичный ключ, авто-инкремент)
            $table->id();

            // Имя администратора
            $table->string('name');

            // Email администратора
            $table->string('email')->unique();

            // Пароль администратора (в базе хранится в зашифрованном виде)
            // Сами пароли не храним, только их хеши
            $table->string('password');

            // Токен для функции "запомнить меня"
            // Когда админ ставит галочку "Запомнить меня", создается этот токен
            $table->rememberToken();

            // created_at и updated_at - дата создания и обновления записи
            // Laravel сам их заполняет
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
