<?php

namespace App\Helpers;

use App\Models\Proxy;
use App\Models\ProxyCheck;
use Illuminate\Support\Facades\Http;

class ProxyCheckHelper
{
    private $limit = 5;
    private $urlCheckProxy;

    public function __construct() {
        $this->urlCheckProxy = config('services.proxy_check.url');
    }

    public function paginate(Proxy $proxy)
    {
        $data = $proxy->checks()
            ->orderBy('created_at', 'desc')
            ->paginate($this->limit)
            ->withQueryString();

        return $data;
    }

    public function check(Proxy $proxy): bool
    {
        $start = microtime(true);

        try {
            $response = Http::withOptions([
                'proxy' => $proxy->buildProxyUrl(),
                'timeout' => 10,
            ])->get($this->urlCheckProxy . '?format=json');

            $time = (microtime(true) - $start) * 1000;

            if ($response->successful()) {

                $ip = $response->json()['ip'] ?? null;

                $proxy->update([
                    'status' => 'working',
                    'checked_at' => now(),
                ]);

                ProxyCheck::create([
                    'proxy_id' => $proxy->id,
                    'status' => 'working',
                    'time' => (int) $time,
                    'ip_addr' => $ip,
                ]);

                return true;
            }
        } catch (\Exception $e) {

            $time = (microtime(true) - $start) * 1000;

            $proxy->update([
                'status' => 'failed',
                'checked_at' => now(),
            ]);

            ProxyCheck::create([
                'proxy_id' => $proxy->id,
                'status' => 'failed',
                'time' => (int) $time,
                'message' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
