<?php

use Database\Factories\TagFactory;

test('unauthenticated users cannot access the create quiz api', function (): void {
    $this->postJson(route('quizzes.create'))
        ->assertUnauthorized();
});

test('authenticated users can access the create quiz api', function (): void {
    $this->actingAs(\Database\Factories\UserFactory::new()->create())
        ->postJson(route('quizzes.create'))
        ->assertJsonValidationErrors(['description', 'title', 'thumbnail', 'tags']);
});

test('quiz creation requires at least one tag', function (): void {
    $this->actingAs(\Database\Factories\UserFactory::new()->create())
        ->postJson(
            route('quizzes.create'),
            \Database\Factories\QuizFactory::new()
                ->makeOne()
                ->only(['title', 'description', 'thumbnail'])
        )
        ->assertJsonValidationErrors(['tags']);
});

test('quiz creation are automatically saved as draft', function (): void {
    $tag = TagFactory::new()->createOne();

    $this->actingAs(\Database\Factories\UserFactory::new()->create())
        ->postJson(
            route('quizzes.create'),
            array_merge(
                \Database\Factories\QuizFactory::new()
                    ->makeOne()
                    ->only(['title', 'description', 'thumbnail']),
                ['tags' => [$tag->id]]
            )
        )
        ->assertSuccessful()
        ->assertJson([
            'status' => \App\Models\Quiz::StatusDraft,
        ])
        ->assertJsonStructure([
            'id',
        ]);
});
