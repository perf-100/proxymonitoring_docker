<?php

namespace App\Policies;

use App\Models\Proxy;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProxyPolicy
{
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
