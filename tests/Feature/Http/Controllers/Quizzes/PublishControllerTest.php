<?php

use App\Models\Question;
use App\Models\Quiz;

test('a draft quiz can be published', function (): void {
    $quiz = Quiz::factory()
        ->draft()
        ->validQuiz()
        ->createOne();

    $this->actingAs($quiz->user)
        ->postJson(route('quizzes.publish', $quiz))
        ->assertSuccessful()
        ->assertJsonFragment([
            'status' => Quiz::StatusPublished,
        ]);

    $quiz->refresh();

    expect($quiz->status)->toBe('published')
        ->and($quiz->published_at)->not->toBeNull();
});

test('a non-draft quiz cannot be published', function (): void {
    $quiz = Quiz::factory()->validQuiz()->published()->create();

    $this->actingAs($quiz->user)
        ->postJson(route('quizzes.publish', $quiz))
        ->assertForbidden();
});

test('a quiz with less than 2 questions cannot be published', function (): void {
    $quiz = Quiz::factory()
        ->published()
        ->has(Question::factory()->count(1))
        ->create();

    $this->actingAs($quiz->user)
        ->postJson(route('quizzes.publish', $quiz))
        ->assertForbidden();
});

test('a quiz with a start time less than 30 minutes from now cannot be published', function (): void {
    $quiz = Quiz::factory()
        ->published()
        ->validQuiz()
        ->create([
            'start_time' => now()->addMinutes(20),
        ]);

    $this->actingAs($quiz->user)
        ->postJson(route('quizzes.publish', $quiz))
        ->assertForbidden();
});
