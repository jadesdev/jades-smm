@section('title', $metaTitle)
<div class="mx-auto bg-white dark:bg-gray-800 rounded-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <!-- Header Section -->
            <div class="md:flex space-y-1 items-center md:justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">My Wallet</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage your wallet, balances and deposits</p>
                </div>
                <a href="{{ route('user.transactions') }}"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-600 transition-colors text-sm font-medium">
                    <i class="fad fa-history mr-2"></i>View Transactions
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <!-- Available Balance -->
                <div
                    class="p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <i class="fad fa-wallet text-white w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Available Balance</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $showBalance ? format_price($balance, 2) : '••••••' }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="toggleBalance"
                        class="p-2 hover:bg-green-200/50 dark:hover:bg-green-800/40 rounded-lg transition-colors">
                        <i
                            class="fad {{ $showBalance ? 'fa-eye-slash' : 'fa-eye' }} text-green-600 dark:text-green-400"></i>
                    </button>
                </div>

                <!-- Referral Balance -->
                <div
                    class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <i class="fad fa-user-friends text-white w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Referral Balance</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $showBalance ? format_price($referralBalance, 2) : '••••••' }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="toggleBalance"
                        class="p-2 hover:bg-blue-200/50 dark:hover:bg-blue-800/40 rounded-lg transition-colors">
                        <i
                            class="fad {{ $showBalance ? 'fa-eye-slash' : 'fa-eye' }} text-blue-600 dark:text-blue-400"></i>
                    </button>
                </div>
            </div>

        </div>
        <div class="p-8">
            <!-- Account Details Section -->
            <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <i class="fad fa-university text-lg text-primary-600 dark:text-primary-400 w-6 h-6"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Bank Account</h3>
                    </div>
                    @if (!$hasAccount)
                        <x-button variant="primary" x-data
                            @click="$dispatch('open-modal', { name: 'create-bankAccount-modal' })">
                            Add Account
                        </x-button>

                        {{-- Create Account modal --}}
                        <x-modal name="create-bankAccount-modal" title="Create Bank Account" persistent="true">
                            <p class="text-gray-600 dark:text-gray-300">
                                Provide your ID (BVN /NIN) as required by CBN
                            </p>
                            <form action="" class="mt-4">
                                <x-forms.select label="ID Type" name="kyc_type" id="kyc_type" wire:model="kyc_type">
                                    <option value="bvn">BVN</option>
                                    {{-- <option value="nin">NIN</option> --}}
                                </x-forms.select>
                                <x-forms.input type="number" label="ID Number" name="kyc_number" id="kyc_number"
                                    placeholder="ID Number" wire:model="kyc_number" />

                                <div class="text-end">
                                    <x-button variant="primary" wire:click="generateAccount">Generate Account</x-button>
                                </div>
                            </form>
                        </x-modal>
                    @endif
                </div>

                @if ($hasAccount)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Bank Name</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $bankAccount->bank_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Number</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $bankAccount->number ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Name</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $bankAccount->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        No bank account generated yet. Generate one to receive payments.
                    </p>
                @endif
            </div>

            <!-- Deposit Section -->
            <div class="mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Add Funds to Your Wallet</h2>

                <form wire:submit="deposit">
                    <div class="space-y-6">
                        <div class="md:flex md:space-x-6 space-y-6 md:space-y-0">
                            <div class="w-full md:w-1/2">
                                <x-forms.input type="number" label="Amount" name="amount"
                                    wire:model.debounce.500ms="amount" placeholder="0.00" min="1"
                                    step="0.01" class="py-4" />
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
        </div>

        <!-- Loading Overlay -->
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
    </style>
@endsection
