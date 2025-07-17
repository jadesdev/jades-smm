@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Categories</h2>
                <x-button wire:click="add" variant='primary'>Add Category</x-button>
            </div>
        </x-slot>

        {{-- Search, Filter, and Bulk Actions --}}
        <div class="mb-6 space-y-4">
            {{-- Search and Filter Row --}}
            <div class="flex flex-wrap gap-4 items-center">
                {{-- Search --}}
                <div class="flex-1 min-w-64">
                    <x-forms.input wire:model.live.debounce.300ms="search" type="text" name="search" id="search"
                        placeholder="Search categories..." class="w-full" />
                </div>

                {{-- Status Filter --}}
                <div class="min-w-48">
                    <x-forms.select wire:model.live="statusFilter" name="statusFilter" id="statusFilter" class="w-full">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </x-forms.select>
                </div>

                {{-- Clear Filters --}}
                <x-button wire:click="clearFilters" class="mb-4" variant="secondary">Clear Filters</x-button>
            </div>

            {{-- Bulk Actions Row --}}
            @if (!empty($selectedCategories))
                <div class="flex flex-wrap gap-4 items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="text-sm text-blue-700 dark:text-blue-300">
                        {{ count($selectedCategories) }} selected
                    </span>

                    <x-forms.select wire:model.live="bulkAction" name="bulkAction" id="bulkAction" class="min-w-48">
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </x-forms.select>

                    <x-button wire:click="executeBulkAction" class="!mb-4" variant="primary" size="sm"
                        :disabled="empty($bulkAction)">
                        Execute
                    </x-button>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                            <button wire:click="sortByColumn('name')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Name</span>
                                @if ($sortBy === 'name')
                                    @if ($sortDirection === 'asc')
                                        <i class="fa fa-caret-up"></i>
                                    @else
                                        <i class="fa fa-caret-down"></i>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                            <button wire:click="sortByColumn('is_active')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Status</span>
                                @if ($sortBy === 'is_active')
                                    @if ($sortDirection === 'asc')
                                        <i class="fa fa-caret-up"></i>
                                    @else
                                        <i class="fa fa-caret-down"></i>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300  tracking-wider">
                            Services
                        </th>
                        <th scope="col" class="relative px-6 py-3 text-right">
                            <span class="text-right font-medium text-gray-500 dark:text-gray-300">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" wire:model.live="selectedCategories" value="{{ $category->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700" />
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($category->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-button
                                    href="{{ route('admin.services', ['categoryFilter' => $category->id]) }}"
                                    variant="primary" outline size="xs" class="ml-2">View ({{ $category->services_count }})
                                </x-button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <x-button wire:click="edit({{ $category->id }})" variant="info" size="sm">
                                    Edit
                                </x-button>
                                <x-button wire:click="delete({{ $category->id }})" variant="danger" size="sm">
                                    Delete
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                @if ($search || $statusFilter)
                                    No categories found matching your criteria.
                                @else
                                    No categories found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </x-card>

    {{-- Add/Edit Modal --}}
    <x-modal name="category-modal" :title="$editing ? 'Edit Category' : 'Add Category'" persistent="true">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <x-forms.input wire:model.defer="name" name='name' id="name" type="text"
                    class="mt-1 block w-full" />

                <x-forms.select wire:model.defer="isActive" name='isActive' id="isActive"
                    class="mt-1 block w-full">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </x-forms.select>
            </div>
        </form>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeModal">Cancel</x-button>
            <x-button variant="primary" wire:click="save">Save</x-button>
        </x-slot>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-category-modal" title="Confirm Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">Are you sure you want to delete the category
            "{{ $deleting?->name }}"?</p>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="confirmDelete">Delete</x-button>
        </x-slot>
    </x-modal>

    {{-- Bulk Delete Confirmation Modal --}}
    <x-modal name="bulk-delete-modal" title="Confirm Bulk Deletion" persistent="true">
        <p class="text-gray-600 dark:text-gray-300">
            Are you sure you want to delete {{ count($selectedCategories) }} selected categories? This action cannot be
            undone.
        </p>

        <x-slot name="footer">
            <x-button variant="secondary" wire:click="closeBulkDeleteModal">Cancel</x-button>
            <x-button variant="danger" wire:click="confirmBulkDelete">Delete Selected</x-button>
        </x-slot>
    </x-modal>
</div>

@include('layouts.meta')
