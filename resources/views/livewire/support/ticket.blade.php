@section('title', $metaTitle)

<div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 sm:mb-8">
    <!-- Ticket Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">#TKT-2024-001</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Order Inquiry</p>
            </div>
            <div class="text-end">
                <span
                    class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-sm font-medium px-3 py-1 rounded-full">Open</span>
                <p class="font-medium ml-2 dark:text-white">2 hours ago</p>
            </div>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="p-6 space-y-6 h-[calc(80vh-290px)] md:h-[calc(87vh-300px)] overflow-y-auto custom-scrollbar">
        <!-- User Message -->
        <div class="flex items-start space-x-3">
            <div
                class="w-8 h-8 bg-primary-500 dark:bg-primary-700 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-medium">U</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-medium text-gray-900 dark:text-white">You</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 max-w-md">
                    <p class="text-gray-800 dark:text-gray-200">I think there is an error in my order, can you check it?
                        The quantity doesn't match what I ordered and the total amount seems incorrect.</p>
                </div>
            </div>
        </div>

        <!-- Admin Reply -->
        <div class="flex items-start space-x-3 justify-end">
            <div class="flex-1 flex flex-col items-end">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</span>
                    <span class="font-medium text-gray-900 dark:text-white">Support Team</span>
                </div>
                <div class="bg-primary-500 dark:bg-primary-700 text-white rounded-lg p-3 max-w-md">
                    <p>Hello! Thank you for reaching out. I've reviewed your order #ORD-12345 and I can see the
                        discrepancy you mentioned. Let me investigate this further and get back to you with a solution.
                    </p>
                </div>
            </div>
            <div
                class="w-8 h-8 bg-green-500 dark:bg-green-700 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-medium">S</span>
            </div>
        </div>

        <!-- User Follow-up -->
        <div class="flex items-start space-x-3">
            <div
                class="w-8 h-8 bg-primary-500 dark:bg-primary-700 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-medium">U</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-medium text-gray-900 dark:text-white">You</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">45 minutes ago</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 max-w-md">
                    <p class="text-gray-800 dark:text-gray-200">Thank you for the quick response! I'll wait for your
                        update.</p>
                </div>
            </div>
        </div>

        <!-- Admin Final Reply -->
        <div class="flex items-start space-x-3 justify-end">
            <div class="flex-1 flex flex-col items-end">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xs text-gray-500 dark:text-gray-400">30 minutes ago</span>
                    <span class="font-medium text-gray-900 dark:text-white">Support Team</span>
                </div>
                <div class="bg-primary-500 dark:bg-primary-700 text-white rounded-lg p-3 max-w-md">
                    <p>Good news! I've corrected the order details and processed a refund for the difference. You should
                        see the adjustment in your account within 24 hours. Is there anything else I can help you with?
                    </p>
                </div>
            </div>
            <div
                class="w-8 h-8 bg-green-500 dark:bg-green-700 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-medium">S</span>
            </div>
        </div>
    </div>

    <!-- Reply Input -->
    <div class="border-t border-gray-200 dark:border-gray-700 p-4">
        <div class="flex space-x-3">
            <div class="flex-1">
                <x-forms.textarea name="message" label="" placeholder="Type your reply..." :rows="2"
                    class="resize-none" />
            </div>
        </div>
        <div class="flex items-center justify-between mt-3">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <i class="fa fa-paperclip"></i>
                <span>Attach file</span>
            </div>
            <div class="flex space-x-2">
                <button
                    class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Close Ticket
                </button>
                <button
                    class="px-4 py-2 bg-primary-500 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-600 dark:hover:bg-primary-800 transition-colors">
                    Send Reply
                </button>
            </div>
        </div>
    </div>
</div>

@include('layouts.meta')