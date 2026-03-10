<?php

namespace App\Console\Commands;

use App\Jobs\CheckProxyJob;
use App\Models\Proxy;
use Illuminate\Console\Command;

class ProxySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxy:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ежеминутный запуск джобов';

    /**
     * Execute the console command.
    */
    public function handle()
    {
        Proxy::shouldBeChecked()
            ->select('id')
            ->chunkById(100, function ($proxies) {
                foreach ($proxies as $proxy) {
                    CheckProxyJob::dispatch($proxy->id);
                }
            });
    }
}
