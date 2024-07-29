<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    public function hello()
    {
        $this->reply('Привет с сервера');
    }
}