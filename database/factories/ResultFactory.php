<?php

namespace Database\Factories;

use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    public function definition(): array
    {
        return [
            'option_id' => OptionFactory::new(),
            'user_id' => UserFactory::new(),
            'is_correct' => fn (array $attrs) => Option::query()->where('id', $attrs['option_id'])->value('is_correct'),
        ];
    }
}
