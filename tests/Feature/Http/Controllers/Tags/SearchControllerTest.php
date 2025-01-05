<?php

use App\Models\Tag;

test('users can search tags', function (): void {
    Tag::factory()->createMany([
        ['name' => 'Laravel'],
        ['name' => 'PHP'],
        ['name' => 'JavaScript'],
    ]);

    $this->getJson(route('tags.search', ['search' => 'lar']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Laravel');
});

test('empty search returns all tags', function (): void {
    Tag::factory()->count(3)->create();

    $this->getJson(route('tags.search'))
        ->assertSuccessful()
        ->assertJsonCount(3, 'data');
});
