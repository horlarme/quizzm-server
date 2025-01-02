<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'quiz_id' => QuizFactory::new(),
            'option_type' => $this->faker->randomElement(Question::OptionTypes),
        ];
    }

    public function text(): QuestionFactory|Factory
    {
        return $this->state([
            'option_type' => Question::OptionTypeText,
        ]);
    }
}
