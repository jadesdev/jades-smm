<?php

use App\Http\Controllers\CronController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Services;
use App\Livewire\User\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Route::controller(HomeController::class)->group(function (): void {
    Route::get('/', 'index')->name('home');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/api-docs', 'apiDocs')->name('api-docs');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/how-it-works', 'howItWorks')->name('how-it-works');
});
Route::get('services', Services::class)->name('services');

Route::get('user/dashboard', Dashboard::class)->name('dashboard');
require __DIR__.'/auth.php';

// queue
Route::get('queue-work', function (Request $request) {
    if ($request->query('key') !== env('CRON_SECRET')) {
        abort(403, 'Unauthorized');
    }

    return Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

// Cron job
Route::get('/cron-job', function (Request $request) {
    if ($request->query('key') !== env('CRON_SECRET')) {
        abort(403, 'Unauthorized');
    }

    return app(CronController::class)->handle($request);
})->name('cron');

// user
Route::prefix('user')->as('user.')->middleware(['auth', 'user'])->group(function (): void {
    require __DIR__.'/user.php';
});

// Admin
Route::prefix('admin')->as('admin.')->middleware(['admin'])->group(function (): void {
    require __DIR__.'/admin.php';
});

// Payment Callback
Route::controller(PaymentController::class)->prefix('payment')->group(function (): void {
    Route::any('/paystack', 'paystackSuccess')->name('paystack.success');
    Route::any('/flutter', 'flutterSuccess')->name('flutter.success');
    Route::post('/cryptomus', 'cryptomusSuccess')->name('cryptomus.success');
    Route::get('/paypal', 'paypalSuccess')->name('paypal.success');
    Route::get('/paypal-cancel', 'paypalError')->name('paypal.cancel');
    Route::get('/korapay-success', 'korapaySuccess')->name('korapay.success');
});
