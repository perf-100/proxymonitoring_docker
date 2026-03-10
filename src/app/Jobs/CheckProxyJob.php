<?php

namespace App\Jobs;

use App\Events\ProxyChecked;
use App\Helpers\ProxyCheckHelper;
use App\Models\Proxy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckProxyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $proxyId) 
    {

    }

    /**
     * Execute the job.
     */

    public function handle(ProxyCheckHelper $checkerHelper): void {

        $proxy = Proxy::find($this->proxyId);

        if (!$proxy) {
            return;
        }

        $checkerHelper->check($proxy);

        broadcast(new ProxyChecked($proxy->fresh()));
    }
}
