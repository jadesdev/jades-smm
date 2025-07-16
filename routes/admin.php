<?php

use App\Livewire\Admin\ApiProviderManager;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ServiceManager;

Route::get('dashboard', Dashboard::class)->name('dashboard');

Route::get('categories', CategoryManager::class)->name('categories');

Route::get('api-providers', ApiProviderManager::class)->name('api-providers');

Route::get('services', ServiceManager::class)->name('services');

