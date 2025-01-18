<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mmo\Faker\PicsumProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'avatar' => PicsumProvider::picsumUrl(),
        ];
    }
}
