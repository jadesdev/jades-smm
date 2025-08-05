@section('title', 'Verify Account')

<div class="flex items-center justify-center bg-gray-100 py-12 px-4 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Verify Your Email Address
        </h2>
        <x-card class="!p-4">
            <p class="mb-3 text-center text-sm text-gray-600 dark:text-gray-400">
                We just need to verify your email address before continuing. <br>
                Click the button below and we'll send a new verification link.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="rounded-md bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900 dark:text-green-200">
                    A new verification link has been sent to your email address.
                </div>
            @endif
            <form wire:submit.prevent="sendVerification">
                <x-button type="submit" variant="primary" class="w-full justify-center py-3 mt-3"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Resend Verification Email</span>
                    <span wire:loading>Sending...</span>
                </x-button>
            </form>
        </x-card>
    </div>
</div>
