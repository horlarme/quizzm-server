<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->middleware('throttle:30,1')
    ->group(function (Router $auth) {

        $auth->get('/auth/user', \App\Http\Controllers\Authentication\CurrentSessionController::class)
            ->middleware('auth:sanctum')
            ->name('auth.session');

        $auth->delete('/auth/logout', \App\Http\Controllers\Authentication\LogoutController::class)
            ->middleware('auth:sanctum')
            ->name('auth.logout');

        $auth->get('/auth/{driver}/callback', \App\Http\Controllers\Authentication\LoginController::class)
            ->name('oauth.callback');

        $auth->get('/auth/{driver}', [\App\Http\Controllers\Authentication\LoginController::class, 'redirect'])
            ->name('oauth.login');
    });

Route::prefix('quizzes')
    ->as('quizzes.')
    ->group(function (Router $quizzes) {
        $quizzes->get('/', \App\Http\Controllers\Quizzes\SearchController::class)
            ->name('search');

        $quizzes->post('', \App\Http\Controllers\Quizzes\CreateController::class)
            ->name('create')
            ->middleware('auth');

        $quizzes->patch('{quiz}', \App\Http\Controllers\Quizzes\UpdateController::class)
            ->name('update')
            ->middleware('auth');

        $quizzes->post('{quiz}', \App\Http\Controllers\Quizzes\PublishController::class)
            ->name('publish')
            ->middleware('auth');
    });
