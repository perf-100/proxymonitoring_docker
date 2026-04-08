<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proxy>
 */
class ProxyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'host' => $this->faker->ipv4,
            'port' => $this->faker->numberBetween(1000, 65535),
            'login' => $this->faker->optional()->userName,
            'password' => $this->faker->optional()->password,
            'type' => $this->faker->randomElement(['http', 'https', 'socks4', 'socks5']),
            'status' => $this->faker->randomElement(['unknown', 'working', 'failed']),
            'checked_at' => $this->faker->optional()->dateTime(),
            'check_interval' => $this->faker->numberBetween(60, 600),
            'comment' => $this->faker->optional()->sentence,
        ];
    }
}
