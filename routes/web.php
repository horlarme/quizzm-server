<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', fn () => view('welcome'));

Route::get('/auth/{driver}', fn (string $driver = 'github') => Socialite::driver($driver)->redirect());
