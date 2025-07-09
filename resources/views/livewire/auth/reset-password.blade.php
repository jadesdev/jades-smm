@section('title', 'Reset Password')

<div class="flex min-h-screen items-center justify-center bg-gray-100 py-12 px-4 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">

        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Set Your New Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Please choose a strong, new password for your account.
            </p>
        </div>

        <x-card class="!p-8">
            <form wire:submit.prevent="resetPassword">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-6">
                    <x-forms.input wire:model.defer="email" type="email" name="email" label="Email Address" placeholder="you@example.com"
                        readonly :required="true" autocomplete="email" autofocus />

                    <x-forms.input wire:model.defer="password" type="password" name="password" label="New Password" placeholder="••••••••"
                        :required="true" autocomplete="new-password" />

                    <x-forms.input wire:model.defer="password_confirmation" type="password" name="password_confirmation" label="Confirm New Password"
                        placeholder="••••••••" :required="true" autocomplete="new-password" />

                    <div>
                        <x-button type="submit" variant="primary" class="w-full justify-center py-3">
                            Reset Password
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>

    </div>
</div>
