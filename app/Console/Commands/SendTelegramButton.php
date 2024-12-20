<?php

namespace App\Console\Commands;

use Controllers\Play\Minecraft\TelegramController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTelegramButton extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tg-button {chatId} {message} {buttonText} {buttonCommand}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить тестовую кнопку [{chatId} {message} {buttonText} {buttonCommand}]';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new TelegramController();
        $api->response(
            $api->withButtons(
                $api->builder($this->argument('message'), $this->argument('chatId')),
                [
                    ['text' => $this->argument('buttonText'), 'command' => $this->argument('buttonCommand')]
                ]
            )
        );

        var_dump($api->withButtons(
            $api->builder($this->argument('message'), $this->argument('chatId')),
            [
                ['text' => $this->argument('buttonText'), 'command' => $this->argument('buttonCommand')]
            ]
        ));
    }
}
