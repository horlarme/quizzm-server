<?php

test('terminated token cannot be used to access protected routes', function (): void {
    $user = \App\Models\User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;

    $this
        ->withToken($token)
        ->deleteJson(route(\Illuminate\Auth\Events\Logout::class))
        ->assertNoContent();

    \Illuminate\Support\Facades\Auth::guard('sanctum')->forgetUser();

    $this
        ->withToken($token)
        ->getJson(route('auth.session'))
        ->assertUnauthorized();
});
