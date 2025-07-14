@section('title', $metaTitle)
<div class="mx-auto bg-white dark:bg-gray-800 rounded-2xl">
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
                    {{ $showBalance ? format_price($balance, 2) : '••••••' }}
                </span>
                <div class="text-primary-200">Available Balance</div>
            </div>
            <div class="">
                <span class="text-3xl font-bold mb-2">
                    {{ $showBalance ? format_price($referralBalance, 2) : '••••••' }}
                </span>
                <div class="text-primary-200">Referral Balance</div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button wire:click="changeTab('deposit')" @class([
                'flex-1 px-6 py-4 text-lg font-semibold transition-all duration-300 flex items-center justify-center space-x-2',
                'tab-active' => $tab === 'deposit',
                'text-gray-600 hover:text-primary-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-primary-400 dark:hover:bg-gray-800' =>
                    $tab !== 'deposit',
            ])>
                <i class="fad fa-credit-card w-5 h-5"></i>
                <span>Deposit</span>
            </button>

            <button wire:click="changeTab('transactions')" @class([
                'flex-1 px-6 py-4 text-lg font-semibold transition-all duration-300 flex items-center justify-center space-x-2',
                'tab-active' => $tab === 'transactions',
                'text-gray-600 hover:text-primary-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-primary-400 dark:hover:bg-gray-800' =>
                    $tab !== 'transactions',
            ])>
                <i class="fad fa-arrow-up-right w-5 h-5"></i>
                <span>Transactions</span>
            </button>
        </div>

        <div class="p-8">
            @if ($tab === 'deposit')
                <div class="mx-auto">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Add Funds to Your Wallet</h2>

                    <form wire:submit="deposit">
                        <div class="space-y-6">
                            <div class="md:flex md:space-x-6 space-y-6 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <x-forms.input type="number" label="Amount" name="amount" wire:model.live="amount"
                                        placeholder="0.00" min="1" step="0.01" class="py-4" />
                                </div>

                                <div class="w-full md:w-1/2">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">
                                        Select Payment Method
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 mb-2 gap-4">
                                        @foreach ($gateways as $key => $gateway)
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="gateway" value="{{ $key }}"
                                                    wire:model.live="selectedGateway" class="sr-only peer">
                                                <div @class([
                                                    'p-4 border-2 rounded-xl transition-all duration-300 hover:shadow-md',
                                                    'peer-checked:border-primary-500 peer-checked:dark:border-primary-400 peer-checked:bg-primary-50 peer-checked:dark:bg-primary-900/50 peer-checked:shadow-lg peer-checked:scale-105 peer-checked:gateway-selected',
                                                    'border-gray-200 hover:border-primary-300 dark:border-gray-700 dark:hover:border-primary-500',
                                                ])>
                                                    <div class="flex items-center space-x-3 ggs">
                                                        <span class="text-2xl">
                                                            <img src="{{ static_asset('payments/' . $gateway['image']) }}"
                                                                alt="{{ $gateway['name'] }}" class="w-6 h-6">
                                                        </span>
                                                        <div class="flex-1">
                                                            <div
                                                                class="font-semibold text-gray-900 dark:text-gray-200 peer-checked:text-primary-600 peer-checked:dark:text-primary-400">
                                                                {{ $gateway['name'] }}
                                                            </div>
                                                            <div
                                                                class="text-sm text-gray-500 dark:text-gray-300 peer-checked:text-primary-500 peer-checked:dark:text-primary-300">
                                                                Fee: {{ $gateway['fee'] }}
                                                            </div>
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
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-200">Transaction History</h2>
                        <button wire:click="clearFilters"
                            class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
                            <i class="fad fa-filter-slash mr-1"></i>
                            <span class="md:block hidden">Clear Filters</span>
                        </button>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-3 mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-forms.input type="text" wire:model.live.debounce.300ms="search" name="search"
                                    placeholder="Search transactions...">
                                </x-forms.input>
                            </div>
                            <div>
                                <select wire:model.live="typeFilter"
                                    class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                                    @foreach ($this->availableServices as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="statusFilter"
                                    class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
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
                                class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5 px-3 hover:bg-gray-100 dark:hover:bg-gray-800 border border-primary-300 transition-all transaction-card">
                                <div class="md:flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div @class(['p-1 rounded-full'])>
                                            <img src="{{ static_asset('services/' . $transaction->image) }}"
                                                alt="" class="w-6 h-6 rounded-full">
                                        </div>
                                        <div class="flex items-center justify-between space-x-2">
                                            <span
                                                class="font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $transaction->type }}</span>
                                            <span @class([
                                                'text-right px-2 py-1 rounded-md text-xs font-medium border flex items-center',
                                                'border-green-300 text-green-600' => $transaction->status === 'successful',
                                                'border-amber-300 text-amber-600' => $transaction->status === 'pending',
                                                'border-red-300 text-red-600' => $transaction->status === 'failed',
                                            ])>
                                                <span class="ml-1">{{ $transaction->status }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between md:grid md:text-right my-2 md:my-0">
                                        <div @class([
                                            'text-lg font-semibold',
                                            'text-green-600' => $transaction->type == 'credit',
                                            'text-red-600' => $transaction->type == 'debit',
                                        ])>
                                            {{ format_price($transaction->amount, 2) }}
                                        </div>
                                        <div class="text-md text-gray-500 dark:text-gray-300">
                                            Fee: {{ format_price($transaction->fee, 2) }}</div>
                                    </div>
                                </div>
                                <p class="dark:text-gray-200">{{ $transaction->message }}</p>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-300">
                                        <span>TX ID: {{ $transaction->code }}</span>
                                        <button onclick="copyToClipboard('{{ $transaction->code }}')"
                                            class="p-1 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition-colors copy-btn">
                                            <i class="fad fa-clone w-3 h-3 dark:text-gray-200"></i>
                                        </button>
                                        <span
                                            class="copied-indicator-{{ $transaction->code }} hidden text-green-600 text-xs">Copied!</span>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ $transaction->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                No transactions found.
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4 ">
                        {{ $this->transactions->links() }}
                    </div>
                </div>
            @endif
        </div>

        <div wire:loading wire:target="deposit">
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md mx-4 text-center shadow-2xl">
                    <div class="mb-6">
                        <div
                            class="mx-auto w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mb-4">
                            <svg class="animate-spin h-8 w-8 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Processing Payment
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">Please wait while we redirect you to the
                            payment gateway...</p>
                    </div>

                    <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></div>
                            <span>Preparing your payment</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"
                                style="animation-delay: 0.2s">
                            </div>
                            <span>Connecting to payment gateway</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"
                                style="animation-delay: 0.4s">
                            </div>
                            <span>Redirecting...</span>
                        </div>
                    </div>

                    <div class="mt-6 text-xs text-gray-400 dark:text-gray-500">
                        Do not close this window or refresh the page
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.meta')

@section('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, var(--color-primary-400) 0%, var(--color-primary-800) 100%);
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

        .dark .tab-active {
            border-bottom: 3px solid var(--color-primary-400);
            background: #263050;
            color: var(--color-primary-400);
        }

        .gateway-selected {
            border-color: var(--color-primary-500) !important;
            background: #eef2ff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dark .gateway-selected {
            border-color: var(--color-primary-400) !important;
            background: #292c35 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
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
@endpush
