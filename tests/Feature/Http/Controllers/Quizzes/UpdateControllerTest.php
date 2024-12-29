<?php

use App\Models\Quiz;

test('only quiz owner can update quiz', function () {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs(\App\Models\User::factory()->create())
        ->patchJson(route('quizzes.update', $quiz), [])
        ->assertForbidden();
});

test('only draft quizzes can be updated', function () {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->published()->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quizzes.update', $quiz), [])
        ->assertForbidden();
});

test('quiz can be updated', function () {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quizzes.update', $quiz), $quizUpdate = Quiz::factory()->make()->toArray())
        ->assertSuccessful()
        ->assertJsonFragment([
            'title' => $quizUpdate['title'],
            'description' => $quizUpdate['description'],
            'status' => 'draft',
        ]);
});
