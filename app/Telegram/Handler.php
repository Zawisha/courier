<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Telegraph;

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