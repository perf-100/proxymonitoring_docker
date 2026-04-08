<?php

namespace App\Models;

use App\Events\ProxyStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    protected $appends = ['raw'];

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'login',
        'password',
        'type',
        'comment',
        'status',
        'checked_at',
        'check_interval'
    ];

    protected static function booted()
    {
        static::updated(function ($proxy) {
            if ($proxy->wasChanged('status')) {
                ProxyStatusChanged::dispatch($proxy, $proxy->getOriginal('status'));
            }
        });
    }

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

    public function buildProxyUrl()
    {
        $auth = '';

        if ($this->login && $this->password) {
            $auth = "{$this->login}:{$this->password}@";
        }

        return "{$this->type}://{$auth}{$this->host}:{$this->port}";
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $v) {
                $q->where(function ($q) use ($v) {
                    $q->where('host','like',"%$v%")
                    ->orWhere('port','like',"%$v%")
                    ->orWhere('login','like',"%$v%")
                    ->orWhere('password','like',"%$v%");
                });
            })
            ->when($filters['type'] ?? null, fn($q,$v) =>
                $q->where('type',$v)
            )
            ->when($filters['status'] ?? null, fn($q,$v) =>
                $q->where('status',$v));
    }

    public function getRawAttribute(): string
    {
        $auth = '';

        if ($this->login && $this->password) {
            $auth = ":{$this->login}:{$this->password}";
        }

        if ($this->type === 'socks5') {
            return "socks5://{$this->host}:{$this->port}{$auth}";
        }

        return "{$this->host}:{$this->port}{$auth}";
    }

    public function scopeShouldBeChecked($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('checked_at')
            ->orWhereRaw(
                'checked_at + INTERVAL(check_interval - 5) SECOND <= NOW()' // чтобы старт ежеминутного расписания работал отнимаю 5 секунд
            );
        });
    }
}
