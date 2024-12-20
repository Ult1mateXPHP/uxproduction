<?php

namespace App\Console\Commands;

use Controllers\Play\Minecraft\TelegramController;
use Illuminate\Console\Command;

class SendTelegramMessageCommand extends Command
{
    protected $signature = 'app:tg-send {chatId} {message}';

    protected $description = 'Отправить сообщение в чат [{chatId} {message}]';

    public function handle(): void
    {
        $api = new TelegramController();
        $api->test_message($this->argument('message'), $this->argument('chatId'));
    }
}
