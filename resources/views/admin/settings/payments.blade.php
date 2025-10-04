<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach ($gateways as $gateway)
        <x-card class="hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-center">
                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $gateway['name'] }}
                </h4>
                <x-forms.toggle wire:model.lazy="sysSettings.{{ $gateway['key'] }}" label="" />
            </div>
        </x-card>
    @endforeach
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    <!-- Paystack Credentials -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fab fa-cc-stripe mr-2 text-purple-500"></i>
                Paystack Credentials
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.env_key') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="paystack">
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="PAYSTACK_PUBLIC_KEY" />
                <x-forms.input name="PAYSTACK_PUBLIC_KEY" label="PUBLIC KEY" :value="env('PAYSTACK_PUBLIC_KEY')" required />
                <x-forms.input type="hidden" name="types[]" value="PAYSTACK_SECRET_KEY" />
                <x-forms.input name="PAYSTACK_SECRET_KEY" label="SECRET KEY" :value="env('PAYSTACK_SECRET_KEY')" required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>
    {{-- Korapay --}}
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fab fa-cc-stripe mr-2 text-purple-500"></i>
                Korapay Credentials
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.env_key') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="korapay">
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="KORAPAY_PUBLIC_KEY" />
                <x-forms.input name="KORAPAY_PUBLIC_KEY" label="PUBLIC KEY" :value="env('KORAPAY_PUBLIC_KEY')" required />
                <x-forms.input type="hidden" name="types[]" value="KORAPAY_SECRET_KEY" />
                <x-forms.input name="KORAPAY_SECRET_KEY" label="SECRET KEY" :value="env('KORAPAY_SECRET_KEY')" required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>


    <!-- Flutterwave Credentials -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                Flutterwave Credentials
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.env_key') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="FLUTTERWAVE_PUBLIC" />
                <x-forms.input name="FLUTTERWAVE_PUBLIC" label="FLW PUBLIC KEY" value="{{ env('FLUTTERWAVE_PUBLIC') }}"
                    required />
                <x-forms.input type="hidden" name="types[]" value="FLUTTERWAVE_SECRET" />
                <x-forms.input name="FLUTTERWAVE_SECRET" label="FLW SECRET KEY" value="{{ env('FLUTTERWAVE_SECRET') }}"
                    required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- PayPal Credentials -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fab fa-paypal mr-2 text-blue-400"></i>
                PayPal Credentials
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.env_key') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="PAYPAL_CLIENT_ID" />
                <x-forms.input name="PAYPAL_CLIENT_ID" label="PayPal Client ID" value="{{ env('PAYPAL_CLIENT_ID') }}"
                    required />
                <x-forms.input type="hidden" name="types[]" value="PAYPAL_CLIENT_SECRET" />
                <x-forms.input name="PAYPAL_CLIENT_SECRET" label="PayPal Client Secret"
                    value="{{ env('PAYPAL_CLIENT_SECRET') }}" required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Cryptomus Credentials -->
    <x-card class="hidden">
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-coins mr-2 text-yellow-500"></i>
                Cryptomus Credentials
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.env_key') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="CRYPTOMUS_KEY" />
                <x-forms.input name="CRYPTOMUS_KEY" label="CRYPTOMUS KEY" value="{{ env('CRYPTOMUS_KEY') }}"
                    required />
                <x-forms.input type="hidden" name="types[]" value="CRYPTOMUS_MERCHANT" />
                <x-forms.input name="CRYPTOMUS_MERCHANT" label="CRYPTOMUS MERCHANT KEY"
                    value="{{ env('CRYPTOMUS_MERCHANT') }}" required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Bank Payment Details -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-university mr-2 text-green-500"></i>
                Bank Payment Details
            </h3>
        </x-slot>
        <form action="{{ route('admin.settings.store_settings') }}" method="post">
            @csrf
            <div class="space-y-4">
                <x-forms.input type="hidden" name="types[]" value="bank_name" />
                <x-forms.input name="bank_name" label="Bank Name" :value="sys_setting('bank_name')" required />
                <x-forms.input type="hidden" name="types[]" value="account_name" />
                <x-forms.input name="account_name" label="Account Name" :value="sys_setting('account_name')" required />
                <x-forms.input type="hidden" name="types[]" value="account_number" />
                <x-forms.input name="account_number" label="Account Number" :value="sys_setting('account_number')" required />
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-save mr-2"></i> Save
                </x-button>
            </div>
        </form>
    </x-card>

    {{-- fees --}}
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>
                Fees Settings
            </h3>
        </x-slot>

        <form action="{{ route('admin.settings.store_settings') }}" method="post" class="row">
            @csrf
            <input type="hidden" name="types[]" value="card_fee_cap">
            <x-forms.input name="card_fee_cap" label="Card Payment Capped @({{ get_setting('currency') }})"
                :value="sys_setting('card_fee_cap')" required />
            <input type="hidden" name="types[]" value="card_fee">
            <x-forms.input name="card_fee" label="Card Payment (%)" :value="sys_setting('card_fee')" required />
            <x-button type="submit" variant="primary" class="w-full">
                <i class="fas fa-save mr-2"></i> Save
            </x-button>
        </form>
    </x-card>
</div>
