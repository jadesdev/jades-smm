@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $metaTitle }}</h2>

            </div>
        </x-slot>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <x-forms.input wire:model.debounce.300ms="search" placeholder="Name, email, username, phone..."
                label="Search" />
            <!-- Status Filter -->
            <x-forms.select wire:model.live="statusFilter" label="Status" :options="[1 => 'Active', 0 => 'Inactive']"></x-forms.select>

            <!-- Email Verified Filter -->
            <x-forms.select wire:model.live="emailVerifiedFilter" label="Email Verified"
                :options="[1 => 'Verified', 0 => 'Not Verified']"></x-forms.select>

            <!-- Per Page -->
            <x-forms.select wire:model.live="perPage" label="Per Page" :options="[25 => '25', 50 => '50', 100 => '100', 150 => '150']"></x-forms.select>
        </div>
        <!-- Results Summary -->
        <div class="mb-4 flex justify-between items-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                of {{ $users->total() }} results
            </p>
            <div class="my-auto justify-end">
                <x-button wire:click="resetFilters" size="sm" variant="secondary">
                    Reset Filters
                </x-button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th wire:click="sortBy('name')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Name</span>
                                @if ($sortField === 'name')
                                    <span class="text-primary-500 dark:text-primary-400">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Contact Info
                        </th>

                        <th wire:click="sortBy('balance')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Balance</span>
                                @if ($sortField === 'balance')
                                    <span class="text-primary-500 dark:text-primary-400">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Verification Status
                        </th>
                        <th wire:click="sortBy('is_active')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                @if ($sortField === 'is_active')
                                    <span class="text-primary-500 dark:text-primary-400">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('created_at')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Joined</span>
                                @if ($sortField === 'created_at')
                                    <span class="text-primary-500 dark:text-primary-400">
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($user->image)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->image }}"
                                                alt="{{ $user->name }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                @if ($user->phone)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ format_price($user->balance) }}
                                </div>
                                @if ($user->bonus > 0)
                                    <div class="text-sm text-green-600 dark:text-green-500">Bonus:
                                        {{ format_price($user->bonus) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <p>Email: @if ($user->email_verify)
                                            <i class="fal fa-check-circle text-green-600 dark:text-green-500"></i>
                                        @else
                                            <i class="fal fa-times-circle text-red-600 dark:text-red-500"></i>
                                        @endif
                                    </p>
                                    <p>Phone: @if ($user->sms_verify)
                                            <i class="fal fa-check-circle text-green-600 dark:text-green-500"></i>
                                        @else
                                            <i class="fal fa-times-circle text-red-600 dark:text-red-500"></i>
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <x-button href="{{ route('admin.users.view', $user->id) }}"
                                        size="xs">View</x-button>
                                    {{-- <button class="text-red-600 hover:text-red-900">Delete</button> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-slot name="footer" class="mt-6">
            {{ $users->links() }}
        </x-slot>
    </x-card>
</div>



@include('layouts.meta')
