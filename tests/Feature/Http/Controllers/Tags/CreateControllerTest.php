<?php

use App\Models\Tag;

test('unauthenticated users cannot create tags', function (): void {
    $this->postJson(route('tags.create'), [
        'name' => 'Laravel',
    ])->assertUnauthorized();
});

test('authenticated users can create new tags', function (): void {
    $this->actingAs(\App\Models\User::factory()->create())
        ->postJson(route('tags.create'), [
            'name' => 'Laravel',
        ])
        ->assertSuccessful()
        ->assertJsonPath('name', 'Laravel');

    expect(Tag::where('name', 'Laravel')->exists())->toBeTrue();
});

test('creating existing tag returns the existing tag', function (): void {
    $tag = Tag::factory()->create(['name' => 'Laravel']);

    $this->actingAs(\App\Models\User::factory()->create())
        ->postJson(route('tags.create'), [
            'name' => 'Laravel',
        ])
        ->assertSuccessful()
        ->assertJsonPath('id', $tag->id)
        ->assertJsonPath('name', 'Laravel');

    expect(Tag::where('name', 'Laravel')->count())->toBe(1);
});
