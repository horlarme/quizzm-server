<?php

use App\Models\Quiz;
use App\Models\User;

test('users can submit answers', function (): void {
    $quiz = Quiz::factory()
        ->published()
        ->userStart()
        ->validQuiz()
        ->withoutRegistration()
        ->create();

    $user = User::factory()->create();
    $question = $quiz->questions->first();

    $this->actingAs($user)
        ->postJson(
            route('quiz.questions.answer', [$quiz, $question, $question->options->first()])
        )
        ->assertSuccessful()
        ->assertJsonStructure(['question'])
        ->assertJsonFragment([
            'title' => $quiz->questions->get(1)->title,
            'value' => $quiz->questions->get(1)->options->get(1)->value,
        ]);
});
test('cannot answer the same question twice', function (): void {
    $quiz = Quiz::factory()
        ->published()
        ->userStart()
        ->validQuiz()
        ->withoutRegistration()
        ->create();

    $user = User::factory()->create();
    $question = $quiz->questions->first();

    \App\Models\Result::factory()
        ->for($user)
        ->for($question->options->first())
        ->create();

    $this->actingAs($user)
        ->postJson(
            route('quiz.questions.answer', [$quiz, $question, $question->options->first()])
        )
        ->assertForbidden();
});
