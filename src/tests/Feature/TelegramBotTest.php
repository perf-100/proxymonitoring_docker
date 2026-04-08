<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TelegramBot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TelegramBotTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_own_bots()
    {
        $user = User::factory()->create();

        TelegramBot::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/bots')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_bot()
    {
        Http::fake([
            '*' => Http::response(['ok' => true], 200),
        ]);

        $user = User::factory()->create();

        $payload = [
            'bot_token' => '123456:ABCDEF1234567890abcdef1234567890abcd',
            'chat_id' => '123456789',
            'status' => 'active',
        ];

        $this->actingAs($user)
            ->postJson('/api/bots', $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('telegram_bots', [
            'bot_token' => $payload['bot_token'],
            'chat_id' => $payload['chat_id'],
            'status' => $payload['status'],
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_toggle_bot()
    {
        Http::fake([
            '*' => Http::response(['ok' => true], 200),
        ]);

        $user = User::factory()->create();

        $bot = TelegramBot::factory()->create([
            'user_id' => $user->id,
            'status' => 'disabled',
        ]);

        $this->actingAs($user)
            ->postJson("/api/bots/{$bot->id}/toggle")
            ->assertStatus(204);

        $this->assertDatabaseHas('telegram_bots', [
            'id' => $bot->id,
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_toggle_foreign_bot()
    {
        Http::fake([
            '*' => Http::response(['ok' => true], 200),
        ]);

        $user = User::factory()->create();
        $other = User::factory()->create();

        $bot = TelegramBot::factory()->create([
            'user_id' => $other->id,
        ]);

        $this->actingAs($user)
            ->postJson("/api/bots/{$bot->id}/toggle")
            ->assertStatus(403);
    }

    public function test_user_can_delete_bot()
    {
        $user = User::factory()->create();

        $bot = TelegramBot::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/bots/{$bot->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('telegram_bots', [
            'id' => $bot->id
        ]);
    }

    public function test_user_cannot_delete_foreign_bot()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $bot = TelegramBot::factory()->create([
            'user_id' => $other->id
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/bots/{$bot->id}")
            ->assertStatus(403);
    }
}