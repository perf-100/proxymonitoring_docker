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

    public function create(User $user, array $input): void
    {
        TelegramBot::create([
            'user_id' => $user->id,
            'bot_token' => $input['bot_token'],
            'chat_id' => $input['chat_id'],
            'status' => 'active',
        ]);
    }

    public function toggle(TelegramBot $bot): void
    {
        $bot->update(['status' => $bot->status === 'active' ? 'disabled' : 'active']);
    }

    public function delete(TelegramBot $bot): void
    {
        $bot->delete();
    }

    public function validateToken(string $token): bool
    {
        try {
            $response = Http::timeout(2)->get(
                "https://api.telegram.org/bot{$token}/getMe"
            );

            return $response->successful() && $response->json('ok');
        } catch (\Throwable $e) {
            return false;
        }
    }
}