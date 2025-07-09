@section('title', 'Sign In')

<div class="flex min-h-screen items-center justify-center bg-gray-100 py-12 px-4 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">

        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Or
                <a href="{{ route('register') }}"
                    class="font-medium text-primary-600 hover:text-primary-500 dark:hover:text-primary-400">
                    create a new account
                </a>
            </p>
        </div>

        <x-card class="!p-8">
            <form wire:submit.prevent="login">
                <div class="space-y-6">
                    <x-forms.input wire:model="email" type="email" name="email" label="Email Address"
                        placeholder="you@example.com" :required="true" autocomplete="email" autofocus />

                    <x-forms.input wire:model="password" type="password" name="password" label="Password"
                        placeholder="••••••••" :required="true" autocomplete="current-password" />

                    <div class="flex items-center justify-between">
                        <x-forms.checkbox wire:model="remember" name="remember" id="remember" label="Remember me" />

                        <div class="text-sm">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-primary-600 hover:text-primary-500 dark:hover:text-primary-400">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <x-button type="submit" variant="primary" class="w-full justify-center py-3"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Sign In</span>
                            <span wire:loading>Signing In...</span>
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>

    </div>
</div>
