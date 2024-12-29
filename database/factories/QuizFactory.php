<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text(100),
            'thumbnail' => $this->faker->imageUrl(),

            'require_registration' => $this->faker->boolean(),
            'require_approval' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(Quiz::Statuses),

            'start_type' => $this->faker->randomElement(Quiz::StartTypes),
            'start_time' => $this->faker->dateTimeBetween('+30minutes', '+1 month'),

            'visibility' => $this->faker->randomElement(Quiz::Visibilities),
        ];
    }

    public function public(): QuizFactory|Factory
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => Quiz::VisibilityPublic,
        ]);
    }

    public function draft(): QuizFactory|Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => Quiz::StatusDraft,
        ]);
    }

    public function published(): QuizFactory|Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => Quiz::StatusPublished,
        ]);
    }
}
