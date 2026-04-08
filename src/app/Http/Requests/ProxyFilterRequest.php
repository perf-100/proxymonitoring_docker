<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProxyFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'type' => ['nullable', 'in:http,https,socks4,socks5'],
            'status' => ['nullable', 'in:unknown,working,failed'],
        ];
    }
}