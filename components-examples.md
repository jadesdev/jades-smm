<x-forms.input name="amount" label="Amount" placeholder="Amount" />

    {{-- modal --}}

    <x-modal name="basic-modal" title="Basic Modal">
        <p>This is a basic modal with some content.</p>
    </x-modal>
    <button x-on:click="$dispatch('open-modal', 'basic-modal')" class="bg-blue-500 text-white px-4 py-2 rounded">
        Open Basic Modal
    </button>

    <!-- Button to open the modal -->
    <button x-data @click="$dispatch('open-modal', { name: 'confirm-deletion' })">
        Delete Post
    </button>

    <!-- The Modal Component -->
    <x-modal name="confirm-deletion" title="Confirm Deletion">
        <p class="text-sm text-gray-600">
            Are you sure you want to delete this post? This action cannot be undone.
        </p>

        <div class="mt-6 flex justify-end">
            <x-button variant="secondary" x-on:click="$dispatch('close-modal', { name: 'confirm-deletion' })">
                Cancel
            </x-button>

            <x-button variant="danger" class="ml-3">
                Delete Post
            </x-button>
        </div>
    </x-modal>

    <!-- Open modal -->
    <x-button x-data @click="$dispatch('open-modal', { name: 'confirm-action' })">
        Open Modal
    </x-button>

    <!-- Modal component -->
    <x-modal name="confirm-action" title="Confirmation" maxWidth="lg" position="top">
        <p>Are you sure you want to perform this action?</p>

        <x-slot name="footer">
            <button x-on:click="$dispatch('close-modal', { name: 'confirm-action' })"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                Cancel
            </button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                Confirm
            </button>
        </x-slot>
    </x-modal>

    <x-dropdown.menu>
        <x-slot:trigger>
            <button
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Options
                <svg class="ml-2 h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </x-slot:trigger>

        <x-dropdown.item href="/profile">
            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            View Profile
        </x-dropdown.item>

        <x-dropdown.item href="/settings">
            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Settings
        </x-dropdown.item>

        <x-dropdown.divider />

        <x-dropdown.item variant="danger">
            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Sign Out
        </x-dropdown.item>
    </x-dropdown.menu>