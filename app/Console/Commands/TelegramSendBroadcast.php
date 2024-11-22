<?php

namespace App\Console\Commands;

use Controllers\Play\Minecraft\TelegramController;
use Illuminate\Console\Command;

class TelegramSendBroadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tg-broadcast {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new TelegramController();
        $api->test_broadcast($this->argument('message'));
    }
}
