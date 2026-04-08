<?php

namespace App\Helpers;

use App\Jobs\CheckProxyJob;
use App\Models\Proxy;
use App\Models\User;

class ProxyHelper
{
    private $limit = 20;

    public function __construct(private ParserHelper $parser) {

    }

    public function paginate(User $user, array $filters) 
    {
        $data = Proxy::where('user_id', $user->id)->filter($filters)->orderBy('created_at', 'desc')->paginate($this->limit)->withQueryString();

        return $data;
    }

    public function create(User $user, array $input): void
    {
        $parsed = $this->parseInput($input);

        Proxy::create([
            'user_id' => $user->id,
            'host' => $parsed['host'],
            'port' => $parsed['port'],
            'login' => $parsed['login'],
            'password' => $parsed['password'],
            'type' => $parsed['type'],
            'check_interval' => $input['check_interval'],
            'comment' => $input['comment'] ?? null,
        ]);
    }

    public function update(Proxy $proxy, array $input): void
    {
        $parsed = $this->parseInput($input);

        $proxy->update([
            'host' => $parsed['host'],
            'port' => $parsed['port'],
            'login' => $parsed['login'],
            'password' => $parsed['password'],
            'type' => $parsed['type'],
            'comment' => $input['comment'] ?? null,
            'check_interval' => $input['check_interval'],
        ]);
    }

    public function delete(Proxy $proxy): void
    {
        $proxy->delete();
    }

    public function dispatchCheck(Proxy $proxy): void
    {
        CheckProxyJob::dispatch($proxy->id);
    }

    private function parseInput(array $input): array
    {
        return $this->parser->parse($input['proxy_string']);
    }
}
