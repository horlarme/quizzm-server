<?php

use App\Models\User;

test('unauthenticated users cannot view profiles', function (): void {
    $user = User::factory()->create();

    $this->getJson(route('users.show', $user))
        ->assertUnauthorized();
});

test('authenticated users can view user profiles', function (): void {
    $viewer = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($viewer)
        ->getJson(route('users.show', $user))
        ->assertSuccessful()
        ->assertJson([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar' => $user->avatar,
        ]);
});

test('user email is not exposed in public profile', function (): void {
    $viewer = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($viewer)
        ->getJson(route('users.show', $user))
        ->assertSuccessful()
        ->assertJsonMissing(['email']);
});
