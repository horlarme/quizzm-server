<?php

use App\Models\Player;
use App\Models\Quiz;
use App\Models\User;

test('only quiz owner can view players', function () {
    $quiz = Quiz::factory()->create();

    $this->actingAs(User::factory()->create())
        ->getJson(route('quiz.players.list', $quiz))
        ->assertForbidden();
});

test('quiz owner can view players', function () {
    $quiz = Quiz::factory()
        ->has(Player::factory()->count(5))
        ->create();

    $this->actingAs($quiz->user)
        ->getJson(route('quiz.players.list', $quiz))
        ->assertSuccessful()
        ->assertJsonCount(5, 'data');
});

test('players list is paginated', function () {
    $quiz = Quiz::factory()
        ->has(Player::factory()->count(20))
        ->create();

    $this->actingAs($quiz->user)
        ->getJson(route('quiz.players.list', $quiz))
        ->assertSuccessful()
        ->assertJsonCount(15, 'data') // Default pagination
        ->assertJsonStructure([
            'meta' => [
                'total',
            ],
        ]);
});
