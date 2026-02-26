<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable; // Подключаем класс для аутентификации (чтобы админ мог входить в систему)
use Illuminate\Notifications\Notifiable;    // Подключаем трейт для отправки уведомлений (например, писем на почту)

class Admin extends Authenticatable // Класс Admin наследуется от Authenticatable - значит админ может логиниться
{
    use Notifiable; // Подключаем трейт Notifiable - чтобы админ мог получать уведомления
    protected $table = 'admins';    //ТАБЛИЦА В БАЗЕ ДАННЫХ
    protected $fillable = [ //$fillable - это "белый список" полей, которые можно заполнять через формы
        'name',     // Имя администратора
        'email',    // Email для входа
        'password', // Пароль (будет захэширован)
    ];
    // СКРЫТЫЕ ПОЛЯ
    // Эти поля не будут видны при преобразовании модели в JSON/массив
    // Например, если вернуть админа через API, пароль не покажется
    protected $hidden = [
        'password',        // Пароль - скрываем нафиг!
        'remember_token',  // Токен "запомнить меня" - тоже скрываем
    ];
    // ПРЕОБРАЗОВАНИЕ ТИПОВ
    // Указываем Laravel, как автоматически преобразовывать поля
    // при чтении из базы данных
    protected $casts = [
        'email_verified_at' => 'datetime',  // Дата регистрации
        'password' => 'hashed', // Пароль уже захэширован
    ];
}
