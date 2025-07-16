@props(['status'])

<span @class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $status,
    'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' => ! $status,
])>
    {{ $status ? 'Active' : 'Inactive' }}
</span>
