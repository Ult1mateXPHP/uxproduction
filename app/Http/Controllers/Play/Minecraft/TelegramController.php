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
            'url' => 'play.ultimatex.tech/api/bot/handler'
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

        if($callback != null) {
            $chatId = $request->input('callback_query.from.id');
        } elseif ($text != null) {
            $chatId = $request->input('message.from.id');
        } elseif($request->input('my_chat_member') != null) {
            Log::info('New Group Message');
        }

        $user = User::where('telegram_id', $chatId)->first();
        if ($user) {
            if($callback != null) {
                Log::info('Callback Proccessing');
                $chatId = $request->input('callback_query.from.id');
                switch ($callback['data']) {
                    case 'menu':
                        $this->response(
                            $this->withButtons(
                                $this->builder("Меню\nБот сделан с душой Ult1mateXPHP\nUXProduction 2024\nt.me/uxproduction", $chatId),
                                [
                                    ['text' => 'Аккаунт', 'command' => 'me'],
                                    //['text' => 'Обратная связь', 'command' => 'new_ticket']
                                ]
                            )
                        );
                        break;

                    case 'me':
                        $this->response(
                            $this->withButtons(
                                $this->builder(
                                    "ID: " . $user->id . "\n" .
                                    "Пользователь: " . $user->name . "\n",
                                    $chatId),
                                [
                                    ['text' => 'Скачать моды', 'command' => 'mods']
                                ]
                            ),
                        );
                        break;

                    case 'mods':
                        $this->response(
                            $this->withButtons(
                                $this->builder('Выберите сервер', $chatId),
                                [
                                    ['text' => 'Кибер Казахстан', 'command' => 'mods kz latest'],
                                    ['text' => 'Выживание', 'command' => 'mods survival latest']
                                ]
                            )
                        );
                        break;

                    case 'mods kz latest':
                        $this->response(
                            $this->builder('Ваша ссылка для скачивания: '.route('package.download.reference').'/kz/latest', $chatId)
                        );
                        break;

                    case 'mods survival latest':
                        $this->response(
                            $this->builder('Ваша ссылка для скачивания: '.route('package.download.reference').'/survival/latest', $chatId)
                        );
                        break;

                    default:
                        $this->response(
                            $this->builder('Комманда не найдена', $chatId)
                        );
                }
            }

            if($text != null) {
                Log::info('Message Proccessing');
                switch ($text) {
                    case '/start':
                        $this->response(
                            $this->withButtons(
                                $this->builder('Добро пожаловать', $chatId),
                                [
                                    ['text' => 'Главное меню', 'command' => 'menu'],
                                ]
                            )
                        );
                        break;
                }
            }
        } else {
            $this->response(
                $this->builder("Вы не авторизованы\nВаш ID: ".$chatId, $chatId)
            );
        }

    }

    public function response($data): void
    {
        Log::info(Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', $data)->json());
    }

    public function builder(string $text, int $chatId): array
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
        Log::info('Attachment processing');
        Log::info(Http::post('https://api.telegram.org/bot' . $this->token . '/sendDocument', [
            'chat_id' => $chatId,
            'document' => $attachment,
            'caption' => $name,
            'parse_mode' => 'HTML'
        ]));
    }
}
