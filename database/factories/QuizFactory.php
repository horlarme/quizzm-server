<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Mmo\Faker\PicsumProvider;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'thumbnail' => PicsumProvider::picsumUrl(),
            'require_registration' => $this->faker->boolean(),
            'require_approval' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(Quiz::Statuses),
            'published_at' => function (array $attributes) {
                if ($attributes['status'] === Quiz::StatusPublished) {
                    return $this->faker->dateTimeBetween('-1 month', '+1 month');
                }

                return null;
            },
            'start_type' => $this->faker->randomElement(Quiz::StartTypes),
            'start_time' => $this->faker->dateTimeBetween('+30minutes', '+1 month'),

            'visibility' => $this->faker->randomElement(Quiz::Visibilities),
        ];
    }

    public function requiresRegistration(): static
    {
        return $this->state([
            'require_registration' => true,
        ]);
    }

    public function withoutRegistration(): static
    {
        return $this->state([
            'require_registration' => false,
            'require_approval' => false,
        ]);
    }

    public function automaticStart(?string $startTime = null): static
    {
        return $this->state([
            'start_type' => Quiz::StartTypeAutomatic,
            'start_time' => $startTime ?? $this->faker->dateTimeBetween('+30minutes', '+1 month'),
        ]);
    }

    public function manualStart(): static
    {
        return $this->state([
            'start_type' => Quiz::StartTypeManual,
            'start_time' => null,
        ]);
    }

    public function userStart(?string $startTime = null): static
    {
        return $this->state([
            'start_type' => Quiz::StartTypeUser,
            'start_time' => $startTime ?? $this->faker->dateTimeBetween('+30minutes', '+1 month'),
        ]);
    }

    public function private(): static
    {
        return $this->state([
            'visibility' => Quiz::VisibilityPrivate,
        ]);
    }

    public function public(): static
    {
        return $this->state([
            'visibility' => Quiz::VisibilityPublic,
        ]);
    }

    public function published(): static
    {
        return $this->state([
            'status' => Quiz::StatusPublished,
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state([
            'status' => Quiz::StatusDraft,
            'published_at' => null,
        ]);
    }

    public function validQuiz($count = 2): static
    {
        return $this
            ->has(TagFactory::new()->count(5))
            ->has(
                QuestionFactory::new()
                    ->count($count)
                    ->has(Option::factory()->count(4)->sequence(
                        ['is_correct' => true],
                        ['is_correct' => false],
                        ['is_correct' => false],
                        ['is_correct' => false],
                    ))
            );
    }

    public function requiresApproval(bool $required = true): QuizFactory|Factory
    {
        return $this->state([
            'require_approval' => $required,
        ]);
    }
}
