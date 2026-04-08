<?php

namespace App\Listeners;

use App\Events\ProxyStatusChanged;
use App\Helpers\NotificationHelper;

class SendProxyStatusNotification
{
    public function __construct(private NotificationHelper $notificationHelper)
    {

    }

    public function handle(ProxyStatusChanged $event)
    {
        $this->notificationHelper->notifyStatusChange($event->proxy, $event->oldStatus);
    }
}