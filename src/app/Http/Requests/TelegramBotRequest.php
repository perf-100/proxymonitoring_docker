<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;

class TelegramBotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bot_token' => ['required', 'string', 'max:255'],
            'chat_id'   => ['required', 'integer'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $botToken = $this->bot_token;

            $response = Http::timeout(5)->get("https://api.telegram.org/bot{$botToken}/getMe");

            if (!$response->successful() || !$response->json('ok')) {
                $validator->errors()->add('bot_token', 'Неверный токен бота');
            }
        });
    }
}
