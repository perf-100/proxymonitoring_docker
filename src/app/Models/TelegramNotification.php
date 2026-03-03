<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'proxy_id',
        'message',
        'status',
    ];

    public function proxy()
    {
        return $this->belongsTo(Proxy::class);
    }
}
