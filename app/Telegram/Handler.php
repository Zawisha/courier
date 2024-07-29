<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    public function hello()
    {
        $this->reply('Привет с сервера');
    }

    public function send_message($user)
    {
        Telegraph::message('hello world')->send();
    }

}