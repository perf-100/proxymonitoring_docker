<?php

namespace App\Rules;

use App\Helpers\TelegramBotHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTelegramBotToken implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $helper = app(TelegramBotHelper::class);

        if (!$helper->validateToken($value)) {
            $fail('Неверный токен бота');
        }
    }
}