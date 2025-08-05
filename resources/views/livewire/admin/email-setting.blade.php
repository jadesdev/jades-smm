@section('title', $metaTitle)
<div>

    <div class="grid grid-cols-2 gap-4">

        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Email Gateway</h2>
            </x-slot>

            <x-forms.select wire:model="email_gateway" name="email_gateway" label="Email Gateway">
                <option value="php">PHP Mail</option>
                <option value="smtp">SMTP</option>
            </x-forms.select>
            <form wire:submit.prevent="updateSettings">
                @foreach ($settings as $key => $value)
                    <x-forms.input wire:model.defer="settings.{{ $key }}"
                        type="{{ str_contains($key, 'PASSWORD') ? 'password' : 'text' }}" name="{{ $key }}"
                        label="{{ str_replace('_', ' ', $key) }}" placeholder="{{ $key }}" />
                @endforeach
                <x-button class="w-full" type="submit">Save</x-button>

            </form>
        </x-card>

        <div>
            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Test Email</h2>
                </x-slot>
                <form wire:submit.prevent="sendTestEmail">
                    <x-forms.input wire:model.defer="test_email" type="email" name="test_email" label="Email Address"
                        placeholder="you@example.com" required autocomplete="email" autofocus />

                    <x-button type="submit" class="w-full">Send Test Email</x-button>
                </form>
            </x-card>

            <x-card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Instructions</h2>
                </x-slot>
                <div class="text-gray-800 dark:text-gray-200 space-y-4">
                    <div>
                        <p class="font-bold">For Non-SSL:</p>
                        <ul class="space-y-2 list-disc list-inside">
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Host according to your server’s Mail Client Manual Settings
                            </li>
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Port as <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">587</code>
                            </li>
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Encryption as
                                <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">tls</code>
                                or
                                <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">ssl</code>
                                if tls fails
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-bold">For SSL:</p>
                        <ul class="space-y-2 list-disc list-inside">
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Host according to your server’s Mail Client Manual Settings
                            </li>
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Port as <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">465</code>
                            </li>
                            <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded">
                                Set Mail Encryption as
                                <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">ssl</code>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@include('layouts.meta')
