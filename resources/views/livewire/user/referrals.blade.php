@section('title', $metaTitle)
<div class="mx-auto rounded-2xl pb-6">
    <!-- Referral Link Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Your Referral Link</h2>
            <div class="flex items-center w-full">
                <x-forms.input name="referral-link" label="" placeholder="Referral Link"
                    value="{{ $referralLink ?? 'https://yoursite.com/register?ref=USERNAME' }}" readonly
                    class="w-full md:w-96 mt-4" />
                <x-button variant="primary" class="ml-3" x-on:click="copyReferralLink">
                    <i class="fad fa-copy"></i>
                </x-button>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-100 dark:bg-blue-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-100">{{ $referralCount ?? 0 }}</div>
            <div class="text-gray-600 dark:text-gray-200">Total Referrals</div>
        </div>
        <div class="bg-green-100 dark:bg-green-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-700 dark:text-green-100">{{ $referralRate ?? 3 }} %</div>
            <div class="text-gray-600 dark:text-gray-200">Commission Rate</div>
        </div>
        <div class="bg-yellow-100 dark:bg-yellow-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-100">{{ $minWithdrawal ?? 10 }} </div>
            <div class="text-gray-600 dark:text-gray-200">Min Withdrawal</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">Recent Referrals</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            User</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Joined</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Earned</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($recentReferrals as $referral)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                        @if (isset($referral['avatar']) && $referral['avatar'])
                                            <img class="h-10 w-10 rounded-full" src="{{ $referral['avatar'] }}"
                                                alt="">
                                        @else
                                            <span
                                                class="text-gray-600 dark:text-gray-300 font-medium">{{ strtoupper(substr($referral['username'], 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $referral['username'] }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $referral['email'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                {{ $referral['created_at']->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $referral['status'] === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                            {{ $referral['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                            {{ $referral['status'] === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                    {{ ucfirst($referral['status']) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                ${{ number_format($referral['earned'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>


@include('layouts.meta')
