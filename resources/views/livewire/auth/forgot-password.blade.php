@section('title', 'Forgot Password')

<div class="flex items-center justify-center bg-gray-100 py-12 px-4 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Forgot Your Password?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                No problem. Enter your email address below and we'll send you a link to reset it.
            </p>
        </div>

        <x-card class="!p-8">
            <form wire:submit.prevent="sendPasswordResetLink">
                <div class="space-y-6">
                    <x-forms.input wire:model="email" type="email" name="email" label="Email Address"
                        placeholder="you@example.com" :required="true" autocomplete="email" autofocus />

                    <div class="flex items-center justify-end">
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:hover:text-primary-400">
                            Return to sign in
                        </a>
                    </div>

                    <div>
                        <x-button type="submit" variant="primary" class="w-full justify-center py-3"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Email Password Reset Link</span>
                            <span wire:loading>Sending Reset Link...</span>
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>
