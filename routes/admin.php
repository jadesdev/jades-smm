<?php

use App\Http\Controllers\SettingsController;
use App\Livewire\Admin\ApiProviderManager;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\GeneralSettings;
use App\Livewire\Admin\ServiceForm;
use App\Livewire\Admin\ServiceManager;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Admin\User\View as UserView;
use App\Livewire\Admin\User\Settings as UserSettings;


Route::get('dashboard', Dashboard::class)->name('dashboard');

Route::get('categories', CategoryManager::class)->name('categories');

Route::get('api-providers', ApiProviderManager::class)->name('api-providers');

Route::get('services', ServiceManager::class)->name('services');
Route::get('services/create', ServiceForm::class)->name('services.create');
Route::get('services/{id}/edit', ServiceForm::class)->name('services.edit');

Route::get('users', UserIndex::class)->name('users');
Route::get('users/{id}/view', UserView::class)->name('users.view');
Route::get('users/{id}/login', UserView::class)->name('users.login');
Route::get('users/settings', UserSettings::class)->name('users.settings');

Route::get('orders', UserIndex::class)->name('orders.index');
Route::get('transactions', UserIndex::class)->name('transactions.index');
Route::get('orders', UserIndex::class)->name('orders.index');

// Settings
Route::get('settings/{type?}', GeneralSettings::class)->name('settings');
Route::get('settings/payment', GeneralSettings::class)->name('settings.payment');
Route::get('settings/features', GeneralSettings::class)->name('settings.features');

Route::controller(SettingsController::class)->as('settings.')->prefix('settings')->group(function (): void {
    Route::post('/update', 'update')->name('update');
    Route::post('/system', 'systemUpdate')->name('sys_settings');
    Route::post('/system/store', 'storeSettings')->name('store_settings');
    Route::post('env_key', 'envkeyUpdate')->name('env_key');
});