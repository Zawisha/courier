<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{

    public function hello()
    {
        $this->reply('Привет с сервера');
    }

    public function send_message($phone,$firstName,$surname,$patronymic)
    {
        $chats = TelegraphChat::all();

        foreach ($chats as $chat) {
            $chat->message('Зарегистрировался: ' . $firstName . ' ' . $surname . ' ' . $patronymic . ' Телефон: ' . $phone)->send();
        }
    }

}