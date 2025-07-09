@section('title', 'Sign Up')

<div class="flex min-h-screen items-center justify-center bg-gray-100 py-10 px-4 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">

        {{-- Header --}}
        <div>
            {{-- You can replace this with your logo --}}
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Or
                <a href="{{ route('login') }}"
                    class="font-medium text-primary-600 hover:text-primary-500 dark:hover:text-primary-400">
                    sign in to your existing account
                </a>
            </p>
        </div>

        {{-- Form Container --}}
        <x-card class="!p-8">
            <form wire:submit.prevent="register">
                <div class="space-y-5">
                    <x-forms.input wire:model="name" name="name" label="Name" placeholder="Full Name"
                        :required="true" autocomplete="name" />

                    <x-forms.input wire:model="username" name="username" label="Username" placeholder="janesmith"
                        :required="true" autocomplete="username" />

                    <x-forms.input wire:model="email" type="email" name="email" label="Email Address"
                        placeholder="you@example.com" :required="true" autocomplete="email" />

                    <x-forms.input wire:model="password" type="password" name="password" label="Password"
                        placeholder="••••••••" :required="true" autocomplete="new-password" />

                    <x-forms.input wire:model="password_confirmation" type="password" name="password_confirmation"
                        label="Confirm Password" placeholder="••••••••" :required="true" autocomplete="new-password" />

                    <!-- Referral Code -->
                    @if ($referral_code)
                        <div class="rounded-md bg-blue-50 dark:bg-blue-900/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        Referred by: {{ $referral_code }}
                                    </p>
                                </div>
                                <input type="hidden" wire:model="referral_code" />
                            </div>
                        </div>
                    @else
                        <x-forms.input wire:model.live.debounce.500ms="referral_code" name="referral_code"
                            label="Referral Code (Optional)" placeholder="Enter referral code" :required="false" />
                    @endif

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <x-forms.checkbox class="mt-4" wire:model="terms" name="terms" id="terms"
                                :required="true" />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-600 dark:text-gray-300">
                                I agree to the
                                <a href="#"
                                    class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                                    Terms of Service
                                </a>.
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-button type="submit" variant="primary" class="w-full justify-center py-3 mt-4"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Create Account</span>
                            <span wire:loading>Creating Account...</span>
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>
