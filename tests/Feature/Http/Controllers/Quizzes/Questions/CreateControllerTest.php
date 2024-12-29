<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

test('others cannot create questions', function () {
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs(User::factory()->create())
        ->postJson(route('quiz.questions.create', $quiz), Question::factory()->make()->toArray())
        ->assertForbidden();
});

test('cannot create questions for a non-draft quiz', function () {
    $quiz = Quiz::factory()->published()->create();

    $this->actingAs($quiz->user)
        ->postJson(route('quiz.questions.create', $quiz), Question::factory()->make()->toArray())
        ->assertForbidden();
});

test('only owner can create questions', function () {
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->postJson(
            route('quiz.questions.create', $quiz),
            array_merge(
                Question::factory()->make()->toArray(),
                ['options' => [
                    ['value' => 'Paris', 'is_correct' => true],
                    ['value' => 'New York', 'is_correct' => false],
                    ['value' => 'London', 'is_correct' => false],
                    ['value' => 'Tokyo', 'is_correct' => false],
                ]]
            )
        )
        ->assertSuccessful();
});

test('questions must have minimum of 4 options', function () {
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->postJson(
            route('quiz.questions.create', $quiz),
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
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->postJson(
            route('quiz.questions.create', $quiz),
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
