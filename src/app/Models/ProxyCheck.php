<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProxyCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'proxy_id',
        'status',
        'time',
        'ip_addr',
        'message',
    ];

    public function proxy()
    {
        return $this->belongsTo(Proxy::class);
    }
}
