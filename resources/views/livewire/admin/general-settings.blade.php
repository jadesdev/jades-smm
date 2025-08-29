@section('title', $metaTitle)
<div class="space-y-6">
    @if ($view == 'index')
        <!-- Website Information -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Website Information
                </h3>
            </x-slot>
            <form action="{{ route('admin.settings.update') }}" method="post"
                class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
                @csrf
                <x-forms.input name="title" label="Website Name" type="text" :value="$settings->title"
                    placeholder="Enter website name" icon="fas fa-globe" />
                <x-forms.input name="name" label="Short Name" type="text" :value="$settings->name"
                    placeholder="Enter short name" />
                <x-forms.input name="email" label="Website Email" type="email" :value="$settings->email"
                    placeholder="Enter website email" />
                <x-forms.input name="admin_email" label="Admin Email" type="email" :value="$settings->admin_email"
                    placeholder="Enter admin email" />
                <x-forms.input name="phone" label="Website Phone" type="tel" :value="$settings->phone"
                    placeholder="Enter phone number" />
                <x-forms.textarea name="description" label="Website About" rows="3" :value="$settings->description"
                    placeholder="Enter website description" class="md:col-span-2" />
                <x-forms.textarea name="meta_description" label="Meta Description" rows="3" :value="$settings->meta_description"
                    placeholder="Meta description" class="md:col-span-2" />
                <x-forms.textarea name="meta_keywords" label="Website Keywords" rows="2" :value="$settings->meta_keywords"
                    placeholder="Enter website meta_keywords" class="md:col-span-2" />
                <div class="md:col-span-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        <i class="fas fa-save mr-2"></i> Save Settings
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Logo/Image Settings -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <i class="fas fa-images mr-2 text-purple-500"></i>
                    Logo & Image Settings
                </h3>
            </x-slot>
            <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
                    <div>
                        <x-forms.file name="logo" label="Site Logo" :value="$settings->logo" accept="image/*"
                            preview-class="h-20 w-30 mt-2" />
                    </div>
                    <div>
                        <x-forms.file name="favicon" label="Favicon" :value="$settings->favicon" accept="image/*"
                            preview-class="h-16 w-16 mt-2" />
                    </div>
                </div>
                <x-button type="submit" variant="primary" class="w-full">
                    <i class="fas fa-sync-alt mr-2"></i> Update Settings
                </x-button>
            </form>
        </x-card>

        <!-- Social Links -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <i class="fas fa-share-alt mr-2 text-green-500"></i>
                    Social Links
                </h3>
            </x-slot>
            <form action="{{ route('admin.settings.update') }}" method="post"
                class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
                @csrf
                <x-forms.input name="facebook" label="Facebook" type="text" :value="$settings->facebook"
                    icon="fab fa-facebook" />
                <x-forms.input name="twitter" label="Twitter" type="text" :value="$settings->twitter" icon="fab fa-twitter" />
                <x-forms.input name="instagram" label="Instagram" type="text" :value="$settings->instagram"
                    icon="fab fa-instagram" />
                <x-forms.input name="telegram" label="Telegram" type="text" :value="$settings->telegram"
                    icon="fab fa-telegram" />
                {{-- <x-forms.input name="linkedin" label="LinkedIn" type="text" :value="$settings->linkedin"
                    icon="fab fa-linkedin" /> --}}
                <x-forms.input name="whatsapp" label="WhatsApp" type="text" :value="$settings->whatsapp"
                    icon="fab fa-whatsapp" />
                <div class="md:col-span-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        <i class="fas fa-save mr-2"></i> Update Settings
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Currency Settings -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>
                    Currency Settings
                </h3>
            </x-slot>
            <form action="{{ route('admin.settings.update') }}" method="post"
                class="grid grid-cols-1 md:grid-cols-3 gap-6 gap-y-0">
                @csrf
                <x-forms.input name="currency" label="Currency Symbol" type="text" :value="$settings->currency" required />
                <x-forms.input name="currency_code" label="Currency Code" type="text" :value="$settings->currency_code"
                    required />
                <x-forms.input name="currency_rate" label="Currency Rate" type="text" :value="$settings->currency_rate"
                    required />
                <div class="md:col-span-3">
                    <x-button type="submit" variant="primary" class="w-full">
                        <i class="fas fa-save mr-2"></i> Update Settings
                    </x-button>
                </div>
            </form>
        </x-card>
    @elseif ($view == 'payments')
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
                        <x-forms.input name="FLUTTERWAVE_PUBLIC" label="FLW PUBLIC KEY"
                            value="{{ env('FLUTTERWAVE_PUBLIC') }}" required />
                        <x-forms.input type="hidden" name="types[]" value="FLUTTERWAVE_SECRET" />
                        <x-forms.input name="FLUTTERWAVE_SECRET" label="FLW SECRET KEY"
                            value="{{ env('FLUTTERWAVE_SECRET') }}" required />
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
                        <x-forms.input name="PAYPAL_CLIENT_ID" label="PayPal Client ID"
                            value="{{ env('PAYPAL_CLIENT_ID') }}" required />
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
    @elseif ($view == 'features')
        <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Force Https</h2>
                </x-slot>

                <x-forms.toggle label="" wire:model.lazy="sysSettings.force_https" />

            </x-card>
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Email Verification</h2>
                </x-slot>

                <x-forms.toggle label="" wire:model.lazy="sysSettings.verify_email" />
            </x-card>
        </div>
    @endif
</div>
