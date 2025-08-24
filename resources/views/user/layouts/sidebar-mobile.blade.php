<div id="mobile-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-primary-700 dark:bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between h-16 px-4 bg-primary-800 dark:bg-gray-900">
            <div class="flex items-center">
                <img src="{{ my_asset(get_setting('favicon')) }}" alt="" class="h-6 w-6 mr-2">
                <span class="text-xl font-bold">{{ get_setting('name') }}</span>
            </div>
            <button id="mobile-sidebar-close"
                class="p-2 rounded-md text-primary-200 dark:text-gray-300 hover:text-white hover:bg-primary-600 dark:hover:bg-gray-700">
                <i class="fad fa-times text-lg"></i>
            </button>
        </div>
        @include('user.layouts._menu')
    </div>
</div>
