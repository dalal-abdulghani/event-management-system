<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Marketing & Promotions</h1>
      <p class="mt-2 text-sm text-gray-600">Manage campaigns, discount codes, and promotional activities.</p>
    </div>

    <!-- Marketing Performance Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Active Campaigns</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.activeCampaigns }}</dd>
                <dd class="text-xs text-blue-600">{{ stats.campaignReach.toLocaleString() }} reach</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.conversionRate }}%</dd>
                <dd class="text-xs text-green-600">+2.3% from last month</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Discount Codes</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.activeCoupons }}</dd>
                <dd class="text-xs text-yellow-600">${{ stats.discountSavings.toLocaleString() }} saved</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Referrals</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.referralSignups }}</dd>
                <dd class="text-xs text-purple-600">{{ stats.referralRate }}% rate</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Marketing Tabs -->
    <div class="bg-white shadow rounded-lg">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
          <button
            v-for="tab in tabs"
            :key="tab.name"
            @click="activeTab = tab.name"
            :class="[
              activeTab === tab.name
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Campaigns Tab -->
        <div v-if="activeTab === 'campaigns'">
          <div class="sm:flex sm:items-center mb-6">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">Marketing Campaigns</h3>
              <p class="mt-1 text-sm text-gray-500">Create and manage promotional campaigns across different channels.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <button
                @click="showCreateCampaign = true"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Create Campaign
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="campaign in campaigns" :key="campaign.id" class="bg-gray-50 rounded-lg p-6">
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-medium text-gray-900">{{ campaign.name }}</h4>
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  campaign.status === 'active' ? 'bg-green-100 text-green-800' :
                  campaign.status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ campaign.status }}
                </span>
              </div>
              
              <div class="space-y-3">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Channel</span>
                  <span class="font-medium">{{ campaign.channel }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Reach</span>
                  <span class="font-medium">{{ campaign.reach.toLocaleString() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Clicks</span>
                  <span class="font-medium">{{ campaign.clicks.toLocaleString() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Conversion</span>
                  <span class="font-medium">{{ campaign.conversion }}%</span>
                </div>
              </div>

              <div class="mt-4 flex space-x-2">
                <button class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                  Edit
                </button>
                <button class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                  View Report
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Discount Codes Tab -->
        <div v-if="activeTab === 'discounts'">
          <div class="sm:flex sm:items-center mb-6">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">Discount Codes</h3>
              <p class="mt-1 text-sm text-gray-500">Create and manage promotional discount codes.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <router-link
                to="/admin/marketing/discounts"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Manage Discounts
              </router-link>
            </div>
          </div>

          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="discount in discountCodes" :key="discount.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ discount.code }}</div>
                    <div class="text-sm text-gray-500">{{ discount.description }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ discount.type === 'percentage' ? discount.value + '%' : '$' + discount.value }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ discount.used_count }} / {{ discount.max_uses || '∞' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(discount.expires_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      discount.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ]">
                      {{ discount.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                    <button 
                      @click="deleteCampaign(campaign)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Referral System Tab -->
        <div v-if="activeTab === 'referrals'">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Referral System</h3>
            <p class="mt-1 text-sm text-gray-500">Manage customer referral program and rewards.</p>
          </div>

          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Referral Settings</h4>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Referrer Reward</span>
                  <span class="text-sm font-medium">$10 credit</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Referee Reward</span>
                  <span class="text-sm font-medium">15% discount</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Min Purchase</span>
                  <span class="text-sm font-medium">$25</span>
                </div>
              </div>
              <button class="mt-4 w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Update Settings
              </button>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Performance Metrics</h4>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Total Referrals</span>
                  <span class="text-sm font-medium">{{ referralStats.totalReferrals }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Successful Conversions</span>
                  <span class="text-sm font-medium">{{ referralStats.conversions }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-700">Revenue Generated</span>
                  <span class="text-sm font-medium">${{ referralStats.revenue.toLocaleString() }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
              <h4 class="text-md font-medium text-gray-900">Recent Referrals</h4>
            </div>
            <div class="p-6">
              <div class="space-y-3">
                <div v-for="referral in recentReferrals" :key="referral.id" class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ referral.referrer_name }}</p>
                    <p class="text-xs text-gray-500">referred {{ referral.referee_name }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">${{ referral.reward }}</p>
                    <p class="text-xs text-gray-500">{{ formatDate(referral.date) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Analytics Tab -->
        <div v-if="activeTab === 'analytics'">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Marketing Analytics</h3>
            <p class="mt-1 text-sm text-gray-500">Performance metrics and conversion tracking.</p>
          </div>

          <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Channel Performance</h4>
              <div class="h-64 flex items-center justify-center text-gray-500">
                Channel performance chart placeholder
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Conversion Funnel</h4>
              <div class="h-64 flex items-center justify-center text-gray-500">
                Conversion funnel chart placeholder
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const activeTab = ref('campaigns')
const showCreateCampaign = ref(false)

const stats = ref({
  activeCampaigns: 12,
  campaignReach: 45670,
  conversionRate: 3.8,
  activeCoupons: 8,
  discountSavings: 12450,
  referralSignups: 156,
  referralRate: 12.3
})

const tabs = [
  { name: 'campaigns', label: 'Campaigns' },
  { name: 'discounts', label: 'Discount Codes' },
  { name: 'referrals', label: 'Referral System' },
  { name: 'analytics', label: 'Analytics' }
]

const campaigns = ref([
  {
    id: 1,
    name: 'Summer Festival Promo',
    channel: 'Email + Social',
    status: 'active',
    reach: 12500,
    clicks: 890,
    conversion: 4.2
  },
  {
    id: 2,
    name: 'Early Bird Special',
    channel: 'Facebook Ads',
    status: 'active',
    reach: 8900,
    clicks: 456,
    conversion: 3.1
  },
  {
    id: 3,
    name: 'VIP Experience',
    channel: 'Instagram',
    status: 'scheduled',
    reach: 0,
    clicks: 0,
    conversion: 0
  }
])

const discountCodes = ref([
  {
    id: 1,
    code: 'SUMMER20',
    description: 'Summer festival discount',
    type: 'percentage',
    value: 20,
    used_count: 45,
    max_uses: 100,
    expires_at: '2025-07-01',
    is_active: true
  },
  {
    id: 2,
    code: 'EARLYBIRD',
    description: 'Early bird special',
    type: 'fixed',
    value: 15,
    used_count: 23,
    max_uses: 50,
    expires_at: '2025-06-01',
    is_active: true
  }
])

const referralStats = ref({
  totalReferrals: 234,
  conversions: 156,
  revenue: 12450
})

const recentReferrals = ref([
  {
    id: 1,
    referrer_name: 'John Doe',
    referee_name: 'Jane Smith',
    reward: 10,
    date: '2025-01-10'
  },
  {
    id: 2,
    referrer_name: 'Alice Johnson',
    referee_name: 'Bob Wilson',
    reward: 10,
    date: '2025-01-09'
  }
])

const formatDate = (date: string) => {
  if (!date) return 'No expiry'
  return new Date(date).toLocaleDateString()
}

const deleteCampaign = (campaign: any) => {
  if (confirm(`Are you sure you want to delete campaign "${campaign.name}"?\n\nThis action cannot be undone.`)) {
    const campaignIndex = campaigns.value.findIndex(c => c.id === campaign.id)
    if (campaignIndex !== -1) {
      const deletedCampaign = campaigns.value[campaignIndex]
      campaigns.value.splice(campaignIndex, 1)
      alert(`Campaign "${deletedCampaign.name}" has been deleted successfully!`)
    }
    console.log('Deleted campaign:', campaign.id)
    // TODO: Delete via API
  }
}

onMounted(() => {
  // TODO: Fetch marketing data from API
  console.log('Loading marketing data...')
})
</script>
