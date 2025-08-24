@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $metaTitle }}</h2>
        </x-slot>

        <!-- Filters Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-4">
            <x-forms.input wire:model.live.debounce.300ms="search" placeholder="Search by User, Code, Service..."
                label="Search" />
            <x-forms.select wire:model.live="statusFilter" label="Status" :options="$statuses" placeholder="All Statuses" />
            <x-forms.select wire:model.live="typeFilter" label="Type" :options="$types" placeholder="All Types" />
            <x-forms.select wire:model.live="serviceFilter" label="Service" :options="$services"
                placeholder="All Services" />
            <x-forms.select wire:model.live="perPage" label="Per Page" :options="['25' => '25', '50' => '50', '100' => '100']" />
        </div>

        <!-- Results Summary & Reset -->
        <div class="mb-4 flex justify-between items-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                @if ($transactions->total() > 0)
                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of
                    {{ $transactions->total() }} results
                @else
                    No results found.
                @endif
            </p>
            <x-button wire:click="resetFilters" size="sm" variant="secondary">
                Reset Filters
            </x-button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        {!! render_sortable_header('user_id', 'User', $sortField, $sortDirection) !!}
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Transaction
                        </th>
                        {!! render_sortable_header('amount', 'Amount', $sortField, $sortDirection) !!}
                        {!! render_sortable_header('status', 'Status', $sortField, $sortDirection) !!}
                        {!! render_sortable_header('created_at', 'Date', $sortField, $sortDirection) !!}
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a class="text-primary-500 hover:underline"
                                        href="{{ route('admin.users.view', $transaction->user_id) }}">{{ $transaction->user->name ?? 'N/A' }}</a>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->user->email ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ ucfirst($transaction->service) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">#{{ $transaction->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div @class([
                                    'text-sm font-semibold',
                                    'text-green-600' => $transaction->type == 'credit',
                                    'text-red-600' => $transaction->type == 'debit',
                                ])>
                                    {{ $transaction->type == 'credit' ? '+' : '-' }}
                                    {{ format_price($transaction->amount) }}
                                </div>
                                @if ($transaction->charge > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Fee:
                                        {{ format_price($transaction->charge) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' =>
                                        $transaction->status === 'successful',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' =>
                                        $transaction->status === 'pending' ||
                                        $transaction->status === 'processing',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' =>
                                        $transaction->status === 'failed',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' =>
                                        $transaction->status === 'reversed',
                                ])>
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <x-button wire:click="showDetails('{{ $transaction->id }}')" outline
                                    size="xs">View
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                No transactions found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot name="footer" class="mt-6">
            {{ $transactions->links() }}
        </x-slot>
    </x-card>

    <!-- Details Modal -->
    @if ($viewingTransaction)
        <x-modal name="transaction-details" title=" Transaction #{{ $viewingTransaction->code }}">

            <div class="prose dark:prose-invert max-w-none">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
                    <li class="py-2 flex justify-between"><span>Status:</span>
                        <span @class([
                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' =>
                                $viewingTransaction->status === 'successful',
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' =>
                                $viewingTransaction->status === 'pending' ||
                                $viewingTransaction->status === 'processing',
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' =>
                                $viewingTransaction->status === 'failed',
                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' =>
                                $viewingTransaction->status === 'reversed',
                        ])>
                            {{ ucfirst($viewingTransaction->status) }}
                        </span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Service:</span>
                        <span>{{ ucfirst($viewingTransaction->service) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Type:</span>
                        <span>{{ ucfirst($viewingTransaction->type) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Amount:</span>
                        <span>{{ format_price($viewingTransaction->amount) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Charge:</span>
                        <span>{{ format_price($viewingTransaction->charge) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Old Balance:</span>
                        <span>{{ format_price($viewingTransaction->old_balance) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>New Balance:</span>
                        <span>{{ format_price($viewingTransaction->new_balance) }}</span>
                    </li>
                    <li class="py-2 flex justify-between"><span>Date:</span>
                        <span>{{ $viewingTransaction->created_at->format('M d, Y h:i A') }}</span>
                    </li>
                </ul>
                <div class="mt-4">
                    <h4 class="font-semibold">Message:</h4>
                    <p>{{ $viewingTransaction->message }}</p>
                </div>
                @if ($viewingTransaction->response)
                    <div class="mt-4">
                        <h4 class="font-semibold">API Response:</h4>
                        <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded-md text-xs overflow-x-auto max-h-48 overflow-y-auto">@json($viewingTransaction->response, JSON_PRETTY_PRINT)</pre>
                    </div>
                @endif
            </div>

            {{-- <div class="mt-6 flex justify-end space-x-3">
                @if (!in_array($viewingTransaction->status, ['pending', 'processing']))
                    <x-button wire:click="approve('{{ $viewingTransaction->id }}')" variant="success">Approve</x-button>
                    <x-button wire:click="reverse('{{ $viewingTransaction->id }}')" variant="warning">Reverse</x-button>
                @endif
            </div> --}}
        </x-modal>
    @endif
</div>
