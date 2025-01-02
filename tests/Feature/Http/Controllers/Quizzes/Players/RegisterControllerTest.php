<?php

use App\Models\Player;
use App\Models\Quiz;
use App\Models\User;

test('users cannot register for their own quiz', function () {
    $quiz = Quiz::factory()->published()->create();

    $this->actingAs($quiz->user)
        ->postJson(route('quiz.players.register', $quiz))
        ->assertForbidden();
});

test('users cannot register for draft quizzes', function () {
    $quiz = Quiz::factory()->draft()->create();

    $this->actingAs(User::factory()->create())
        ->postJson(route('quiz.players.register', $quiz))
        ->assertForbidden();
});

test('users can register for published quizzes', function () {
    $quiz = Quiz::factory()->published()->create([
        'require_registration' => true,
        'require_approval' => false,
    ]);

    $this->actingAs($user = User::factory()->create())
        ->postJson(route('quiz.players.register', $quiz))
        ->assertSuccessful()
        ->assertJsonPath('status', Player::StatusApproved);

    expect($quiz->players()->where('user_id', $user->id)->exists())->toBeTrue();
});

test('registration requires approval when configured', function () {
    $quiz = Quiz::factory()->published()->create([
        'require_registration' => true,
        'require_approval' => true,
    ]);

    $this->actingAs($user = User::factory()->create())
        ->postJson(route('quiz.players.register', $quiz))
        ->assertSuccessful()
        ->assertJsonPath('status', Player::StatusPending);
});

test('users cannot register multiple times', function () {
    $quiz = Quiz::factory()->published()->create([
        'require_registration' => true,
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('quiz.players.register', $quiz))
        ->assertSuccessful();

    $this->actingAs($user)
        ->postJson(route('quiz.players.register', $quiz))
        ->assertForbidden();
});
