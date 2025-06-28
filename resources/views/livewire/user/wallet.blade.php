@section('title', $metaTitle)
<div class="mx-auto bg-white rounded-2xl">
    <!-- Balance Card -->
    <div class="gradient-bg rounded-2xl p-8 text-white mb-8 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <i class="fad fa-wallet w-8 h-8"></i>
                <h1 class="text-2xl font-bold">Wallet Balance</h1>
            </div>
            <button wire:click="toggleBalance" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                <i class="fad {{ $showBalance ? 'fa-eye' : 'fa-eye-slash' }} w-5 h-5"></i>
            </button>
        </div>
        <div class="md:flex justify-between">
            <div class="mb-2">
                <span class="text-3xl font-bold mb-2">
                    {{ $showBalance ? '$' . number_format($balance, 2) : '••••••' }}
                </span>
                <div class="text-primary-200">Available Balance</div>
            </div>
            <div class="">
                <span class="text-3xl font-bold mb-2">
                    {{ $showBalance ? '$' . number_format($referralBalance, 2) : '••••••' }}
                </span>
                <div class="text-primary-200">Referral Balance</div>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tab Navigation & Content -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Tab Headers -->
        <div class="flex border-b border-gray-200">
            <button wire:click="changeTab('deposit')" @class([
                'flex-1 px-6 py-4 text-lg font-semibold transition-all duration-300 flex items-center justify-center space-x-2',
                'tab-active' => $activeTab === 'deposit',
                'text-gray-600 hover:text-primary-600 hover:bg-gray-50' =>
                    $activeTab !== 'deposit',
            ])>
                <i class="fad fa-credit-card w-5 h-5"></i>
                <span>Deposit</span>
            </button>

            <button wire:click="changeTab('transactions')" @class([
                'flex-1 px-6 py-4 text-lg font-semibold transition-all duration-300 flex items-center justify-center space-x-2',
                'tab-active' => $activeTab === 'transactions',
                'text-gray-600 hover:text-primary-600 hover:bg-gray-50' =>
                    $activeTab !== 'transactions',
            ])>
                <i class="fad fa-arrow-up-right w-5 h-5"></i>
                <span>Transactions</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            <!-- Deposit Tab Content -->
            @if ($activeTab === 'deposit')
                <div class="mx-auto">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Add Funds to Your Wallet</h2>

                    <form wire:submit="deposit">
                        <div class="space-y-6">
                            <div class="md:flex md:space-x-6 space-y-6 md:space-y-0">
                                <!-- Amount Input -->
                                <div class="w-full md:w-1/2">
                                    <x-forms.input type="number" label="Amount" name="amount" wire:model.live="amount"
                                        placeholder="0.00" min="1" step="0.01" class="py-4" />
                                </div>

                                <!-- Gateway Selection -->
                                <div class="w-full md:w-1/2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                                        Select Payment Method
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 mb-2 gap-4">
                                        @foreach ($gateways as $key => $gateway)
                                            <label wire:click="selectGateway('{{ $key }}')"
                                                @class([
                                                    'relative cursor-pointer p-4 border-2 rounded-xl transition-all hover:shadow-md',
                                                    'gateway-selected border-primary-500 bg-primary-50' =>
                                                        $selectedGateway === $key,
                                                    'border-gray-200 hover:border-primary-300' => $selectedGateway !== $key,
                                                ])>
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-2xl"><i class="{{ $gateway['icon'] }}"></i></span>
                                                    <div class="flex-1">
                                                        <div class="font-semibold text-gray-900">{{ $gateway['name'] }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">Fee: {{ $gateway['fee'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('selectedGateway')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" @disabled(!$this->isDepositValid) @class([
                                'w-full font-semibold py-4 px-6 rounded-xl transition-all duration-200',
                                'bg-primary text-white hover:shadow-lg transform hover:scale-105' =>
                                    $this->isDepositValid,
                                'bg-primary-300 text-gray-500 cursor-not-allowed' => !$this->isDepositValid,
                            ])>
                                {{ $this->depositButtonText }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Transactions Tab Content -->
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Transaction History</h2>
                        <button wire:click="clearFilters"
                            class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            <i class="fad fa-filter-slash mr-1"></i>
                            <span class="md:block hidden">Clear Filters</span>
                        </button>
                    </div>
                    <!-- Filters Section -->
                    <div class="bg-gray-50 rounded-xl p-3 mb-6 space-y-4">
                        <!-- Search and Quick Filters Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search -->
                            <div>
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Search transactions..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <select wire:model.live="typeFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <option value="">All Types</option>
                                    <option value="deposit">Deposit</option>
                                    <option value="withdraw">Withdraw</option>
                                    <option value="referral">Referral</option>
                                    <option value="chargeback">Chargeback</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <select wire:model.live="statusFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <option value="">All Status</option>
                                    <option value="successful">Successful</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($this->transactions as $transaction)
                            <div
                                class="bg-gray-50 rounded-xl p-5 px-3 hover:bg-gray-100 border border-primary-300 transition-all transaction-card">
                                <div class="md:flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div @class([
                                            'p-3 rounded-full',
                                            'bg-green-100' => $transaction['status'] === 'successful',
                                            'bg-yellow-100' => $transaction['status'] === 'pending',
                                            'bg-red-100' => $transaction['status'] === 'failed',
                                        ])>
                                            @php
                                                $iconClass = match ($transaction['type']) {
                                                    'deposit' => 'fa-arrow-down-left',
                                                    'withdraw' => 'fa-arrow-right-left',
                                                    'chargeback' => 'fa-arrow-down-left',
                                                    'referral' => 'fa-credit-card',
                                                    default => 'fa-money-bill',
                                                };
                                                $iconColor = match ($transaction['status']) {
                                                    'successful' => 'text-green-600',
                                                    'pending' => 'text-yellow-600',
                                                    'failed' => 'text-red-600',
                                                    default => 'text-gray-600',
                                                };
                                            @endphp
                                            <i class="fad {{ $iconClass }} w-5 h-5 {{ $iconColor }}"></i>
                                        </div>
                                        <div class="flex items-center justify-between space-x-2">
                                            <span
                                                class="font-semibold text-gray-900 capitalize">{{ $transaction['type'] }}</span>
                                            <span @class([
                                                'text-right px-2 py-1 rounded-full text-xs font-medium border flex items-center',
                                                'border-green-200' => $transaction['status'] === 'successful',
                                                'border-yellow-200' => $transaction['status'] === 'pending',
                                                'border-red-200' => $transaction['status'] === 'failed',
                                            ])>
                                                @php
                                                    $statusIcon = match ($transaction['status']) {
                                                        'successful' => 'fa-money-bill',
                                                        'pending' => 'fa-money-bill',
                                                        'failed' => 'fa-money-bill',
                                                        default => 'fa-money-bill',
                                                    };
                                                @endphp
                                                <i class="fad {{ $statusIcon }}"></i>
                                                <span class="ml-1">{{ $transaction['status'] }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between md:grid md:text-right my-2 md:my-0">
                                        <div @class([
                                            'text-lg font-semibold',
                                            'text-green-600' => $transaction['status'] === 'successful',
                                            'text-yellow-600' => $transaction['status'] === 'pending',
                                            'text-red-600' => $transaction['status'] === 'failed',
                                        ])>
                                            ${{ number_format($transaction['amount'], 0) }}
                                        </div>
                                        <div class="text-md text-gray-500">Fee:
                                            ${{ number_format($transaction['fee'], 2) }}</div>
                                    </div>
                                </div>
                                <p>{{ $transaction['description'] }}</p>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <span>TX ID: {{ $transaction['id'] }}</span>
                                        <button onclick="copyToClipboard('{{ $transaction['id'] }}')"
                                            class="p-1 hover:bg-gray-200 rounded transition-colors copy-btn">
                                            <i class="fad fa-clone w-3 h-3"></i>
                                        </button>
                                        <span
                                            class="copied-indicator-{{ $transaction['id'] }} hidden text-green-600 text-xs">Copied!</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $transaction['date'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                No transactions found.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.meta')

@section('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-800) 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .tab-active {
            border-bottom: 3px solid var(--color-primary-500);
            background: #eef2ff;
            color: var(--color-primary-500);
        }

        .gateway-selected {
            border-color: var(--color-primary-500) !important;
            background: #eef2ff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show copied indicator
            const indicator = document.querySelector('.copied-indicator-' + text);
            if (indicator) {
                indicator.classList.remove('hidden');
                setTimeout(() => {
                    indicator.classList.add('hidden');
                }, 2000);
            }
        });
    }
</script>
