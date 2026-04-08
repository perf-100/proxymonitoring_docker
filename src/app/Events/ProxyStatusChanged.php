<?php

namespace App\Events;

use App\Models\Proxy;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProxyStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(public Proxy $proxy, public string $oldStatus) 
    {

    }
}