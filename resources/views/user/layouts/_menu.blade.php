<nav class="flex-1 px-2 py-4 space-y-1 side-menu">
    {{-- Dashboard --}}
    <a href="{{ route('user.dashboard') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md bg-primary-800 dark:bg-gray-700 text-white">
        <i class="fad fa-home mr-3 w-5"></i>
        Dashboard
    </a>

    {{-- New Order --}}
    <a href="{{ route('user.orders.create') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-plus-circle mr-3 w-5"></i>
        New Order
    </a>

    {{-- Orders --}}
    <a href="{{ route('user.orders') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-bags-shopping mr-3 w-5"></i>
        Orders
    </a>
    {{-- Wallet --}}
    <a href="{{ route('user.wallet') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-wallet mr-3 w-5"></i>
        Wallet
    </a>

    {{-- Services --}}
    <a href="{{ route('user.services') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-list mr-3 w-5"></i>
        Services
    </a>

    {{-- Support --}}
    <a href="{{ route('user.support') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-comment-alt mr-3 w-5"></i>
        Support
    </a>

    {{-- Referrals --}}
    <a href="{{ route('user.referrals') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-users mr-3 w-5"></i>
        Referrals
    </a>

    {{-- Developer Api --}}
    <a href="{{ route('user.developer') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-code mr-3 w-5"></i>
        Developer Api
    </a>

    {{-- Account --}}
    <a href="{{ route('user.profile') }}" wire:navigate
        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-primary-200 dark:text-gray-300 hover:bg-primary-600 dark:hover:bg-gray-700 hover:text-white">
        <i class="fad fa-user-circle mr-3 w-5"></i>
        Account
    </a>
</nav>
