@props(['title'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
    <div class="p-6 sm:p-8">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $title }}</h3>
        {{ $slot }}
    </div>
</div>