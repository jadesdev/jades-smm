@section('title', $metaTitle)

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="md:flex items-center justify-between">
                <div class="flex items-center space-x-4 mb-2">
                    <!-- User Avatar -->
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center">
                        @if ($user->image)
                            <img src="{{ my_asset($user->image) }}" alt="{{ $user->name }}"
                                class="w-full h-full rounded-full object-cover">
                        @else
                            <span class="text-white text-xl font-bold">{{ $user->initials() }}</span>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        <div class="flex items-center space-x-3 mt-2">
                            <!-- Status Badge -->
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <!-- Verification Badges -->
                            @if ($user->email_verify)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                    <i class="fas fa-envelope-check mr-1"></i> Email Verified
                                </span>
                            @endif

                            @if ($user->sms_verify)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                    <i class="fas fa-mobile-check mr-1"></i> Phone Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button wire:click="toggleEditForm"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-edit mr-2"></i>
                        {{ $showEditForm ? 'Cancel Edit' : 'Edit User' }}
                    </button>

                    <button wire:click="toggleUserStatus"
                        class="inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium {{ $user->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-primary-600 dark:text-primary-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stats['total_orders']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ format_price($stats['total_spent']) }}</p>
                </div>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Balance</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ format_price($user->balance) }}</p>
                </div>
            </div>
        </div>

        <!-- Support Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-life-ring text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Open Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stats['open_tickets']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-3 lg:space-x-2">
                @if (!$user->email_verify)
                    <x-button variant="success" outline wire:click="verifyEmail"> Verify Email </x-button>
                @endif

                @if (!$user->sms_verify)
                    <x-button variant="cyan" outline wire:click="verifySms"> Verify Phone </x-button>
                @endif

                <x-button variant="primary" outline href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}">
                    View Orders
                </x-button>
                <x-button variant="yellow" outline
                    href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}">
                    View Transactions
                </x-button>
                <x-button variant="info" target="_blank" outline href="{{ route('admin.users.login', $user->id) }}">
                    Login as User
                </x-button>
                <x-button variant="blue" outline>
                    Send Email
                </x-button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- User Details & Edit Form -->
        <div class="lg:col-span-2 ">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-2">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                        {{ $showEditForm ? 'Edit User Details' : 'User Details' }}
                    </h3>

                    @if ($showEditForm)
                        <!-- Edit Form -->
                        <form wire:submit="updateUser" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-forms.input wire:model="name" label="Name" />
                                <x-forms.input wire:model="email" label="Email" />
                                <x-forms.input wire:model="username" label="Username" />
                                <x-forms.input wire:model="phone" label="Phone" />
                                <x-forms.input wire:model="country" label="Country" />
                                <x-forms.input wire:model="address" label="Address" />
                            </div>

                            <!-- Password Change -->
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Change Password
                                    (Optional)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <x-forms.input wire:model="password" label="New Password" />
                                    <x-forms.input wire:model="password_confirmation" label="Confirm Password" />
                                </div>
                            </div>

                            <!-- Checkboxes -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex items-center">
                                    <input wire:model="is_active" type="checkbox" id="is_active"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="is_active"
                                        class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active</label>
                                </div>

                                <div class="flex items-center">
                                    <input wire:model="email_verify" type="checkbox" id="email_verify"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="email_verify"
                                        class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Email
                                        Verified</label>
                                </div>

                                <div class="flex items-center">
                                    <input wire:model="sms_verify" type="checkbox" id="sms_verify"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="sms_verify"
                                        class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Phone
                                        Verified</label>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="toggleEditForm"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 shadow-sm">
                                    Update User
                                </button>
                            </div>

                        </form>
                    @else
                        <!-- View Mode -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->username }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $user->phone ?: 'Not provided' }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Country</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $user->country ?: 'Not provided' }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Joined</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            @if ($user->address)
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->address }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Referral Info -->
                @if ($user->referrer || $stats['referrals_count'] > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Referral Information
                            </h3>
                            <div class="space-y-3">
                                @if ($user->referrer)
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-500 dark:text-gray-400">Referred
                                            By</label>
                                        <a href="{{ route('admin.users.view', $user->referrer->id) }}"
                                            class="mt-1 text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            {{ $user->referrer->name }} ({{ $user->referrer->username }})
                                        </a>
                                    </div>
                                @endif

                                @if ($stats['referrals_count'] > 0)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                            Referrals</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ number_format($stats['referrals_count']) }} users</p>

                                        <ul>
                                            @foreach ($user->referrals as $referral)
                                                <li>
                                                    <a href="{{ route('admin.users.view', $referral->id) }}"
                                                        class="mt-1 text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                        {{ $referral->name }} ({{ $referral->username }})
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Recent Activity -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Account Info</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User
                                    ID</label>
                                <p class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $user->id }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Member
                                    Since</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last
                                    Updated</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            @if ($user->api_token)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">API
                                        Access</label>
                                    <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        API Token Active
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Additional Stats -->
        <div class="space-y-6">
            <!-- Balance Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Balance Management</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Balance</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ format_price($user->balance) }}</p>
                            </div>
                        </div>

                        <div x-data="{ amount: '', type: 'add' }" class="space-y-3">
                            <x-forms.input type="number" name="amount" x-model="amount" id="amount"
                                label="Amount" placeholder="0.00" :required="true" step="0.01" min="0" />

                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    @click="if(amount && parseFloat(amount) > 0) { $wire.adjustBalance(parseFloat(amount), 'add'); amount = ''; }"
                                    class="w-full flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                    <i class="fas fa-plus mr-1"></i>
                                    Add
                                </button>
                                <button
                                    @click="if(amount && parseFloat(amount) > 0) { $wire.adjustBalance(parseFloat(amount), 'subtract'); amount = ''; }"
                                    class="w-full flex items-center justify-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                    <i class="fas fa-minus mr-1"></i>
                                    Subtract
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Additional Stats</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Orders</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stats['completed_orders']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Orders</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stats['pending_orders']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Deposits</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ format_price($stats['total_deposits']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Bonus Balance</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ format_price($user->bonus) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Referrals</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stats['referrals_count']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tickets</span>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_tickets']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('layouts.meta')
