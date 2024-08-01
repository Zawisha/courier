<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{
    public function start()
    {
        $this->reply('Бот запущен, теперь ты будешь знать кто зарегился на нашей платформе');
    }

    public function hello()
    {
        $this->reply('Привет с сервера');
    }

    public function send_message($phone,$firstName,$surname,$patronymic,$id,$telegram)
    {
        $chats = TelegraphChat::all();
        if (substr($telegram, 0, 1) !== '@') {
            $telegram = '@' . $telegram;
        };
        foreach ($chats as $chat) {
            $chat->message('Зарегистрировался: ' . $firstName . ' ' . $surname . ' ' . $patronymic . ' Телефон: ' . $phone.' '.'Телеграм:'.$telegram.' '.'https://luxury-courier.ru/edit-user/'.$id)->send();
        }
    }

}