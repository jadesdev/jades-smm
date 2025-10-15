<?php

use App\Livewire\Orders\Bulk as OrdersBulk;
use App\Livewire\Orders\Create as OrdersCreate;
use App\Livewire\Orders\Index as OrdersIndex;
use App\Livewire\Support\Create as Support;
use App\Livewire\Support\Ticket as SupportView;
use App\Livewire\User\Dashboard;
use App\Livewire\User\Developer;
use App\Livewire\User\Profile;
use App\Livewire\User\Referrals;
use App\Livewire\User\Services;
use App\Livewire\User\Transactions;
use App\Livewire\User\Wallet;

Route::get('/', Dashboard::class)->name('index');
Route::get('dashboard', Dashboard::class)->name('dashboard');
Route::get('wallet', Wallet::class)->name('wallet');
Route::get('transactions', Transactions::class)->name('transactions');
Route::get('referrals', Referrals::class)->name('referrals');
Route::get('profile', Profile::class)->name('profile');

Route::get('orders', OrdersIndex::class)->name('orders');
Route::get('orders/create', OrdersCreate::class)->name('orders.create');
Route::get('orders/bulk', OrdersBulk::class)->name('orders.bulk');

Route::get('services', Services::class)->name('services');
Route::get('developer', Developer::class)->name('developer');

Route::get('support', Support::class)->name('support');
Route::get('support/create', Support::class)->name('support.create');
Route::get('support/ticket/{code}', SupportView::class)->name('support.view');

Route::get('logout', App\Livewire\Actions\Logout::class)->name('logout');
