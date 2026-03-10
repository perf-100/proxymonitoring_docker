<?php

namespace App\Http\Requests;

use App\Helpers\ParserHelper;
use App\Models\Proxy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class ProxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proxy_string' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'check_interval' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $parsed = app(ParserHelper::class)->parse($this->proxy_string);

            if (!$parsed) {
                $validator->errors()->add(
                    'proxy_string',
                    'Неверный формат прокси'
                );
                return;
            }

            $proxy = $this->route('proxy');

            $query = Proxy::where('user_id', Auth::id())
                ->where('host', $parsed['host'])
                ->where('port', $parsed['port']);

            if ($proxy) {
                $query->where('id', '!=', $proxy->id);
            }

            if ($query->exists()) {
                $validator->errors()->add('proxy_string', 'Такой прокси уже добавлен');
            }
        });
    }
}