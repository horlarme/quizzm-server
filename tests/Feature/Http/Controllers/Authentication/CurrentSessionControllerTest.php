<?php

use App\Models\User;

test('logged in user profile can be accessed', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('auth.session'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'id',
            'email',
        ]);
});
