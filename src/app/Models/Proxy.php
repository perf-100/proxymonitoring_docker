<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'login',
        'password',
        'type',
        'raw',
        'comment',
        'status',
        'checked_at',
        'check_interval'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checks()
    {
        return $this->hasMany(ProxyCheck::class);
    }

    public function notifications()
    {
        return $this->hasMany(TelegramNotification::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, fn($q,$v) =>
                $q->where('host','like',"%$v%")
                  ->orWhere('raw','like',"%$v%")
            )
            ->when($filters['type'] ?? null, fn($q,$v) =>
                $q->where('type',$v)
            )
            ->when($filters['status'] ?? null, fn($q,$v) =>
                $q->where('status',$v));
    }

    public function shouldBeChecked(): bool
    {
        if (!$this->checked_at) {
            return true;
        }

        return $this->checked_at->addSeconds($this->check_interval - 5)->lte(Carbon::now()); // чтобы старт ежеминутного расписания работал отнимаю 5 секунд
    }
}
