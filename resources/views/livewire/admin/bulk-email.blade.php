@section('title', $metaTitle)
<div>
    @if ($view == 'list')
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Bulk Email</h2>
                    <x-button wire:click="add" variant='primary'>New Email</x-button>
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
                                Subject
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                Date
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
                        @forelse ($newsletters as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item->subject }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->date->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($item->status == 1)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Sent
                                        </span>
                                    @elseif($item->status == 2)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Scheduled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <x-button wire:click="edit('{{ $item->id }}')" variant="primary" size="sm">
                                        Edit
                                    </x-button>
                                    <x-button outline variant="danger" size="sm"
                                        wire:click="delete('{{ $item->id }}')">
                                        Delete
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No newsletters found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($newsletters->hasPages())
                <div class="mt-4">
                    {{ $newsletters->links() }}
                </div>
            @endif
        </x-card>
    @elseif ($view == 'showForm')
        <x-card>
            <x-slot name="header">
                <div class="flex justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $newsletterId ? 'Edit Email' : 'Create Email' }}
                    </h2>
                    <x-button wire:click="backToList" variant='primary'>Back to List</x-button>
                </div>
            </x-slot>

            <form wire:submit.prevent="save" class="space-y-4">
                <x-forms.checkbox label="Send to all registered users" wire:model="user_emails" />

                <x-forms.textarea label="Other Emails (comma separated)" name="other_emails" wire:model="other_emails"
                    placeholder="other@example.com, another@example.com" rows="3" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-forms.input label="Send Date" required wire:model="date" type="text" />

                    <x-forms.select label="Status" wire:model="status">
                        <option value="2">Scheduled</option>
                        <option value="1">Sent</option>
                    </x-forms.select>
                </div>

                <x-forms.input label="Subject" required wire:model="subject" placeholder="Email Subject" />
                <x-forms.rich-editor label="Content" required wire:model="content" name="content"
                    placeholder="Write your email content here..." />

                <x-button class="w-full" type="submit">
                    {{ $newsletterId ? 'Update' : 'Schedule' }}
                </x-button>
            </form>
        </x-card>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-modal" title="Confirm Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">
            Are you sure you want to delete this newsletter "{{ $deletingItem?->subject }}"?
        </p>
        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="deleteNewsletter" wire:loading.attr="disabled">Delete</x-button>
        </x-slot>
    </x-modal>
</div>
@include('layouts.meta')
