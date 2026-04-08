<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use App\Rules\ValidTelegramBotToken;

class TelegramBotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bot_token' => ['required', 'string', new ValidTelegramBotToken],
            'chat_id'   => ['required', 'string'],
        ];
    }
}
