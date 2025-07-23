@section('title', $metaTitle)
<div class="grid grid-cols-2 md:grid-cols-2 gap-6">
    <x-card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Referral Settings</h2>
        </x-slot>

        <form wire:submit="saveReferralSettings" class="space-y-6">
            <x-forms.toggle label="Enable Referral" wire:model.lazy="sysSettings.is_referral" />

            <x-forms.input name="referral_bonus" type="number" label="Referral Bonus (%)" wire:model="referral_bonus"
                required />

            <div class="flex justify-end">
                <x-button type="submit" class="w-full">
                    Save Referral Settings
                </x-button>
            </div>
        </form>

    </x-card>
    <x-card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Welcome Bonus</h2>
        </x-slot>

        <form wire:submit="saveWelcomeBonus" class="space-y-6">
            <x-forms.toggle label="Enable Welcome Bonus" wire:model.lazy="sysSettings.is_welcome_bonus" />

            <x-forms.input name="welcome_bonus" type="number" label="Welcome Bonus {{ get_setting('currency') }}"
                wire:model="welcome_bonus" required />

            <div class="flex justify-end">
                <x-button type="submit" class="w-full">
                    Save Welcome Bonus
                </x-button>
            </div>
        </form>

    </x-card>
</div>
