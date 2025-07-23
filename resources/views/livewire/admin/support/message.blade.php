@section('title', $metaTitle)

<div wire:poll.30s="loadMessages"
    class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 sm:mb-8">
    <div class="border-b border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between space-y-4 lg:space-y-0">
            <!-- Ticket Info -->
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">#{{ $ticket->code }}</h2>
                    <span
                        class="bg-{{ $ticket->status_color }}-100 dark:bg-{{ $ticket->status_color }}-800 text-{{ $ticket->status_color }}-800 dark:text-{{ $ticket->status_color }}-200 text-sm font-medium px-3 py-1 rounded-full">
                        {{ ucfirst($ticket->status) }}
                    </span>
                    @if ($ticket->priority)
                        <span
                            class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200 text-xs font-medium px-2 py-1 rounded">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                    @endif
                </div>
                <p class="text-gray-800 dark:text-gray-300 font-medium mb-1">{{ $ticket->subject }}</p>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-x-4">
                    <span><strong>Customer:</strong> {{ $ticket->user->name ?? 'N/A' }}</span>
                    <span><strong>Email:</strong> {{ $ticket->user->email ?? 'N/A' }}</span>
                    <span><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</span>
                    <span><strong>Updated:</strong> {{ $ticket->updated_at->diffForHumans() }}</span>
                </div>
                @if ($ticket->assigned_to)
                    <div class="mt-2">
                        <span class="text-sm text-blue-600 dark:text-blue-400">
                            <i class="fa fa-user-circle mr-1"></i>
                            Assigned to: {{ $ticket->assignedTo->name ?? 'Unknown' }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Admin Actions -->
            <div class="flex flex-wrap gap-2">

                <!-- Assignment Actions -->
                {{-- @if (!$ticket->assigned_to)
                    <button wire:click="assignToMe"
                        class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                        <i class="fa fa-user-plus mr-1"></i>
                        Assign to Me
                    </button>
                @else
                    <button wire:click="unassignTicket"
                        class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                        <i class="fa fa-user-times mr-1"></i>
                        Unassign
                    </button>
                @endif --}}

                <!-- Status Actions -->
                @if ($ticket->status === 'open')
                    <button wire:click="markAsResolved"
                        class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                        <i class="fa fa-check mr-1"></i>
                        Mark Resolved
                    </button>
                    <x-modal name="close-ticket-admin" title="Close Ticket" maxWidth="sm">
                        <p>Are you sure you want to close this ticket? This action will prevent further responses from
                            the customer.</p>
                        <x-slot name="footer">
                            <button wire:click="closeTicket"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                Close Ticket
                            </button>
                        </x-slot>
                    </x-modal>
                    <button x-on:click="$dispatch('open-modal', { name: 'close-ticket-admin' })"
                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                        <i class="fa fa-times mr-1"></i>
                        Close Ticket
                    </button>
                @elseif($ticket->status === 'closed')
                    <button wire:click="openTicket"
                        class="px-3 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors text-sm">
                        <i class="fa fa-folder-open mr-1"></i>
                        Reopen Ticket
                    </button>
                @elseif($ticket->status === 'resolved')
                    <button wire:click="openTicket"
                        class="px-3 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors text-sm">
                        <i class="fa fa-undo mr-1"></i>
                        Reopen
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6 h-[calc(80vh-290px)] md:h-[calc(87vh-300px)] overflow-y-auto custom-scrollbar"
        id="messages-container">
        @foreach ($ticketmessages as $message)
            @if ($message->is_admin)
                <div class="flex items-start space-x-3 justify-end">
                    <div class="flex-1 flex flex-col items-end">
                        <div class="flex items-center space-x-2 mb-1">
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">You</span>
                        </div>
                        <div class="bg-primary-500 dark:bg-primary-700 text-white rounded-lg p-3 max-w-md">
                            <p>{!! nl2br(e($message->message)) !!}</p>
                            @if ($message->image)
                                <div class="mt-2">
                                    <a data-fancybox="chat-{{ $message->ticket_id ?? 'default' }}"
                                        href="{{ $message->image_url }}"
                                        data-caption="{{ show_datetime($message->created_at) }}">
                                        <img src="{{ $message->image_url }}" alt="Uploaded Image"
                                            class="rounded-md max-w-full h-auto"
                                            style="max-height: 300px; cursor: pointer;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div
                        class="w-8 h-8 bg-primary-500 dark:bg-primary-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-medium">AD</span> {{-- Admin --}}
                    </div>
                </div>
            @else
                <div class="flex items-start space-x-3">
                    <div
                        class="w-8 h-8 bg-gray-500 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <span
                            class="text-white text-sm font-medium">{{ strtoupper(substr($ticket->user->name ?? 'U', 0, 2)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $ticket->user->name ?? 'User' }}</span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 max-w-md">
                            <p class="text-gray-800 dark:text-gray-200">{!! nl2br(e($message->message)) !!}</p>
                            @if ($message->image)
                                <div class="mt-2">
                                    <a data-fancybox="chat-{{ $message->ticket_id ?? 'default' }}"
                                        href="{{ $message->image_url }}"
                                        data-caption="{{ show_datetime($message->created_at) }}">
                                        <img src="{{ $message->image_url }}" alt="Uploaded Image"
                                            class="rounded-md max-w-full h-auto"
                                            style="max-height: 300px; cursor: pointer;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    @if ($ticket->status !== 'closed')
        <form wire:submit.prevent="sendMessage" class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="flex space-x-3">
                <div class="flex-1">
                    <textarea wire:model="message" placeholder="Type your reply..." rows="2"
                        class="w-full resize-none border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                        required></textarea>
                    @error('message')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div
                class="flex flex-col md:flex-row md:items-center justify-between mt-4 space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                    <label for="image-upload"
                        class="cursor-pointer hover:text-primary-500 transition-colors flex items-center space-x-2">
                        <i class="fa fa-paperclip"></i>
                        <span>Attach image</span>
                        <input type="file" id="image-upload" wire:model="image" accept="image/*" class="hidden">
                    </label>
                    @if ($image)
                        <div class="items-center space-x-2">
                            <img src="{{ $image->temporaryUrl() }}"
                                class="h-12 w-12 object-cover rounded-md border border-gray-300 dark:border-gray-600 shadow-sm"
                                alt="Preview">
                        </div>
                    @endif
                    @error('image')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex space-x-3 justify-end mt-auto">
                    @if ($ticket->status === 'open')
                        <x-modal name="close-ticket" title="Close Ticket" maxWidth="sm">
                            <p>Are you sure you want to close this ticket?</p>
                            <x-slot name="footer">
                                <button wire:click="closeTicket"
                                    class="px-4 py-2 bg-primary-500 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-600 dark:hover:bg-primary-800 transition-colors">
                                    Close Ticket
                                </button>
                            </x-slot>
                        </x-modal>
                        <button x-on:click="$dispatch('open-modal', { name: 'close-ticket' })"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            Close Ticket
                        </button>
                    @endif
                    <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage"
                        class="px-4 py-2 bg-primary-500 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-600 dark:hover:bg-primary-800 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendMessage">Send Reply</span>
                        <span wire:loading wire:target="sendMessage">Sending...</span>
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-500 dark:text-gray-400">This ticket is closed.</span>
                <x-modal name="reopen-ticket" title="Reopen Ticket" maxWidth="sm">
                    <p>Are you sure you want to reopen this ticket?</p>
                    <x-slot name="footer">
                        <button wire:click="openTicket"
                            class="px-4 py-2 bg-primary-500 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-600 dark:hover:bg-primary-800 transition-colors">
                            Reopen Ticket
                        </button>
                    </x-slot>
                </x-modal>
                <button x-on:click="$dispatch('open-modal', { name: 'reopen-ticket' })"
                    class="px-4 py-2 bg-primary-500 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-600 dark:hover:bg-primary-800 transition-colors">
                    Reopen Ticket
                </button>
            </div>
        </div>
    @endif
</div>

@include('layouts.meta')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <script>
        Fancybox.bind("[data-fancybox]", {});

        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
        });

        document.addEventListener('message-sent', () => {
            setTimeout(scrollToBottom, 100);
            Fancybox.reinit();
        });

        document.addEventListener('ticket-updated', () => {});
    </script>
@endpush
