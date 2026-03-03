<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}