@section('title', $metaTitle)

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-10">
    <!-- Account Information -->
    <div>
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Account Information</h2>
            </x-slot>
            <form wire:submit.prevent="updateProfile" class="px-2 space-y-4">
                <x-forms.input wire:model="name" name="name" label="Name" placeholder="Enter your name"
                    :required="true" />
                <x-forms.input wire:model="username" name="username" label="Username" placeholder="Enter your username"
                    :required="true" />
                <x-forms.input wire:model="email" name="email" type="email" label="Email"
                    placeholder="Enter your email" readonly />
                <x-forms.input wire:model="phone" name="phone" label="Phone" placeholder="Enter your phone" />

                <x-button type="submit" variant="primary" class="w-full justify-center" wire:loading.attr="disabled"
                    wire:target="updateProfile">
                    <span wire:loading.remove wire:target="updateProfile">Update Profile</span>
                    <span wire:loading wire:target="updateProfile">Saving...</span>
                </x-button>
            </form>
        </x-card>

        <!-- Change Password -->
        <x-card class="mt-6">
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Security</h2>
            </x-slot>
            <form wire:submit.prevent="updatePassword" class="px-2 space-y-4">
                <x-forms.input wire:model="current_password" type="password" name="current_password"
                    label="Current Password" placeholder="Enter your current password" :required="true" />
                <x-forms.input wire:model="password" type="password" name="password" label="New Password"
                    placeholder="Enter new password" :required="true" />
                <x-forms.input wire:model="password_confirmation" type="password" name="password_confirmation"
                    label="Confirm New Password" placeholder="Confirm your new password" :required="true" />

                <x-button type="submit" variant="primary" class="w-full justify-center" wire:loading.attr="disabled"
                    wire:target="updatePassword">
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                    <span wire:loading wire:target="updatePassword">Updating...</span>
                </x-button>
            </form>
        </x-card>
    </div>

    <div class="space-y-6">
        <!-- API Key -->
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">API Key</h2>
            </x-slot>
            <div class="px-2 space-y-4">
                <x-forms.input wire:model="api_token" name="api_token" label="Your API Key"
                    placeholder="Generate an API key" readonly />
                <x-button wire:click="generateApiKey" wire:loading.attr="disabled" type="button" variant="primary"
                    class="w-full" id="generate-api-key">
                    <span wire:loading.remove wire:target="generateApiKey">Generate New API Key</span>
                    <span wire:loading wire:target="generateApiKey">Generating...</span>
                </x-button>
            </div>
        </x-card>

        <!-- Delete Account -->
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Delete Account</h2>
            </x-slot>
            <div class="px-2 space-y-4">
                <p class="text-sm text-red-600 dark:text-red-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>

                @if ($confirmingUserDeletion)
                    <form wire:submit.prevent="deleteAccount" class="space-y-4">
                        <x-forms.input wire:model="delete_password" type="password" name="delete_password"
                            label="Password" placeholder="Enter your password to confirm" :required="true" />
                        <div class="flex justify-end space-x-3">
                            <x-button type="button" variant="secondary"
                                wire:click="$set('confirmingUserDeletion', false)" wire:loading.attr="disabled">
                                Cancel
                            </x-button>
                            <x-button type="submit" variant="danger" wire:loading.attr="disabled">
                                <span wire:loading.remove>Delete Account</span>
                                <span wire:loading>Deleting...</span>
                            </x-button>
                        </div>
                    </form>
                @else
                    <x-button type="button" variant="danger" class="w-full justify-center"
                        wire:click="confirmUserDeletion">
                        Delete Account
                    </x-button>
                @endif
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('password-updated', () => {
                // Clear password fields after successful update
                @this.set('current_password', '');
                @this.set('password', '');
                @this.set('password_confirmation', '');
            });
        });
    </script>
@endpush
