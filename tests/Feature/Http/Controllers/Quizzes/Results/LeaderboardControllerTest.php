<?php

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;

test('quiz leaderboard shows correct rankings', function () {
    $quiz = Quiz::factory()
        ->published()
        ->validQuiz(4)
        ->create();

    $users = User::factory(3)->create();

    // User 1: 3/4 correct
    createResults($quiz, $users[0], [true, true, true, false]);

    // User 2: 2/4 correct
    createResults($quiz, $users[1], [true, true, false, false]);

    // User 3: 1/4 correct
    createResults($quiz, $users[2], [true, false, false, false]);

    $this->getJson(route('quizzes.leaderboard', $quiz))
        ->assertSuccessful()
        ->assertJsonCount(3, 'data')
        ->assertJsonFragment(['score' => 75])
        ->assertJsonFragment(['score' => 25])
        ->assertJsonFragment(['score' => 50]);
});

function createResults($quiz, $user, array $correctness): void
{
    foreach ($quiz->questions as $i => $question) {
        $option = $question->options()->where('is_correct', $correctness[$i])->first();
        Result::factory()->create([
            'user_id' => $user->id,
            'option_id' => $option->id,
        ]);
    }
}
