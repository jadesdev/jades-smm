@section('title', $metaTitle)
<div class="grid grid-cols-2 md:grid-cols-2 gap-6">
    <x-card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Referral Settings</h2>
        </x-slot>

        <form wire:submit="save" class="space-y-6">

            <x-forms.toggle label="Enable Referral" wire:model="referralEnabled" />
            <x-forms.input label="Referral Bonus" type="number" wire:model="referralBonus" />

            <div class="flex justify-end">
                <x-button type="submit" class="w-full">
                    Save
                </x-button>
            </div>
        </form>

    </x-card>
    <x-card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Welcome Bonus</h2>
        </x-slot>

        <form wire:submit="save" class="space-y-6">

            <x-forms.toggle label="Enable Welcome Bonus" wire:model="welcomeBonusEnabled" />
            <x-forms.input label="Welcome Bonus" type="number" wire:model="welcomeBonus" />

            <div class="flex justify-end">
                <x-button type="submit" class="w-full">
                    Save
                </x-button>
            </div>
        </form>

    </x-card>
</div>
