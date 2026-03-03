<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bot_token',
        'chat_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, fn($q, $v) =>
            $q->where('chat_id', 'like', "%$v%")
                ->orWhere('bot_token', 'like', "%$v%"))
            ->when($filters['status'] ?? null, fn($q, $v) =>
            $q->where('status', $v));
    }
}
