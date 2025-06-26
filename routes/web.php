<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/user', function () {
    return view('user.index');
})->name('user');

Route::get('/user/profile', function () {
    return view('user.profile');
})->name('user.profile');

require __DIR__ . '/auth.php';
