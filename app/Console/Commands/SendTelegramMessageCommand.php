<?php

namespace App\Console\Commands;

use Controllers\Play\Minecraft\TelegramController;
use Illuminate\Console\Command;

class SendTelegramMessageCommand extends Command
{
    protected $signature = 'app:tg-send {chatId} {message}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $api = new TelegramController();
        $api->response(
            $api->builder($this->argument('message'), $this->argument('chatId'))
        );
    }
}
