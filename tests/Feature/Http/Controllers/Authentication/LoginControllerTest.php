<?php

use Laravel\Socialite\Facades\Socialite;

it('redirects to Google for authentication', function () {
    Socialite::shouldReceive('driver->stateless->redirect')->andReturn(redirect('https://google.com'));

    $this->get(route('oauth.login', 'google'))
        ->assertRedirect('https://google.com');
});

it('unsupported driver not allowed', function () {
    Socialite::shouldReceive('driver->stateless->redirect')->andReturn(redirect('https://facebook.com'));

    $this->get(route('oauth.login', 'facebook'))->assertBadRequest();
});

it('handles Google callback and logs in user', function () {
    $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
    $googleUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $googleUser->shouldReceive('getName')->andReturn('Test User');
    $googleUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);

    $this->getJson(route('oauth.callback', ['google', 'code' => 'code']))
        ->assertJsonStructure([
            'token', 'user',
        ]);
});

it('redirects to GitHub for authentication', function () {
    Socialite::shouldReceive('driver->stateless->redirect')->andReturn(redirect('https://github.com'));

    $this->get(route('oauth.login', 'github'))
        ->assertRedirect('https://github.com');
});

it('handles GitHub callback and logs in user', function () {
    $githubUser = Mockery::mock('Laravel\Socialite\Two\User');
    $githubUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $githubUser->shouldReceive('getName')->andReturn('Test User');
    $githubUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver->stateless->user')->andReturn($githubUser);

    $this->getJson(route('oauth.callback', ['github', 'code' => 'code']))
        ->assertJsonStructure([
            'token', 'user',
        ]);
});
