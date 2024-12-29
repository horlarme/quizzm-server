<?php

test('terminated token cannot be used to access protected routes', function () {
    $user = \App\Models\User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;

    $this
        ->withToken($token)
        ->deleteJson(route('auth.logout'))
        ->assertNoContent();

    \Illuminate\Support\Facades\Auth::guard('sanctum')->forgetUser();

    $this
        ->withToken($token)
        ->getJson(route('auth.session'))
        ->assertUnauthorized();
});
