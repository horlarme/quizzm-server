<?php

test('unauthentic user can access search quiz', function () {
    \App\Models\Quiz::factory(20)
        ->public()
        ->published()
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
    \App\Models\Quiz::factory(4)
        ->published()
        ->sequence([
            'visibility' => \App\Models\Quiz::VisibilityPublic,
        ], [
            'visibility' => \App\Models\Quiz::VisibilityPrivate,
        ])
        ->create();

    $this->getJson(route('quizzes.search'))
        ->assertSuccessful()
        ->assertJsonFragment([
            'total' => 2,
        ]);
});

test('only published quizzes are included in search results', function () {
    \App\Models\Quiz::factory(2)
        ->public()
        ->sequence([
            'status' => \App\Models\Quiz::StatusDraft,
        ], [
            'status' => \App\Models\Quiz::StatusPublished,
        ])
        ->create();

    $this->getJson(route('quizzes.search'))
        ->assertSuccessful()
        ->assertJsonFragment([
            'total' => 1,
        ]);
});
