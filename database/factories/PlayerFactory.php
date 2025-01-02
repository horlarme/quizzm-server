<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'quiz_id' => QuizFactory::new(),
            'status' => $this->faker->randomElement(Player::Statuses),
        ];
    }

    public function pending(): static
    {
        return $this->state([
            'status' => Player::StatusPending,
        ]);
    }

    public function approved(): static
    {
        return $this->state([
            'status' => Player::StatusApproved,
        ]);
    }

    public function rejected(): static
    {
        return $this->state([
            'status' => Player::StatusRejected,
        ]);
    }
}
