<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mmo\Faker\PicsumProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'is_correct' => $this->faker->boolean(),
            'question_id' => QuestionFactory::new(),
            'value' => $this->faker->sentence(),
        ];
    }

    public function image(): OptionFactory|Factory
    {
        return $this->state([
            'value' => PicsumProvider::picsumUrl(),
        ]);
    }
}
