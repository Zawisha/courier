<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Telegraph;

class Handler extends WebhookHandler
{

    protected $telegraph;

    // Используем dependency injection для Telegraph
    public function __construct(Telegraph $telegraph)
    {
        $this->telegraph = $telegraph;
    }

    public function hello()
    {
        $this->reply('Привет с сервера');
    }

    public function send_message($user)
    {
        $this->telegraph->message('hello world')->send();
    }

}