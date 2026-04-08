<?php

namespace App\Policies;

use App\Models\Proxy;
use App\Models\User;

class ProxyPolicy
{
    public function create(User $user)
    {
        return true; // любой авторизованный
    }

    public function viewAny(User $user)
    {
        return true; // любой авторизованный
    }
    
    public function view(User $user, Proxy $proxy)
    {
        return $proxy->user_id === $user->id;
    }

    public function update(User $user, Proxy $proxy)
    {
        return $proxy->user_id === $user->id;
    }

    public function delete(User $user, Proxy $proxy)
    {
        return $proxy->user_id === $user->id;
    }
}
