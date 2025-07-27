@props(['status'])

@php
    $colors = match (strtolower($status ?? '')) {
        'completed' => 'bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-300',
        'inprogress' => 'bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-300',
        'processing' => 'bg-cyan-100 dark:bg-cyan-700 text-cyan-800 dark:text-cyan-300',
        'pending' => 'bg-yellow-100 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-300',
        'partial' => 'bg-orange-100 dark:bg-orange-700 text-orange-800 dark:text-orange-300',
        'canceled' => 'bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-300',
        'refunded' => 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300',
        default => 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300',
    };
@endphp

@if ($status)
    <span
        {{ $attributes->merge(['class' => 'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' . $colors]) }}>
        {{ Str::title(str_replace('_', ' ', $status)) }}
    </span>
@endif
