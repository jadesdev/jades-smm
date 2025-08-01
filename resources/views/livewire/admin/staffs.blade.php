@section('title', $metaTitle)
<div>
    @if ($view == 'list')
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manage Staffs</h2>
                    <x-button wire:click="add" variant='primary'>New Staff</x-button>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                #
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3 text-right">
                                <span class="text-right font-medium text-gray-500 dark:text-gray-300">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($staffs as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ e($item->name) }}

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($item->type == 'super')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                            super admin
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100">
                                            {{ $item->type }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($item->is_active == 1)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <x-button wire:click="edit('{{ $item->id }}')" variant="primary" size="sm">
                                        Edit
                                    </x-button>
                                    @if ($item->type !== 'super')
                                        <x-button outline variant="danger" size="sm"
                                            wire:click="delete('{{ $item->id }}')">
                                            Delete
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No staff found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    @elseif ($view == 'showForm')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $staffId ? 'Edit Staff' : 'Create Staff' }}
                    </h2>
                    <x-button wire:click="backToList" variant='primary'>Back to List</x-button>
                </div>
            </x-slot>

            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-forms.input label="Name" required wire:model="name" />
                    <x-forms.input label="Email" required wire:model="email" />
                    <x-forms.input label="Phone" wire:model="phone" />
                    <x-forms.input label="Password" wire:model.defer="password" type="password" />
                    <x-forms.select label="Type" required wire:model="type">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </x-forms.select>
                    <x-forms.select label="Status" wire:model="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </x-forms.select>

                </div>
                <x-button class="w-full" type="submit">
                    {{ $staffId ? 'Update' : 'Create' }}
                </x-button>
            </form>
        </x-card>
    @endif
    
    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-modal" title="Confirm Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">
            Are you sure you want to delete this newsletter "{{ $deletingStaff?->name }}"?
        </p>
        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="deleteStaff" wire:loading.attr="disabled">Delete</x-button>
        </x-slot>
    </x-modal>
</div>

@include('layouts.meta')
