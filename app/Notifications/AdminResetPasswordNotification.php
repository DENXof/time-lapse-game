<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('admin.password.reset', $this->token) . '?email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Сброс пароля - TimeLapse Games Админ-панель')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи администратора.')
            ->action('Сбросить пароль', $url)
            ->line('Ссылка для сброса пароля действительна в течение 60 минут.')
            ->line('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.')
            ->salutation('С уважением, Команда TimeLapse Games');
    }
}
