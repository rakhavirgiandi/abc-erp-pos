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
            ->to(config('services.telegram-bot-api.logger_chat_id'))
            ->content('`'.$this->params['data'].'`');
    }
}