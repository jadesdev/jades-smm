<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 shadow-lg border-t border-gray-200 dark:border-gray-700 z-30">
    <nav class="flex justify-around">
        <!-- Menu Toggle Button - First Item -->
        <button id="mobile-menu-button"
            class="flex flex-col items-center py-2 px-3 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i class="fas fa-bars text-lg"></i>
            <span class="text-xs mt-1">Menu</span>
        </button>

        <a href="{{ route('user') }}" wire:navigate
            class="flex flex-col items-center py-2 px-3 text-indigo-600 dark:text-indigo-400">
            <i class="fas fa-tachometer-alt text-lg"></i>
            <span class="text-xs mt-1">Dashboard</span>
        </a>

        <a href="#"
            class="flex flex-col items-center py-2 px-3 text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i class="fas fa-shopping-cart text-lg"></i>
            <span class="text-xs mt-1">Orders</span>
        </a>

        <a href="#"
            class="flex flex-col items-center py-2 px-3 text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i class="fas fa-cogs text-lg"></i>
            <span class="text-xs mt-1">Services</span>
        </a>

        <a href="{{ route('user.profile') }}" wire:navigate
            class="flex flex-col items-center py-2 px-3 text-gray-500 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i class="fas fa-user text-lg"></i>
            <span class="text-xs mt-1">Account</span>
        </a>
    </nav>
</div>