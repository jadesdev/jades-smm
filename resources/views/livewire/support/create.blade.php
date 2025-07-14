@section('title', $metaTitle)

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-10">
    <div x-data="{ subject: 'Order Inquiry' }" class="p-4 bg-white dark:bg-gray-800 rounded-2xl">
        <div class="flex items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Create Support Ticket</h3>
        </div>
        <form wire:submit.prevent="submit" class="space-y-6">
            @csrf
            <x-forms.select name="subject" label="Subject" :required="true" wire:model.live='subject' x-model="subject"
                :options="$subjectOptions" />

            {{-- Order Inquiry Fields --}}
            @if ($this->isOrderInquiry())
                <x-forms.input name="orderid" wire:model="orderid" id="orderid" label="Your Order Number"
                    placeholder="Enter your order number" :required="true" />
                <x-forms.select name="want" wire:model="want" id="want" label="Specify Your Request"
                    placeholder="Please Select" :required="true" :options="$requestOptions" />
            @endif

            {{-- Payment Notification Fields --}}
            @if ($this->isPaymentNotification())
                <x-forms.select name="payment" wire:model="payment" id="payment" label="Payment Method"
                    placeholder="Please Select" :required="true" :options="$paymentOptions" />
                <x-forms.input name="transactionId" wire:model="transactionId" id="transactionId" label="Sender Name"
                    placeholder="Enter sender name" :required="true" />
                <x-forms.input type="number" name="addamount" wire:model="addamount" id="addamount" label="Sent Amount"
                    placeholder="0.00" :required="true" step="0.01" />
            @endif

            {{-- Message Field --}}
            <x-forms.textarea name="message" wire:model="message" id="message" rows="5"
                label="Message {{ $this->isOrderInquiry() || $this->isPaymentNotification() ? '(optional)' : '' }}"
                placeholder="Please provide additional details about your request..." :required="true" />

            {{-- Image Upload --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Attach Image (optional)
                </label>
                <input type="file" wire:model="image" id="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('image')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                {{-- Image Preview --}}
                @if ($image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg">
                    </div>
                @endif
            </div>


            <x-button type="submit" variant="primary" class="w-full justify-center py-4" wire:loading.attr="disabled">
                <span wire:loading.remove>Send Support Request</span>
                <span wire:loading>Creating Ticket...</span>
            </x-button>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                <i class="fa fa-clock mr-1"></i>
                We typically respond within 24 hours during business days
            </p>
        </form>
    </div>

    <div class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Support Tickets</h3>
            <span
                class="bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-200 text-sm font-medium px-3 py-1 rounded-full">
                {{ $tickets->where('status', 'active')->count() }} Active
            </span>
        </div>

        @forelse ($tickets as $ticket)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer"
                onclick="openLink('{{ route('user.support.view', $ticket->code) }}')">

                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-2 h-2 bg-{{ $ticket->status_color }}-500 dark:bg-{{ $ticket->status_color }}-700 rounded-full">
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100">#{{ $ticket['code'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket['subject'] }}</p>
                        </div>
                    </div>
                    <span
                        class="bg-{{ $ticket->status_color }}-100 dark:bg-{{ $ticket->status_color }}-200 
                        text-{{ $ticket->status_color }}-800 dark:text-{{ $ticket->status_color }}-200 
                        text-xs font-medium px-2 py-1 rounded-full">
                        {{ $ticket['status'] }}
                    </span>
                </div>

                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    {{ $ticket->lastMessage?->message ?? 'No message' }}</p>

                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ $ticket->latestMessage?->created_at->diffForHumans() ?? 'No message' }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                <i class="fad fa-ticket text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No support tickets</h3>
                <p class="text-gray-600 dark:text-gray-400">Your support tickets will appear here when you create them.
                </p>
            </div>
        @endforelse

    </div>

</div>

@include('layouts.meta')
