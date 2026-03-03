<?php

namespace App\Policies;

use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TelegramBotPolicy
{
    public function view(User $user, TelegramBot $bot)
    {
        return $bot->user_id === $user->id;
    }

    public function update(User $user, TelegramBot $bot)
    {
        return $bot->user_id === $user->id;
    }

    public function delete(User $user, TelegramBot $bot)
    {
        return $bot->user_id === $user->id;
    }
}
