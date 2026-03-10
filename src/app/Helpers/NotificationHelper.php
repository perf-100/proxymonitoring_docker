<?php

namespace App\Helpers;

use App\Models\Proxy;
use App\Models\TelegramBot;
use App\Models\TelegramNotification;
use Illuminate\Support\Facades\Http;

class NotificationHelper
{
    private $limit = 5;

    public function __construct() {

    }

    public function paginate(Proxy $proxy) {

        $data = $proxy->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($this->limit)
            ->withQueryString();

        return $data;
    }

    public function notifyStatusChange(Proxy $proxy, string $oldStatus)
    {
        if ($proxy->status === $oldStatus) {
            return;
        }

        $bots = TelegramBot::where('user_id', $proxy->user_id)
            ->where('status', 'active')
            ->get();

        foreach ($bots as $bot) {

            $statusText = $proxy->status === 'failed'
                ? 'Прокси упал'
                : 'Прокси снова работает';

            $message = "<b>{$statusText}</b> \n{$proxy->raw}";

            $this->send($bot, $proxy, $message);
        }
    }

    private function send(TelegramBot $bot, Proxy $proxy, string $message)
    {
        try {
            $response = Http::post(
                "https://api.telegram.org/bot{$bot->bot_token}/sendMessage",
                [
                    'chat_id' => $bot->chat_id,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ]
            );

            TelegramNotification::create([
                'proxy_id' => $proxy->id,
                'message' => $message,
                'status' => $response->successful() ? 'send' : 'notsend',
            ]);

        } catch (\Exception $e) {
            TelegramNotification::create([
                'proxy_id' => $proxy->id,
                'message' => $message,
                'status' => 'notsend',
            ]);
        }
    }
}
