<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramBot>
 */
class TelegramBotFactory extends Factory
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
            
            'bot_token' => $this->faker->regexify('[0-9]{9}:[A-Za-z0-9_-]{35}'),
            'chat_id' => $this->faker->numerify('#########'),
            'status' => $this->faker->randomElement(['active', 'disabled']),
        ];
    }
}
