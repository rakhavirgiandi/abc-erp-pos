<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class TelegramError extends Notification
{
    protected $params;

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function via($notifiable)
    {
        return ["telegram"];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to(env('TELEGRAM_LOGGER_CHAT_ID'))
            ->content('`'.$this->params['data'].'`');
    }
}