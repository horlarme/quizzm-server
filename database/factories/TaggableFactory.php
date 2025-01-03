<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Taggable>
 */
class TaggableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'taggable_id' => QuizFactory::new(),
            'taggable_type' => 'quiz',
        ];
    }
}
