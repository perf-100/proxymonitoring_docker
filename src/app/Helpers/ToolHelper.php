<?php

namespace App\Helpers;

use App\Models\ProxyCheck;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class ToolHelper
{
    private $urlGetIp;

    public function __construct()
    {
        $this->urlGetIp = env('URL_GET_IP');
    }

    public function lookup(User $user)
    {
        $response = Http::timeout(5)->get($this->urlGetIp . '/json/');

        if (!$response->successful()) {
            throw new \Exception('IP service unavailable');
        }

        $data = $response->json();

        $latestChecks = ProxyCheck::where('status', 'working')
            ->whereHas('proxy', fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('proxy_id')
            ->map(fn($checks) => $checks->first()->ip_addr) // берём только  свежий ip
            ->toArray();

        $data['proxy'] = in_array($data['query'] ?? '', $latestChecks);

        return $data;
    }
}
