<?php

namespace Controllers\Play\Minecraft;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TelegramController extends Controller
{
    private string $token;

    public function __construct()
    {
        $this->token = config('app.telegram_token');
    }

    public function handler(Request $request)
    {
        Log::info('Telegram message', $request->all());
        $text = $request->input('message.text');
        $chatId = $request->input('message.from.id');

        $user = User::where('telegram_id', $chatId)->first();
        if ($user) {
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
                        $this->builder(
                            "ID: " . $user->id . "\n" .
                            "Пользователь: " . $user->name . "\n",
                            $chatId)
                    );

                default:
                    $this->response(
                        $this->builder('Комманда не найдена', $chatId)
                    );
            }
        } else {
            $this->response(
                $this->builder('Вы не авторизованы', $chatId)
            );
        }

    }

    protected function response($data): void
    {
        Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', $data);
    }

    protected function builder(string $text, string $chatId): array
    {
        $data = [
            'text' => $text,
            'chat_id' => $chatId,
            'inline_keyboard' => []
        ];
        return $data;
    }

    protected function withButtons(array $data, array $buttons): array
    {
        foreach ($buttons as $button) {
            $data['inline_keyboard'] = ['text' => $button['text'], 'callback_data' => $button['command']];
        }
        return $data;
    }
}
