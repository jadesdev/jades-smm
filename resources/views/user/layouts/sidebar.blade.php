<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64 bg-primary-700 dark:bg-gray-800 text-white">
        <div class="flex items-center h-16 px-4 bg-primary-800 dark:bg-gray-900">
            <img src="{{ my_asset(get_setting('favicon')) }}" alt="" class="h-6 w-6 mr-2">
            <span class="text-xl font-bold">{{ get_setting('name') }}</span>
        </div>
        @include('user.layouts._menu')
    </div>
</div>
