<?php

use App\Models\Quiz;
use App\Models\User;

test('others cannot delete questions', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs(User::factory()->create())
        ->deleteJson(route('quiz.questions.delete', [
            'quiz' => $quiz->id,
            'question' => $quiz->questions->first->id,
        ]))
        ->assertForbidden();
});

test('can delete questions', function () {
    $quiz = Quiz::factory()->draft()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->deleteJson(route('quiz.questions.delete', [
            'quiz' => $quiz->id,
            'question' => $quiz->questions->first->id,
        ]))
        ->assertNoContent();
});

test('cannot delete questions for published quiz', function () {
    $quiz = Quiz::factory()->published()->validQuiz()->create();

    $this->actingAs($quiz->user)
        ->deleteJson(route('quiz.questions.delete', [
            'quiz' => $quiz->id,
            'question' => $quiz->questions->first->id,
        ]))
        ->assertForbidden();
});
