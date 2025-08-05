@section('title', $metaTitle)

<x-card>
    <x-slot name="header">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $metaTitle }}</h3>
    </x-slot>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        {{-- Search Input --}}
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search tickets by ID, subject, or user..."
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
        </div>

        {{-- Status Filter --}}
        <div class="sm:w-48">
            <select wire:model.live="statusFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        {{-- Per Page --}}
        <div class="sm:w-32">
            <select wire:model.live="perPage"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 shadow overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th wire:click="sortBy('code')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-1">
                            <span>Ticket ID</span>
                            @if ($sortField === 'code')
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
                    <th wire:click="sortBy('subject')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-1">
                            <span>Subject</span>
                            @if ($sortField === 'subject')
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
                        User
                    </th>
                    <th wire:click="sortBy('created_at')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-1">
                            <span>Date</span>
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
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-1">
                            <span>Status</span>
                            @if ($sortField === 'status')
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
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            #{{ $ticket->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="max-w-xs truncate" title="{{ $ticket->subject }}">
                                {{ $ticket->subject }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="flex items-center">
                                <div>
                                    <a class="font-medium text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('admin.users.view', $ticket->user_id) }}">{{ $ticket->user?->name ?? 'N/A' }}</a>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $ticket->user?->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                {{ $ticket->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($ticket->status)
                                @case('open')
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Open
                                    </span>
                                @break

                                @case('pending')
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                        Pending
                                    </span>
                                @break

                                @case('resolved')
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        Resolved
                                    </span>
                                @break

                                @case('closed')
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                        Closed
                                    </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.support.messages', $ticket) }}" wire:navigate
                                    class="rounded-md text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 ring-1 ring-primary-600 dark:ring-primary-400 px-1.5 py-0.5">
                                    View
                                </a>
                                <button
                                    class="rounded-md text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ring-1 ring-red-600 dark:ring-red-400 px-1.5 py-0.5"
                                    wire:click="deleteTicket('{{ $ticket->id }}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-slot name="footer" class="mt-6">
            {{ $tickets->links() }}
        </x-slot>

    </x-card>

    @include('layouts.meta')
