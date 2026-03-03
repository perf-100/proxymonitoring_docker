<?php

namespace App\Helpers;

use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TelegramBotHelper
{
    private $limit = 20;

    public function paginate(User $user, array $filters)
    {
        $data = TelegramBot::where('user_id', $user->id)->filter($filters)->orderBy('created_at', 'desc')->paginate($this->limit)->withQueryString();

        return $data;
    }

    public function create(User $user, array $input)
    {
        $botToken = $input['bot_token'];

        $response = Http::timeout(5)
            ->get("https://api.telegram.org/bot{$botToken}/getMe");

        if (!$response->successful() || !$response->json('ok')) {
            throw ValidationException::withMessages([
                'bot_token' => 'Неверный токен бота'
            ]);
        }

        TelegramBot::create([
            'user_id' => $user->id,
            'bot_token' => $botToken,
            'chat_id' => $input['chat_id'],
            'status' => 'active',
        ]);
    }

    public function toggle(TelegramBot $bot)
    {
        $bot->update(['status' => $bot->status === 'active' ? 'disabled' : 'active']);
    }

    public function delete(TelegramBot $bot)
    {
        $bot->delete();
    }
}