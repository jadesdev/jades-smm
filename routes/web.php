<?php

use App\Http\Controllers\PaymentController;
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

// cron jobs
Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

// Cron job
Route::get('/cron-job', [App\Http\Controllers\CronController::class, 'handle'])->name('cron');

// user
Route::prefix('user')->as('user.')->middleware(['auth'])->group(function (): void {
    require __DIR__.'/user.php';
});

// Admin
Route::prefix('admin')->as('admin.')->group(function (): void {
    require __DIR__.'/admin.php';
});

// Payment Callback
Route::controller(PaymentController::class)->prefix('payment')->group(function (): void {
    Route::any('/paystack', 'paystackSuccess')->name('paystack.success');
    Route::any('/flutter', 'flutterSuccess')->name('flutter.success');
    Route::post('/cryptomus', 'cryptomusSuccess')->name('cryptomus.success');
    Route::get('/paypal', 'paypalSuccess')->name('paypal.success');
    Route::get('/paypal-cancel', 'paypalError')->name('paypal.cancel');
});
