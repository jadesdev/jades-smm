@section('title', $metaTitle)

<div class="mx-auto rounded-2xl pb-6" x-data="{ showTooltip: false }">
    <!-- Loading State -->
    @if ($isLoading)
        <div class="flex justify-center items-center min-h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
        </div>
    @else
        <!-- Referral Link Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Your Referral Program</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Invite friends and earn {{ $referralRate }}% commission on their first purchase!
            </p>

            <div x-data="copyLinkComponent()" class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <!-- Referral Link Input -->
                <div class="flex-1 w-full">
                    <x-forms.input wire:model="referralLink" name="referral-link" label="Your Unique Referral Link"
                        placeholder="Generating your link..." readonly class="w-full" x-ref="referralInput" />
                </div>

                <!-- Copy Button -->
                <x-button type="button" variant="primary" class="w-full md:w-auto py-3 !mt-3" @click="copyText()"
                    x-bind:class="{ '!bg-green-600 !hover:bg-green-700': copied }">
                    <template x-if="!copied">
                        <span><i class="fad fa-copy mr-2"></i> Copy Link</span>
                    </template>
                    <template x-if="copied">
                        <span><i class="fad fa-check mr-2"></i> Copied!</span>
                    </template>
                </x-button>
            </div>

        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-900 rounded-lg p-6 text-center transition-all hover:shadow-md">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $referralCount }}</div>
                <div class="text-sm font-medium text-blue-800 dark:text-blue-200">Total Referrals</div>
            </div>
            <div
                class="bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-900 rounded-lg p-6 text-center transition-all hover:shadow-md">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">{{ $referralRate }}%</div>
                <div class="text-sm font-medium text-green-800 dark:text-green-200">Commission Rate</div>
            </div>
            <div
                class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-100 dark:border-yellow-900 rounded-lg p-6 text-center transition-all hover:shadow-md">
                <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                    ${{ number_format($totalEarned, 2) }}</div>
                <div class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Total Earned</div>
            </div>
        </div>

        <!-- Recent Referrals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Referrals</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your most recent referred users</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Joined
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Earned
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentReferrals as $referral)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                            @if (isset($referral['image']) && $referral['image'])
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ $referral['image'] }}" alt="">
                                            @else
                                                <span class="text-gray-600 dark:text-gray-300 font-medium">
                                                    {{ strtoupper(substr($referral['username'], 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $referral['username'] }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $referral['email'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $referral['created_at']->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $referral['is_active'] == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                        {{ $referral['is_active'] == 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                        {{ $referral['is_active'] == 2 ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                        {{ $referral['is_active'] == 1 ? 'Active' : ($referral['is_active'] == 0 ? 'Pending' : 'Inactive') }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                    ${{ number_format($referral['bonus'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fad fa-users-slash text-3xl text-gray-300 dark:text-gray-600"></i>
                                        <p>No referrals yet. Share your link to start earning!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function copyLinkComponent() {
            return {
                copied: false,
                copyText() {
                    const input = this.$refs.referralInput;
                    if (input) {
                        input.select();
                        input.setSelectionRange(0, 99999); // For mobile
                        navigator.clipboard.writeText(input.value).then(() => {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        });
                        JDVToast.success('Link copied to clipboard!');
                    }
                }
            }
        }
    </script>
@endpush
