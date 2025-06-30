@props(['headers' => ['Parameters', 'Description'], 'rows' => []])

<div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 w-40">
                        {{ $headers[0] }}
                    </th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                        {{ $headers[1] }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @foreach ($rows as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-white font-mono">
                                {!! $row[0] !!}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                {!! $row[1] !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>