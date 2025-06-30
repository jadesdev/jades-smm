@section('title', $metaTitle)
<div class="pb-6" x-data>
    <!-- Section 1: API Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
        <div class="p-6 sm:p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">API Documentation</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">All responses are in JSON format.</p>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 w-40">HTTP Method
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">POST</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">API URL</td>
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white"> {{ url('api/v2') }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">API Key</td>
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">
                                Get an API key on the <a href="{{ route('user.profile') }}"
                                    class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline">Account</a>
                                page
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">Response format
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">JSON</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Section 2: Service List -->
    <x-developer.doc-section title="Service list">

        <x-developer.param-table :rows="[['key', 'Your API key'], ['action', 'services']]" />

        <x-developer.example-code>
[
    {
        "service": 1,
        "name": "Followers",
        "type": "Default",
        "category": "First Category",
        "rate": "0.90",
        "min": "50",
        "max": "10000",
        "refill": true,
        "cancel": true
    },
    {
        "service": 2,
        "name": "Comments",
        "type": "Custom Comments",
        "category": "Second Category",
        "rate": "8",
        "min": "10",
        "max": "1500",
        "refill": false,
        "cancel": true
    }
]
        </x-developer.example-code>

    </x-developer.doc-section>

    <!-- Section 3: Add Order -->
    <x-developer.add-order-section />
    <!-- Section 4: Order Status (Single) -->
    <x-developer.doc-section title="Order status">

        <x-developer.param-table :rows="[['key', 'Your API key'], ['action', 'status'], ['order', 'Order ID']]" />

        <x-developer.example-code>
{
    "charge": "0.27819",
    "start_count": "3572",
    "status": "Partial",
    "remains": "157",
    "currency": "USD"
}
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 5: Multiple Orders Status -->
    <x-developer.doc-section title="Multiple orders status">

        <x-developer.param-table :rows="[
            ['key', 'Your API key'],
            ['action', 'status'],
            ['orders', 'Order IDs (separated by a comma, up to 100 IDs)'],
        ]" />

        <x-developer.example-code>
[
    "1": {
        "charge": "0.27819",
        "start_count": "3572",
        "status": "Partial",
        "remains": "157",
        "currency": "USD"
    },
    "10": {
        "error": "Incorrect order ID"
    },
    "100": {
        "charge": "1.44219",
        "start_count": "234",
        "status": "In progress",
        "remains": "10",
        "currency": "USD"
    }
]
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 6: Create Refill (Single) -->
    <x-developer.doc-section title="Create refill">

        <x-developer.param-table :rows="[['key', 'Your API key'], ['action', 'refill'], ['order', 'Order ID']]" />

        <x-developer.example-code>
{
    "refill": "1"
}
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 7: Create Multiple Refill -->
    <x-developer.doc-section title="Create multiple refill">

        <x-developer.param-table :rows="[
            ['key', 'Your API key'],
            ['action', 'refill'],
            ['orders', 'Order IDs (separated by a comma, up to 100 IDs)'],
        ]" />

        <x-developer.example-code>
[
    {
        "order": 1,
        "refill": 1
    },
    {
        "order": 2,
        "refill": 2
    },
    {
        "order": 3,
        "refill": {
            "error": "Incorrect order ID"
        }
    }
]
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 8: Get Refill Status (Single) -->
    <x-developer.doc-section title="Get refill status">

        <x-developer.param-table :rows="[['key', 'Your API key'], ['action', 'refill_status'], ['refill', 'Refill ID']]" />

        <x-developer.example-code>
{
    "status": "Completed"
}
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 9: Get Multiple Refill Status -->
    <x-developer.doc-section title="Get multiple refill status">

        <x-developer.param-table :rows="[
            ['key', 'Your API key'],
            ['action', 'refill_status'],
            ['refills', 'Refill IDs (separated by a comma, up to 100 IDs)'],
        ]" />

        <x-developer.example-code>
[
    {
        "refill": 1,
        "status": "Completed"
    },
    {
        "refill": 2,
        "status": "Rejected"
    },
    {
        "refill": 3,
        "status": {
            "error": "Refill not found"
        }
    }
]
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 10: Cancel Orders -->
    <x-developer.doc-section title="Cancel Orders">

        <x-developer.param-table :rows="[
            ['key', 'Your API key'],
            ['action', 'cancel'],
            ['orders', 'Order IDs (separated by a comma, up to 100 IDs)'],
        ]" />

        <x-developer.example-code>
[
    {
        "order": 9,
        "cancel": {
            "error": "Incorrect order ID"
        }
    },
    {
        "order": 2,
        "cancel": 1
    }
]
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Section 11: User Balance -->
    <x-developer.doc-section title="User balance">

        <x-developer.param-table :rows="[['key', 'Your API key'], ['action', 'balance']]" />

        <x-developer.example-code>
{
    "balance": "100.84292",
    "currency": "USD"
}
        </x-developer.example-code>

    </x-developer.doc-section>
    <!-- Download Button -->
    <div class="text-center mb-6">
        <a href="/example.txt" target="_blank"
            class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                </path>
            </svg>
            Example of PHP code
        </a>
    </div>
</div>

<script>
    function showServiceType(value) {
        // Hide all service types
        document.querySelectorAll('.service-type').forEach(el => {
            el.classList.add('hidden');
        });

        // Show selected service type
        const selectedType = document.getElementById(`type_${value}`);
        if (selectedType) {
            selectedType.classList.remove('hidden');
        }
    }
</script>

@include('layouts.meta')
