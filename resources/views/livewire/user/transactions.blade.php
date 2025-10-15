@section('title', 'Transaction History')

<div class="mx-auto bg-white dark:bg-gray-800 rounded-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <!-- Header Section -->
        <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <div class="md:flex items-center md:justify-between mb-4 space-y-1">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Transaction History</h1>
                    <p class="text-gray-600 dark:text-gray-400">View and manage all your wallet transactions</p>
                </div>
                <a href="{{ route('user.wallet') }}"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-600 transition-colors text-sm font-medium text-end">
                    <i class="fad fa-wallet mr-2"></i>Back to Wallet
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div
                    class="p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-500 rounded-lg text-center">
                            <i class="fad fa-arrow-up text-white w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total Credits</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ format_price($totalCredits, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-red-500 rounded-lg text-center">
                            <i class="fad fa-arrow-down text-white w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total Debits</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ format_price($totalDebits, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-500 rounded-lg text-center">
                            <i class="fad fa-receipt text-white w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total Transactions</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $totalTransactions }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-200">All Transactions</h2>
                <button wire:click="clearFilters"
                    class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
                    <i class="fad fa-filter-slash mr-1"></i>
                    Clear Filters
                </button>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-forms.input type="text" wire:model.live.debounce.300ms="search" name="search"
                            placeholder="Search transactions...">
                        </x-forms.input>
                    </div>
                    <div>
                        <select wire:model.live="typeFilter"
                            class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                            @foreach ($this->availableServices as $value => $label)
                                <option value="{{ $value }}">{{ str_replace('-', ' ', $label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                            <option value="">All Status</option>
                            <option value="successful">Successful</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Transactions List -->
            <div class="space-y-4">
                @forelse($this->transactions as $transaction)
                    <div
                        class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-all transaction-card">
                        <div class="md:flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 rounded-lg bg-white dark:bg-gray-800 shadow-sm">
                                    <img src="{{ static_asset('services/' . $transaction->image) }}" alt=""
                                        class="w-8 h-8 rounded-full">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        {{-- <span class="font-semibold text-gray-900 dark:text-gray-100 capitalize">
                                            {{ $transaction->type }}
                                        </span> --}}
                                        <span @class([
                                            'px-2 py-1 rounded-md text-xs font-medium border',
                                            'border-green-300 dark:border-green-600 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30' =>
                                                $transaction->status === 'successful',
                                            'border-amber-300 dark:border-amber-600 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30' =>
                                                $transaction->status === 'pending',
                                            'border-red-300 dark:border-red-600 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30' =>
                                                $transaction->status === 'failed',
                                        ])>
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $transaction->message }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:block md:text-right mt-3 md:mt-0 md:ml-4">
                                <div @class([
                                    'text-xl font-bold mb-1',
                                    'text-green-600 dark:text-green-400' => $transaction->type == 'credit',
                                    'text-red-600 dark:text-red-400' => $transaction->type == 'debit',
                                ])>
                                    {{ format_price($transaction->amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Fee: {{ format_price($transaction->charge, 2) }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-mono">TX ID: {{ $transaction->code }}</span>
                                <button onclick="copyToClipboard('{{ $transaction->code }}')"
                                    class="p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition-colors copy-btn">
                                    <i class="fad fa-clone w-3 h-3"></i>
                                </button>
                                <span
                                    class="copied-indicator-{{ $transaction->code }} hidden text-green-600 dark:text-green-400 text-xs font-semibold">
                                    Copied!
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="mb-4">
                            <i class="fad fa-receipt text-gray-400 dark:text-gray-600" style="font-size: 64px;"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">No transactions found
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">You haven't made any transactions yet.</p>
                        <a href="{{ route('user.wallet') }}"
                            class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-600 transition-colors font-medium">
                            <i class="fad fa-wallet mr-2"></i>Add Funds to Wallet
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($this->transactions->hasPages())
                <div class="mt-6">
                    {{ $this->transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.meta')

@section('styles')
    <style>
        .transaction-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
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
