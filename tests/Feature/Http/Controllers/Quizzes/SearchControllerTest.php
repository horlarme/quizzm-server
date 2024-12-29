<?php

test('unauthentic user can access search quiz', function () {
    \App\Models\Quiz::factory(20)
        ->public()
        ->create([]);

    $this->getJson(route('quizzes.search'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'data', 'meta',
        ])
        ->assertJsonFragment([
            'total' => 20,
        ]);
});

test('private quizzes are not included in search results', function () {
    \App\Models\Quiz::factory(40)
        ->sequence([
            'visibility' => \App\Models\Quiz::VisibilityPublic,
        ], [
            'visibility' => \App\Models\Quiz::VisibilityPrivate,
        ])
        ->create();

    $this->getJson(route('quizzes.search'))
        ->assertSuccessful()
        ->assertJsonFragment([
            'total' => 20,
        ]);
});
