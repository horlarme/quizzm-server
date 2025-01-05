<?php

use App\Models\Quiz;

test('fetching a single quiz returns the correct data', function (): void {
    $quiz = Quiz::factory()->validQuiz()->public()->published()->create();

    $this->getJson(route('quizzes.get', $quiz))
        ->assertSuccessful()
        ->assertJsonFragment([
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'questions_count' => $quiz->questions->count(),
        ])
        ->assertJsonFragment([
            'id' => $quiz->user->id,
        ]);
});

test('only owner can fetch a draft quiz', function (): void {
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs($quiz->user)
        ->getJson(route('quizzes.get', $quiz))
        ->assertSuccessful();

    \Illuminate\Support\Facades\Auth::guard('sanctum')->forgetUser();

    $this->actingAs(\App\Models\User::factory()->create())
        ->getJson(route('quizzes.get', $quiz))
        ->assertForbidden();
});

test('fetching a non-existent quiz returns a 404 error', function (): void {
    $this->getJson(route('quizzes.get', 999))
        ->assertNotFound();
});

test('only owner can see correct options for a quiz', function (): void {
    $quiz = Quiz::factory()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->getJson(route('quizzes.get', $quiz))
        ->assertJsonFragment([
            'is_correct' => true,
        ])
        ->assertJsonFragment([
            'is_correct' => false,
        ]);

    \Illuminate\Support\Facades\Auth::guard('sanctum')->forgetUser();

    $this->actingAs(\App\Models\User::factory()->create())
        ->getJson(route('quizzes.get', $quiz))
        ->assertJsonMissingPath('questions.0.options.0.is_correct')
        ->assertJsonMissingPath('questions.1.options.1.is_correct');
});
