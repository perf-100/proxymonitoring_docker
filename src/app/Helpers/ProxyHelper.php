<?php

namespace App\Helpers;

use App\Jobs\CheckProxyJob;
use App\Models\Proxy;
use App\Models\User;
use App\Services\ProxyParser;
use Illuminate\Validation\ValidationException;

class ProxyHelper
{
    private $limit = 20;

    public function __construct(private ParserHelper $parser) {

    }

    public function paginate(User $user, array $filters) {

        $data = Proxy::where('user_id', $user->id)->filter($filters)->orderBy('created_at', 'desc')->paginate($this->limit)->withQueryString();

        return $data;
    }

    public function create(User $user, array $input) {

        $parsed = $this->parser->parse($input['proxy_string']);

        $exists = Proxy::where('user_id', $user->id)
            ->where('host', $parsed['host'])
            ->where('port', $parsed['port'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'proxy_string' => 'Такой прокси уже добавлен'
            ]);
        }

        Proxy::create([
            'user_id' => $user->id,
            'host' => $parsed['host'],
            'port' => $parsed['port'],
            'login' => $parsed['login'],
            'password' => $parsed['password'],
            'type' => $parsed['type'],
            'raw' => $input['proxy_string'],
            'check_interval' => $input['check_interval'],
            'comment' => $input['comment'] ?? null,
        ]);
    }

    public function update(User $user, Proxy $proxy, array $input) {

        $parsed = $this->parser->parse($input['proxy_string']);

        $exists = Proxy::where('user_id', $user->id)
            ->where('host', $parsed['host'])
            ->where('port', $parsed['port'])
            ->whereNot('id', $proxy->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'proxy_string' => 'Такой прокси уже добавлен'
            ]);
        }

        $proxy->update([
            'host' => $parsed['host'],
            'port' => $parsed['port'],
            'login' => $parsed['login'],
            'password' => $parsed['password'],
            'type' => $parsed['type'],
            'raw' => $input['proxy_string'],
            'comment' => $input['comment'] ?? null,
            'check_interval' => $input['check_interval'],
        ]);
    }

    public function delete(Proxy $proxy) {
        $proxy->delete();
    }

    public function dispatchCheck(Proxy $proxy) {
        CheckProxyJob::dispatch($proxy);
    }
}
