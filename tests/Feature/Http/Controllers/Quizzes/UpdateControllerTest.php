<?php

use App\Models\Quiz;

test('only quiz owner can update quiz', function (): void {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs(\App\Models\User::factory()->create())
        ->patchJson(route('quizzes.update', $quiz), [])
        ->assertForbidden();
});

test('only draft quizzes can be updated', function (): void {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->published()->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quizzes.update', $quiz), [])
        ->assertForbidden();
});

test('quiz can be updated', function (): void {
    /** @var Quiz $quiz */
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->patchJson(
            route('quizzes.update', $quiz), array_merge(
                $quizUpdate = Quiz::factory()->make()->toArray(),
                [
                    'tags' => [\App\Models\Tag::factory()->create()->id],
                ]))
        ->assertSuccessful()
        ->assertJsonFragment([
            'title' => $quizUpdate['title'],
            'description' => $quizUpdate['description'],
            'status' => 'draft',
        ]);
});
