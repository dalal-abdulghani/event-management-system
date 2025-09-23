<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Marketing Campaigns</h1>
      <p class="mt-2 text-sm text-gray-600">Create and manage promotional campaigns across different channels.</p>
    </div>

    <!-- Campaign Performance -->
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
                <dd class="text-lg font-medium text-gray-900">{{ campaignStats.active }}</dd>
                <dd class="text-xs text-blue-600">{{ campaignStats.scheduled }} scheduled</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Reach</dt>
                <dd class="text-lg font-medium text-gray-900">{{ campaignStats.totalReach.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">{{ campaignStats.reachGrowth }}% growth</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Click-through Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ campaignStats.ctr }}%</dd>
                <dd class="text-xs text-yellow-600">{{ campaignStats.totalClicks.toLocaleString() }} clicks</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ campaignStats.conversionRate }}%</dd>
                <dd class="text-xs text-purple-600">{{ campaignStats.conversions }} conversions</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Campaign List -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">All Campaigns</h2>
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
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="campaign in campaigns" :key="campaign.id" class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-md font-medium text-gray-900">{{ campaign.name }}</h3>
              <span :class="[
                'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                campaign.status === 'active' ? 'bg-green-100 text-green-800' :
                campaign.status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                campaign.status === 'paused' ? 'bg-yellow-100 text-yellow-800' :
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
                <span class="text-gray-500">Budget</span>
                <span class="font-medium">${{ campaign.budget.toLocaleString() }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Spent</span>
                <span class="font-medium">${{ campaign.spent.toLocaleString() }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Reach</span>
                <span class="font-medium">{{ campaign.reach.toLocaleString() }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Conversions</span>
                <span class="font-medium">{{ campaign.conversions }}</span>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
              <div class="flex justify-between text-xs text-gray-500 mb-2">
                <span>Progress</span>
                <span>{{ Math.round((campaign.spent / campaign.budget) * 100) }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                  class="bg-indigo-600 h-2 rounded-full" 
                  :style="{ width: `${Math.min((campaign.spent / campaign.budget) * 100, 100)}%` }"
                ></div>
              </div>
            </div>

            <div class="mt-4 flex space-x-2">
              <button 
                @click="editCampaign(campaign)"
                class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Edit
              </button>
              <button 
                @click="viewCampaignReport(campaign)"
                class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Report
              </button>
              <button 
                @click="toggleCampaign(campaign)"
                :class="[
                  'px-3 py-2 rounded-md text-sm font-medium',
                  campaign.status === 'active' 
                    ? 'border border-yellow-300 text-yellow-700 hover:bg-yellow-50'
                    : 'border border-green-300 text-green-700 hover:bg-green-50'
                ]"
              >
                {{ campaign.status === 'active' ? 'Pause' : 'Resume' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const showCreateCampaign = ref(false)

const campaignStats = ref({
  active: 8,
  scheduled: 3,
  totalReach: 125670,
  reachGrowth: 15.2,
  ctr: 3.8,
  totalClicks: 4785,
  conversionRate: 2.4,
  conversions: 115
})

const campaigns = ref([
  {
    id: 1,
    name: 'Summer Festival Promo',
    channel: 'Facebook Ads',
    status: 'active',
    budget: 5000,
    spent: 3200,
    reach: 45000,
    conversions: 67,
    start_date: '2025-01-01',
    end_date: '2025-01-31'
  },
  {
    id: 2,
    name: 'Early Bird Special',
    channel: 'Email Marketing',
    status: 'active',
    budget: 2000,
    spent: 1200,
    reach: 12500,
    conversions: 28,
    start_date: '2025-01-05',
    end_date: '2025-01-25'
  },
  {
    id: 3,
    name: 'VIP Experience Campaign',
    channel: 'Instagram Ads',
    status: 'scheduled',
    budget: 3000,
    spent: 0,
    reach: 0,
    conversions: 0,
    start_date: '2025-02-01',
    end_date: '2025-02-28'
  },
  {
    id: 4,
    name: 'Last Minute Deals',
    channel: 'Google Ads',
    status: 'paused',
    budget: 1500,
    spent: 800,
    reach: 8900,
    conversions: 12,
    start_date: '2024-12-15',
    end_date: '2025-01-15'
  }
])

const editCampaign = (campaign: any) => {
  console.log('Editing campaign:', campaign.id)
  alert(`Editing campaign: ${campaign.name}\nThis feature will be implemented with a full campaign editor.`)
  // TODO: Open campaign edit modal with full functionality
}

const viewCampaignReport = (campaign: any) => {
  console.log('Viewing report for campaign:', campaign.id)
  // TODO: Open detailed campaign report
}

const toggleCampaign = (campaign: any) => {
  const newStatus = campaign.status === 'active' ? 'paused' : 'active'
  if (confirm(`Are you sure you want to ${newStatus === 'paused' ? 'pause' : 'resume'} this campaign?`)) {
    campaign.status = newStatus
    console.log(`Campaign ${campaign.id} ${newStatus}`)
    // TODO: Update via API
  }
}

onMounted(() => {
  // TODO: Fetch campaign data from API
  console.log('Loading marketing campaigns...')
})
</script>
