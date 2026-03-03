<?php

namespace App\Jobs;

use App\Events\ProxyChecked;
use App\Helpers\NotificationHelper;
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
    public function __construct(public Proxy $proxy) 
    {

    }

    /**
     * Execute the job.
     */

    public function handle(ProxyCheckHelper $checkerHelper, NotificationHelper $notificationHelper): void {

        $oldStatus = $this->proxy->status;

        $checkerHelper->check($this->proxy);

        $proxy = $this->proxy->fresh();

        $notificationHelper->notifyStatusChange($proxy, $oldStatus);

        broadcast(new ProxyChecked($proxy));
    }
}
