<nav class="flex-1 px-2 py-4 space-y-1 side-menu overflow-y-auto">
    {{-- Dashboard --}}
    <x-sidebar.menu-item href="{{ route('admin.dashboard') }}" icon="fad fa-tachometer-alt" name="Dashboard" />

    <h6 class="font-medium text-sm tracking-wider text-gray-400 dark:text-gray-500 uppercase">Services</h6>
    <x-sidebar.menu-item href="{{ route('admin.api-providers') }}" icon="fad fa-plug" name="API Providers" />
    <x-sidebar.menu-item href="{{ route('admin.categories') }}" icon="fad fa-tags" name="Categories" />
    <x-sidebar.menu-item href="{{ route('admin.services') }}" icon="fad fa-concierge-bell" name="Services" />

    <h6 class="font-medium text-sm tracking-wider text-gray-400 dark:text-gray-500 uppercase">Finance</h6>
    <x-sidebar.menu-item href="{{ route('admin.dashboard') }}" icon="fad fa-shopping-cart" name="Orders" />
    <x-sidebar.menu-item href="{{ route('admin.dashboard') }}" icon="fad fa-money-check-alt" name="Transactions" />

    <h6 class="font-medium text-sm tracking-wider text-gray-400 dark:text-gray-500 uppercase">Management</h6>
    <x-sidebar.submenu icon="fad fa-users" name="Users" :submenu-items="[
        [
            'href' => route('admin.users'),
            'name' => 'All Users',
        ],
        [
            'href' => route('admin.users.settings'),
            'name' => 'Settings',
        ]
    ]" />

    <h6 class="font-medium text-sm tracking-wider text-gray-400 dark:text-gray-500 uppercase">Support</h6>
    <x-sidebar.submenu icon="fad fa-headset" name="Support Tickets" :submenu-items="[
        [
            'href' => route('admin.support.tickets', 'open'),
            'name' => 'Open Tickets',
        ],
        [
            'href' => route('admin.support.tickets', 'closed'),
            'name' => 'Closed Tickets',
        ],
        [
            'href' => route('admin.support.tickets'),
            'name' => 'All Tickets',
        ],
    ]" />

    <h6 class="font-medium text-sm tracking-wider text-gray-400 dark:text-gray-500 uppercase">Settings</h6>
    <x-sidebar.submenu icon="fad fa-envelope" name="Email" :submenu-items="[
        [
            'href' => '#',
            'name' => 'Email Templates',
        ],
        [
            'href' => '#',
            'name' => 'Settings',
        ],
        [
            'href' => route('admin.dashboard'),
            'name' => 'Bulk Email',
        ],
    ]" />

    <x-sidebar.submenu icon="fad fa-cogs" name="Settings" :submenu-items="[
        [
            'href' => route('admin.settings'),
            'name' => 'General',
        ],
        [
            'href' => route('admin.settings.payment'),
            'name' => 'Payment',
        ],
        [
            'href' => route('admin.settings.features'),
            'name' => 'Features',
        ],
    ]" />
</nav>
