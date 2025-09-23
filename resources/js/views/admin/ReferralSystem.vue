<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Referral System</h1>
      <p class="mt-2 text-sm text-gray-600">Manage customer referral program and track performance.</p>
    </div>

    <!-- Referral Performance -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Referrals</dt>
                <dd class="text-lg font-medium text-gray-900">{{ referralStats.totalReferrals.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">{{ referralStats.newThisMonth }} this month</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Successful Conversions</dt>
                <dd class="text-lg font-medium text-gray-900">{{ referralStats.conversions.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">{{ referralStats.conversionRate }}% rate</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Revenue Generated</dt>
                <dd class="text-lg font-medium text-gray-900">${{ referralStats.revenueGenerated.toLocaleString() }}</dd>
                <dd class="text-xs text-yellow-600">From referrals</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Rewards Paid</dt>
                <dd class="text-lg font-medium text-gray-900">${{ referralStats.rewardsPaid.toLocaleString() }}</dd>
                <dd class="text-xs text-purple-600">{{ referralStats.pendingRewards }} pending</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Referral Settings -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Referral Program Settings</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Reward Structure</h3>
            <div class="space-y-4">
              <div>
                <label for="referrer-reward" class="block text-sm font-medium text-gray-700">Referrer Reward</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                  <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">$</span>
                  <input
                    v-model="referralSettings.referrerReward"
                    type="number"
                    id="referrer-reward"
                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="referee-reward" class="block text-sm font-medium text-gray-700">Referee Reward</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                  <input
                    v-model="referralSettings.refereeReward"
                    type="number"
                    id="referee-reward"
                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-r-0 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  />
                  <span class="inline-flex items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">%</span>
                </div>
              </div>

              <div>
                <label for="min-purchase" class="block text-sm font-medium text-gray-700">Minimum Purchase</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                  <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">$</span>
                  <input
                    v-model="referralSettings.minimumPurchase"
                    type="number"
                    id="min-purchase"
                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  />
                </div>
              </div>
            </div>
          </div>

          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Program Settings</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">Enable Referral Program</h4>
                  <p class="text-sm text-gray-500">Allow customers to refer friends</p>
                </div>
                <button
                  @click="referralSettings.enabled = !referralSettings.enabled"
                  :class="referralSettings.enabled ? 'bg-indigo-600' : 'bg-gray-200'"
                  class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out"
                >
                  <span
                    :class="referralSettings.enabled ? 'translate-x-5' : 'translate-x-0'"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                  ></span>
                </button>
              </div>

              <div>
                <label for="reward-limit" class="block text-sm font-medium text-gray-700">Reward Limit per User</label>
                <select
                  v-model="referralSettings.rewardLimit"
                  id="reward-limit"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                  <option value="unlimited">Unlimited</option>
                  <option value="5">5 referrals</option>
                  <option value="10">10 referrals</option>
                  <option value="25">25 referrals</option>
                </select>
              </div>

              <div>
                <label for="reward-expiry" class="block text-sm font-medium text-gray-700">Reward Expiry</label>
                <select
                  v-model="referralSettings.rewardExpiry"
                  id="reward-expiry"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                  <option value="30">30 days</option>
                  <option value="60">60 days</option>
                  <option value="90">90 days</option>
                  <option value="365">1 year</option>
                  <option value="never">Never expires</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button
            @click="saveReferralSettings"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Save Settings
          </button>
        </div>
      </div>
    </div>

    <!-- Recent Referrals -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Recent Referrals</h2>
        <p class="mt-1 text-sm text-gray-500">Latest referral activities and rewards.</p>
      </div>
      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referee</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="referral in recentReferrals" :key="referral.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ referral.referrer_name }}</div>
                <div class="text-sm text-gray-500">{{ referral.referrer_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ referral.referee_name }}</div>
                <div class="text-sm text-gray-500">{{ referral.referee_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ referral.event_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ referral.reward }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  referral.status === 'paid' ? 'bg-green-100 text-green-800' :
                  referral.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]">
                  {{ referral.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(referral.created_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const referralStats = ref({
  totalReferrals: 1456,
  newThisMonth: 89,
  conversions: 987,
  conversionRate: 67.8,
  revenueGenerated: 45670,
  rewardsPaid: 12340,
  pendingRewards: 23
})

const referralSettings = ref({
  enabled: true,
  referrerReward: 10,
  refereeReward: 15,
  minimumPurchase: 25,
  rewardLimit: 'unlimited',
  rewardExpiry: '90'
})

const recentReferrals = ref([
  {
    id: 1,
    referrer_name: 'John Doe',
    referrer_email: 'john@example.com',
    referee_name: 'Jane Smith',
    referee_email: 'jane@example.com',
    event_name: 'Summer Music Festival',
    reward: 10,
    status: 'paid',
    created_at: '2025-01-10'
  },
  {
    id: 2,
    referrer_name: 'Alice Johnson',
    referrer_email: 'alice@example.com',
    referee_name: 'Bob Wilson',
    referee_email: 'bob@example.com',
    event_name: 'Tech Conference',
    reward: 10,
    status: 'pending',
    created_at: '2025-01-09'
  },
  {
    id: 3,
    referrer_name: 'Mary Brown',
    referrer_email: 'mary@example.com',
    referee_name: 'Tom Davis',
    referee_email: 'tom@example.com',
    event_name: 'Art Exhibition',
    reward: 10,
    status: 'paid',
    created_at: '2025-01-08'
  }
])

const saveReferralSettings = () => {
  console.log('Saving referral settings:', referralSettings.value)
  // TODO: Save settings via API
  alert('Referral settings saved successfully!')
}

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch referral data from API
  console.log('Loading referral system data...')
})
</script>
