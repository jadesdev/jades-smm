<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/terms', function () {
    return view('welcome');
})->name('terms');

Route::get('/dashboard', function () {
    return view('dashboard');
});

require __DIR__.'/auth.php';

// user
Route::prefix('user')->as('user.')->middleware(['auth'])->group(function (): void {
    require __DIR__.'/user.php';
});
