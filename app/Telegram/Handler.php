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

    public function send_message($user)
    {
        $chat = TelegraphChat::find(2);
        Telegraph::message('hello world')->chat($chat)->send();
    }

}