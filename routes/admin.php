<?php

use App\Livewire\Admin\ApiProviderManager;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Dashboard;

Route::get('dashboard', Dashboard::class)->name('dashboard');

Route::get('categories', CategoryManager::class)->name('categories');

Route::get('api-providers', ApiProviderManager::class)->name('api-providers');
