@section('title', $metaTitle)
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile</h3>
        </x-slot>
        <div>
            <form wire:submit.prevent="update">
                <x-forms.input label="Name" required wire:model="name" />
                <x-forms.input label="Email" required wire:model="email" />
                <x-forms.input label="Phone" wire:model="phone" />
                <x-forms.input label="Password" wire:model.defer="password" type="password" />
                <x-button wire:loading.attr="disabled" variant="primary" class="w-full" type="submit">
                    Update
                </x-button>
            </form>
        </div>
    </x-card>
</div>

@include('layouts.meta')
