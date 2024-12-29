<?php

test('unauthenticated users cannot access the create quiz api', function () {
    $this->postJson(route('quizzes.create'))
        ->assertUnauthorized();
});

test('authenticated users can access the create quiz api', function () {
    $this->actingAs(\Database\Factories\UserFactory::new()->create())
        ->postJson(route('quizzes.create'))
        ->assertJsonValidationErrors(['description', 'title', 'thumbnail']);
});

test('quiz creation are automaticallly saved as draft', function () {
    $this->actingAs(\Database\Factories\UserFactory::new()->create())
        ->postJson(
            route('quizzes.create'),
            \Database\Factories\QuizFactory::new()
                ->makeOne()
                ->only(['title', 'description', 'thumbnail'])
        )
        ->assertSuccessful()
        ->assertJson([
            'status' => \App\Models\Quiz::StatusDraft,
        ])
        ->assertJsonStructure([
            'id',
        ]);
});
