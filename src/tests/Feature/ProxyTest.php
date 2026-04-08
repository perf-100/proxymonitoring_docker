<?php

namespace Tests\Feature;

use App\Models\Proxy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ProxyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_own_proxies()
    {
        $user = User::factory()->create();

        Proxy::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/proxies')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_proxy()
    {
        $user = User::factory()->create();
        $proxy = Proxy::factory()->make();

        $this->actingAs($user)
            ->postJson('/api/proxies', [
                'proxy_string' => $proxy->buildProxyUrl(),
                'check_interval' => 300,
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('proxies', [
            'host' => $proxy->host,
            'port' => $proxy->port,
        ]);
    }

    public function test_user_can_update_own_proxy()
    {
        $user = User::factory()->create();

        $proxy = Proxy::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->putJson("/api/proxies/{$proxy->id}", [
                'proxy_string' => "1.1.1.1:8080",
                'check_interval' => 300,
            ])
            ->assertStatus(204);
    }

    public function test_user_cannot_update_foreign_proxy()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $proxy = Proxy::factory()->create([
            'user_id' => $other->id
        ]);

        $this->actingAs($user)
            ->putJson("/api/proxies/{$proxy->id}", [
                'proxy_string' => "1.1.1.1:8080",
                'check_interval' => 300,
            ])
            ->assertStatus(403);
    }

    public function test_user_can_delete_own_proxy()
    {
        $user = User::factory()->create();

        $proxy = Proxy::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/proxies/{$proxy->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('proxies', [
            'id' => $proxy->id
        ]);
    }

    public function test_user_cannot_delete_foreign_proxy()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $proxy = Proxy::factory()->create([
            'user_id' => $other->id
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/proxies/{$proxy->id}")
            ->assertStatus(403);
    }

    public function test_user_can_trigger_check()
    {
        Http::fake([
            '*' => Http::response(['ip' => '1.1.1.1'], 200),
        ]);

        $user = User::factory()->create();

        $proxy = Proxy::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->postJson("/api/proxies/{$proxy->id}/check")
            ->assertStatus(202);
    }
}