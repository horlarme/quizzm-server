<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->middleware('throttle:30,1')
    ->group(function (Router $auth) {

        $auth->get('user', \App\Http\Controllers\Authentication\CurrentSessionController::class)
            ->middleware('auth:sanctum')
            ->name('auth.session');

        $auth->delete('logout', \App\Http\Controllers\Authentication\LogoutController::class)
            ->middleware('auth:sanctum')
            ->name('auth.logout');

        $auth->get('{driver}/callback', \App\Http\Controllers\Authentication\LoginController::class)
            ->name('oauth.callback');

        $auth->get('{driver}', [\App\Http\Controllers\Authentication\LoginController::class, 'redirect'])
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

        $quizzes->get('{quiz}', \App\Http\Controllers\Quizzes\GetController::class)
            ->name('get');

    });
Route::prefix('quizzes/{quiz}/questions')
    ->as('quiz.questions.')
    ->middleware('auth')
    ->group(function (Router $quiz) {
        $quiz->post('', \App\Http\Controllers\Quizzes\Questions\CreateController::class)
            ->name('create');

        $quiz->delete('{question}', \App\Http\Controllers\Quizzes\Questions\DeleteController::class)
            ->scopeBindings()
            ->name('delete');

        $quiz->patch('{question}', \App\Http\Controllers\Quizzes\Questions\UpdateController::class)
            ->scopeBindings()
            ->name('update');
    });

Route::prefix('quizzes/{quiz}/players')
    ->as('quiz.players.')
    ->middleware('auth')
    ->group(function (Router $quiz) {
        $quiz->post('register', \App\Http\Controllers\Quizzes\Players\RegisterController::class)
            ->name('register');

        $quiz->get('', \App\Http\Controllers\Quizzes\Players\ListController::class)
            ->name('list');

        $quiz->patch('{player}', \App\Http\Controllers\Quizzes\Players\UpdateController::class)
            ->name('update');
    });

Route::middleware('auth')
    ->prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get('{user}', \App\Http\Controllers\Users\ShowController::class)
            ->name('show');
    });
