<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

test('others cannot update questions', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs(User::factory()->create())
        ->patchJson(route('quiz.questions.update', [
            'quiz' => $quiz,
            'question' => $quiz->questions->first->id,
        ]), Question::factory()->make()->toArray())
        ->assertForbidden();
});

test('cannot update questions for a non-draft quiz', function () {
    $quiz = Quiz::factory()->published()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.questions.update', [
            'quiz' => $quiz,
            'question' => $quiz->questions->first->id,
        ]), Question::factory()->make()->toArray())
        ->assertForbidden();
});

test('only owner can update questions', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->patchJson(
            route('quiz.questions.update', [
                'quiz' => $quiz,
                'question' => $quiz->questions->first->id,
            ]),
            array_merge(
                Question::factory()->text()->make()->toArray(),
                [
                    'options' => [
                    ['value' => 'Paris', 'is_correct' => true],
                    ['value' => 'New York', 'is_correct' => false],
                    ['value' => 'London', 'is_correct' => false],
                    ['value' => 'Tokyo', 'is_correct' => false],
                ]]
            )
        )
        ->assertSuccessful()
        ->assertJsonFragment([
            'value' => 'Paris',
        ]);
});

test('questions must have minimum of 4 options', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->patchJson(
            route('quiz.questions.update', [
                'quiz' => $quiz,
                'question' => $quiz->questions->first->id,
            ]),
            array_merge(
                Question::factory()->make()->toArray(),
                ['options' => [
                    ['value' => 'Paris', 'is_correct' => true],
                    ['value' => 'New York', 'is_correct' => false],
                    ['value' => 'London', 'is_correct' => false],
                ]]
            )
        )
        ->assertJsonValidationErrorFor('options');
});

test('questions must have only one correct option', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->patchJson(
            route('quiz.questions.update', [
                'quiz' => $quiz,
                'question' => $quiz->questions->first->id,
            ]),
            array_merge(
                Question::factory()->make()->toArray(),
                ['options' => [
                    ['value' => 'Paris', 'is_correct' => true],
                    ['value' => 'New York', 'is_correct' => true],
                    ['value' => 'London', 'is_correct' => false],
                ]]
            )
        )
        ->assertJsonValidationErrorFor('options');
});
