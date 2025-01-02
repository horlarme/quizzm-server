<?php

use App\Models\Player;
use App\Models\Quiz;
use App\Models\User;

test('cannot update player status if quiz does not require registration', function () {
    $quiz = Quiz::factory()->withoutRegistration()->create();
    $player = Player::factory()->pending()->for($quiz)->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => Player::StatusApproved,
        ])
        ->assertForbidden()
        ->assertJsonPath('message', 'This quiz does not require registration approval.');
});

test('only quiz owner can update player status', function () {
    $quiz = Quiz::factory()->requiresRegistration()->create();
    $player = Player::factory()->pending()->for($quiz)->create();

    $this->actingAs(User::factory()->create())
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => Player::StatusApproved,
        ])
        ->assertForbidden();
});

test('quiz owner can approve pending players', function () {
    $quiz = Quiz::factory()->requiresRegistration()->create();
    $player = Player::factory()->pending()->for($quiz)->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => Player::StatusApproved,
        ])
        ->assertSuccessful()
        ->assertJsonPath('status', Player::StatusApproved);

    expect($player->fresh()->status)->toBe(Player::StatusApproved);
});

test('quiz owner can reject pending players', function () {
    $quiz = Quiz::factory()->requiresRegistration()->create();
    $player = Player::factory()->pending()->for($quiz)->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => Player::StatusRejected,
        ])
        ->assertSuccessful()
        ->assertJsonPath('status', Player::StatusRejected);

    expect($player->fresh()->status)->toBe(Player::StatusRejected);
});

test('cannot update non-pending players', function () {
    $quiz = Quiz::factory()->requiresRegistration()->create();
    $player = Player::factory()->approved()->for($quiz)->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => Player::StatusRejected,
        ])
        ->assertForbidden();
});

test('status must be valid', function () {
    $quiz = Quiz::factory()->requiresRegistration()->create();
    $player = Player::factory()->pending()->for($quiz)->create();

    $this->actingAs($quiz->user)
        ->patchJson(route('quiz.players.update', [$quiz, $player]), [
            'status' => 'invalid-status',
        ])
        ->assertUnprocessable();
});
