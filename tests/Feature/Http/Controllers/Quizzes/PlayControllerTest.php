<?php

use App\Models\Player;
use App\Models\Quiz;

test('players can play a registered quiz', function () {
    $quiz = Quiz::factory()->validQuiz()->published()->public()->userStart()->create();
    $player = Player::factory()->for($quiz)->approved()->create();

    $this->actingAs($player->user)
        ->postJson(route('quiz.play', $quiz))
        ->assertSuccessful()
        ->assertJsonFragment([
            'total' => 2,
            'answered' => 0,
        ]);
});

test('players can play a quiz they are not approved to play', function () {
    $quiz = Quiz::factory()->validQuiz()
        ->published()
        ->requiresRegistration()
        ->public()
        ->userStart()
        ->create();
    $player = Player::factory()->for($quiz)->rejected()->create();

    $this->actingAs($player->user)
        ->postJson(route('quiz.play', $quiz))
        ->assertForbidden();
});

test('players can play a quiz that is not published', function () {
    $quiz = Quiz::factory()->validQuiz()->draft()->public()->userStart()->create();
    $player = Player::factory()->for($quiz)->approved()->create();

    $this->actingAs($player->user)
        ->postJson(route('quiz.play', $quiz))
        ->assertForbidden();
});

test('players cannot play a quiz they have not registered for', function () {
    $quiz = Quiz::factory()->validQuiz()->draft()->public()->userStart()->create();

    $this->actingAs(\App\Models\User::factory()->createOne())
        ->postJson(route('quiz.play', $quiz))
        ->assertForbidden();
});

test('the next question is returned when the previous question is answered', function () {
    $quiz = Quiz::factory()->validQuiz()->published()->public()->userStart()->create();
    $player = Player::factory()->for($quiz)->approved()->create();

    \App\Models\Result::factory()
        ->for($player->user)
        ->for($quiz->questions->first()->options->first())
        ->createOne();

    $this->actingAs($player->user)
        ->postJson(route('quiz.play', $quiz))
        ->assertSuccessful()
        ->assertJsonFragment([
            'total' => 2,
            'answered' => 1,
            'title' => $quiz->questions->get('1')->title,
            'value' => $quiz->questions->get('1')->options->get(1)->value,
        ])
        ->assertJsonMissingPath('question.options.*.is_correct');
});

test('no more questions are returned when all questions have been answered', function () {
    $quiz = Quiz::factory()->validQuiz()->published()->public()->userStart()->create();
    $player = Player::factory()->for($quiz)->approved()->create();

    $quiz->questions->each(fn (\App\Models\Question $question) => \App\Models\Result::factory()
        ->for($player->user)
        ->for($question->options->random(1)->first())
        ->createOne()
    );

    $this->actingAs($player->user)
        ->postJson(route('quiz.play', $quiz))
        ->assertForbidden();
});
