@section('title', $metaTitle)

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-10">
    <div x-data="{ subject: 'Order Inquiry' }" class="p-4 bg-white dark:bg-gray-800 rounded-2xl">
        <div class="flex items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Create Support Ticket</h3>
        </div>
        <form method="post" action="#" id="ticketsend" class="space-y-6">
            @csrf
            <x-forms.select name="subject" label="Subject" :required="true" x-model="subject" :options="$subjectOptions" />

            <div x-show="subject === 'Order Inquiry'" class="space-y-4">
                <x-forms.input name="orderid" label="Your Order Number" placeholder="Enter your order number"
                    :required="true" />
                <x-forms.select name="want" label="Specify Your Request" placeholder="Please Select"
                    :required="true" :options="$requestOptions" />
            </div>

            <div x-show="subject === 'Payment Notification'" style="display: none;" class="space-y-4">
                <x-forms.select name="payment" label="Payment Method" :required="true" :options="$paymentOptions" />
                <x-forms.input name="Transaction[ID]" label="Sender Name" placeholder="Enter sender name"
                    :required="true" />
                <x-forms.input type="number" name="addamount[ID]" label="Sent Amount" placeholder="0.00"
                    :required="true" step="0.01" />
            </div>

            <x-forms.textarea name="message" label="Message (optional)"
                placeholder="Please provide additional details about your request..." :rows="5" />

            <input id="hmessage" name="hmessage" type="hidden">

            <x-button type="submit" variant="primary" class="w-full justify-center py-4">
                Send Support Request
            </x-button>

            <p class="text-sm text-gray-600 dark:text-gray-200 text-center">
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
                class="bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-200 text-sm font-medium px-3 py-1 rounded-full">12
                Active</span>
        </div>

        <!-- Ticket Item -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer"
            onclick="openLink('{{ route('user.support.view', 1) }}')">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-500 dark:bg-green-700 rounded-full"></div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">#TKT-2024-001</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Order Inquiry</p>
                    </div>
                </div>
                <span
                    class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs font-medium px-2 py-1 rounded-full">Open</span>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">I think there is an error in my order, can you
                check it?</p>
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>2 hours ago</span>
                <span>Order #ORD-12345</span>
            </div>
        </div>

        <!-- Ticket Item -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer"
            onclick="openLink('{{ route('user.support.view', 2) }}')">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-yellow-500 dark:bg-yellow-700 rounded-full"></div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">#TKT-2024-002</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Notification</p>
                    </div>
                </div>
                <span
                    class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">Bank transfer confirmation for recent payment</p>
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>1 day ago</span>
                <span>Amount: ₦50,000</span>
            </div>
        </div>

        <!-- Ticket Item -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer"
            onclick="openLink('{{ route('user.support.view', 3) }}')">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">#TKT-2024-003</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Other</p>
                    </div>
                </div>
                <span
                    class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2 py-1 rounded-full">Closed</span>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">General inquiry about account settings</p>
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>3 days ago</span>
                <span>Resolved</span>
            </div>
        </div>

        <!-- Empty State (if no tickets) -->
        <div class="text-center py-12">
            <i class="fa fa-ticket-alt text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No support tickets</h3>
            <p class="text-gray-600">Your support tickets will appear here when you create them.</p>
        </div>
    </div>

</div>

@include('layouts.meta')
