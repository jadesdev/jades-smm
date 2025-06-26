<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
});

require __DIR__ . '/auth.php';

// user
Route::prefix('user')->as('user.')->group(function (): void {
    require __DIR__ . '/user.php';
});