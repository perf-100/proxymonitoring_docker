<?php

namespace App\Events;

use App\Models\Proxy;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProxyChecked implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $proxy;

    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    public function broadcastOn()
    {
        return new Channel('proxies');
    }

    public function broadcastAs()
    {
        return 'proxy.updated';
    }
}