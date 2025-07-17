<?php

use App\Livewire\Admin\ApiProviderManager;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ServiceForm;
use App\Livewire\Admin\ServiceManager;

Route::get('dashboard', Dashboard::class)->name('dashboard');

Route::get('categories', CategoryManager::class)->name('categories');

Route::get('api-providers', ApiProviderManager::class)->name('api-providers');

Route::get('services', ServiceManager::class)->name('services');
Route::get('services/create', ServiceForm::class)->name('services.create');
Route::get('services/{id}/edit', ServiceForm::class)->name('services.edit');


