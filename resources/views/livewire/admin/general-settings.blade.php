@section('title', $metaTitle)
<div class="space-y-6">
    @if ($view == 'index')
        @include('admin.settings.index')
    @elseif ($view == 'payments')
        @include('admin.settings.payments')
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
