@section('title', $metaTitle)
<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    @if ($view == 'edit')
                        Edit Template: {{ $name }}
                    @else
                        Notification Templates
                    @endif
                </h2>
                @if ($view == 'edit')
                    <div>
                        <x-button wire:click="backToList" variant="primary" outline>
                            <i class="fa fa-arrow-left mr-2"></i>
                            Back to List
                        </x-button>
                    </div>
                @endif
            </div>
        </x-slot>

        @if ($view == 'edit')
            {{-- Edit View --}}
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Form Fields --}}
                    <div class="lg:col-span-2 space-y-6">
                        <x-forms.input wire:model.defer="subject" name='subject' id="subject" type="text"
                            label="Email Subject" class="w-full" />

                        {{-- Note: For a rich text editor, you'll need Alpine.js to sync the data. --}}
                        <x-forms.rich-editor wire:model.defer="content" name="content" id="content"
                            label="Email Content" class="w-full" rows="15" />

                        <x-forms.select wire:model.defer="email_status" name="email_status" id="email_status"
                            label="Status" class="w-full" :options="[
                                1 => 'Active',
                                0 => 'Inactive',
                            ]">
                        </x-forms.select>
                    </div>

                    {{-- Shortcodes --}}
                    <div class="lg:col-span-1">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg h-full">
                            <h4 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Available Shortcodes
                            </h4>
                            <div class="overflow-y-auto max-h-[500px] border dark:border-gray-700 rounded">
                                <table class="min-w-full text-sm">
                                    <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th class="p-2 text-left font-medium text-gray-600 dark:text-gray-300">Code
                                            </th>
                                            <th class="p-2 text-left font-medium text-gray-600 dark:text-gray-300">
                                                Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($shortcodes as $shortcode => $key)
                                            <tr>
                                                <td class="p-2 font-mono text-gray-700 dark:text-gray-300">
                                                    &#123;&#123;{{ $shortcode }}&#125;&#125;
                                                </td>
                                                <td class="p-2 text-gray-600 dark:text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach (get_setting('shortcodes') as $shortcode => $key)
                                            <tr>
                                                <td class="p-2 font-mono text-gray-700 dark:text-gray-300">
                                                    {{-- {{ '{' . $shortcode . '}' }} --}}
                                                    &#123;&#123;{{ $shortcode }}&#125;&#125;
                                                </td>
                                                <td class="p-2 text-gray-600 dark:text-gray-400">{{ $key }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                        Update Template
                    </x-button>
                </div>
            </form>
        @else
            {{-- List View --}}
            <div>
                {{-- Search and Filter Row --}}
                <div class="mb-6 flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <x-forms.input wire:model.live.debounce.300ms="search" type="text" name="search"
                            id="search" placeholder="Search templates..." class="w-full" />
                    </div>
                    <div class="min-w-48">
                        <x-forms.select wire:model.live="perPage" name="perPage" id="perPage" class="w-full">
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </x-forms.select>
                    </div>
                </div>

                {{-- Templates Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                    <button wire:click="sortBy('name')"
                                        class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                        <span>Name</span>
                                        @if ($sortField === 'name')
                                            <i
                                                class="fa fa-{{ $sortDirection === 'asc' ? 'caret-up' : 'caret-down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                    Subject</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                    <button wire:click="sortBy('email_status')"
                                        class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                        <span>Status</span>
                                        @if ($sortField === 'email_status')
                                            <i
                                                class="fa fa-{{ $sortDirection === 'asc' ? 'caret-up' : 'caret-down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="relative px-6 py-3 text-right">
                                    <span class="text-right font-medium text-gray-500 dark:text-gray-300">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($templates as $template)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ e($template->name) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ e($template->type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ e($template->subject) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($template->email_status)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <x-button wire:navigate
                                            href="{{ route('admin.email.templates.edit', $template->id) }}"
                                            variant="info" size="sm">
                                            <i class="fa fa-edit"></i>
                                        </x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        No notification templates found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $templates->links() }}
                </div>
            </div>
        @endif
    </x-card>
</div>

@include('layouts.meta')
