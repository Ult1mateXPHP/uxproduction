<?php

namespace Controllers\Play\Minecraft;

use App\Http\Controllers\Controller;
use Application\Play\Minecraft\PackageApi;
use Application\Play\Minecraft\ProductionApi;
use Domain\Play\Minecraft\Repository\PackageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Infrastructure\Play\Minecraft\Storage\PackageDriver;

class TelegramController extends Controller
{
    private string $token;
    private string $broadcast_channel;
    private string $broadcast_thread;

    public function __construct()
    {
        $this->token = config('app.telegram_token');
        $this->broadcast_channel = config('app.telegram_broadcast_channel');
        $this->broadcast_thread = config('app.telegram_broadcast_thread');
    }

    public function webhook() : void {
        Log::info(Http::get('https://api.telegram.org/bot'.$this->token.'/setWebhook', [
            'url' => config('app.url')
        ]));
    }

    public function broadcast(Request $request) : void {
        $data = $this->builder($request->input('text'), $this->broadcast_channel);
        $data['reply_to_message_id'] = $this->broadcast_thread;
        $this->response($data);
    }

    public function test_broadcast($text) : void {
        $data = $this->builder($text, $this->broadcast_channel);
        $data['reply_to_message_id'] = $this->broadcast_thread;
        $this->response($data);
    }

    public function test_message($text, $chatId) {
        $this->response(
            $this->builder($text, $chatId)
        );
    }

    public function handler(Request $request) : void
    {
        Log::info('Telegram message', $request->all());
        $text = $request->input('message.text');
        $callback = $request->input('callback_query');
        $chatId = $request->input('message.from.id');

        $user = User::where('telegram_id', $chatId)->first();
        if ($user) {
            if(!empty($callback)) {
                switch ($callback['message']) {
                    case '/menu':
                        $this->response(
                            $this->withButtons(
                                $this->builder("Меню\nБот сделан с душой Ult1mateXPHP\nUXProduction 2024\nt.me/uxproduction", $chatId),
                                [
                                    ['text' => 'Аккаунт', 'command' => '/me'],
                                    //['text' => 'Обратная связь', 'command' => '/new_ticket']
                                ]
                            )
                        );

                    case '/me':
                        $this->response(
                            $this->withButtons(
                                $this->builder(
                                    "ID: " . $user->id . "\n" .
                                    "Пользователь: " . $user->name . "\n",
                                    $chatId),
                                [
                                    ['text' => 'Скачать моды', 'commands' => '/mods']
                                ]
                            ),
                        );

                    case '/mods':
                        $this->response(
                            $this->withButtons(
                                $this->builder('Выберите сервер', $chatId),
                                [
                                    ['text' => 'Кибер Казахстан', 'command' => '/mods kz latest'],
                                    ['text' => 'Выживание', 'command' => '/mods survival latest']
                                ]
                            )
                        );

                    case '/mods kz latest':
                        $package_api = new PackageApi();
                        $production_api = new ProductionApi();
                        $attachemnt = $package_api->getPackage('kz', 'latest', $production_api);
                        $this->sendAttachment($chatId, $attachemnt, 'Последние обновление');

                    case '/mods survival latest':
                        $package_api = new PackageApi();
                        $production_api = new ProductionApi();
                        $attachemnt = $package_api->getPackage('survival', 'latest', $production_api);
                        $this->sendAttachment($chatId, $attachemnt, 'Последние обновление');

                    default:
                        $this->response(
                            $this->builder('Комманда не найдена', $chatId)
                        );
                }
            }

            if(!empty($text)) {
                switch ($text) {
                    case '/start':
                        $this->response(
                            $this->withButtons(
                                $this->builder('Добро пожаловать', $chatId),
                                [
                                    ['text' => 'Главное меню', 'command' => '/menu'],
                                ]
                            )
                        );
                }
            }
        } else {
            $this->response(
                $this->builder('Вы не авторизованы', $chatId)
            );
        }

    }

    public function response($data): void
    {
        Log::info(Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', $data)->json());
    }

    public function builder(string $text, string $chatId): array
    {
        $data = [
            'text' => $text,
            'chat_id' => $chatId,
            'parse_mode' => 'markdown'
        ];
        return $data;
    }

    public function withButtons(array $data, array $buttons): array
    {
        $keyboard = [
            'inline_keyboard' => [],
        ];
        foreach ($buttons as $button) {
            $keyboard['inline_keyboard'][][] = ['text' => $button['text'], 'callback_data' => $button['command']];
        }
        $data['reply_markup'] = $keyboard;
        return $data;
    }

    public function sendAttachment(int $chatId, $attachment, string $name) : void {
        Http::post('https://api.telegram.org/bot' . $this->token . '/sendDocument', [
            'chat_id' => $chatId,
            'document' => $attachment,
            'caption' => $name,
            'parse_mode' => 'HTML'
        ]);
    }
}
