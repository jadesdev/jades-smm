<div x-data="{ serviceType: 'default' }">
    <x-developer.doc-section title="Add order">

        <div class="mb-6">
            <select id="service_type" x-on:change="serviceType = $event.target.value"
                class="w-full max-w-xs px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="default">Default</option>
                <option value="package">Package</option>
                <option value="custom_comments">Custom Comments</option>
                <option value="comment_likes">Comment Likes</option>
                <option value="poll">Poll</option>
                <option value="invites_from_groups">Invites from Groups</option>
                <option value="subscriptions">Subscriptions</option>
            </select>
        </div>

        <!-- Default Type -->
        <div x-show="serviceType === 'default'">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
                ['quantity', 'Needed quantity'],
                ['runs (optional)', 'Runs to deliver'],
                ['interval (optional)', 'Interval in minutes'],
            ]" />
        </div>

        <!-- Package Type -->
        <div x-show="serviceType === 'package'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
            ]" />
        </div>

        <!-- Custom Comments Type -->
        <div x-show="serviceType === 'custom_comments'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
                ['comments', 'Comments list separated by \\r\\n or \\n'],
            ]" />
        </div>

        <!-- Comment Likes Type -->
        <div x-show="serviceType === 'comment_likes'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
                ['quantity', 'Needed quantity'],
                ['username', 'Username of the comment owner'],
            ]" />
        </div>

        <!-- Poll Type -->
        <div x-show="serviceType === 'poll'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
                ['quantity', 'Needed quantity'],
                ['answer_number', 'Answer number of the poll'],
            ]" />
        </div>

        <!-- Invites from Groups Type -->
        <div x-show="serviceType === 'invites_from_groups'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['link', 'Link to page'],
                ['quantity', 'Needed quantity'],
                ['groups', 'Groups list separated by \\r\\n or \\n'],
            ]" />
        </div>

        <!-- Subscriptions Type -->
        <div x-show="serviceType === 'subscriptions'" style="display: none;">
            <x-developer.param-table :rows="[
                ['key', 'Your API key'],
                ['action', 'add'],
                ['service', 'Service ID'],
                ['username', 'Username'],
                ['min', 'Quantity min'],
                ['max', 'Quantity max'],
                ['posts (optional)', 'Use to limit new posts parsed. If not set, subscription is unlimited.'],
                ['old_posts (optional)', 'Number of existing posts parsed if available for service.'],
                ['delay', 'Delay in minutes (0, 5, ..., 600)'],
                ['expiry (optional)', 'Expiry date in d/m/Y format'],
            ]" />
        </div>

        <x-developer.example-code>
{
    "order": 23501
}
        </x-developer.example-code>

    </x-developer.doc-section>
</div>
