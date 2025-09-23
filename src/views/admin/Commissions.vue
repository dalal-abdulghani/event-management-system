<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Commission Management</h1>
      <p class="mt-2 text-sm text-gray-600">Manage partner commissions and payouts.</p>
    </div>

    <!-- Commission Summary -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Commissions</dt>
                <dd class="text-lg font-medium text-gray-900">${{ commissionData.total.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">This month</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Pending Payouts</dt>
                <dd class="text-lg font-medium text-gray-900">${{ commissionData.pending.toLocaleString() }}</dd>
                <dd class="text-xs text-yellow-600">{{ commissionData.pendingCount }} partners</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

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
                <dt class="text-sm font-medium text-gray-500 truncate">Active Partners</dt>
                <dd class="text-lg font-medium text-gray-900">{{ commissionData.activePartners }}</dd>
                <dd class="text-xs text-blue-600">{{ commissionData.avgCommissionRate }}% avg rate</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Paid This Month</dt>
                <dd class="text-lg font-medium text-gray-900">${{ commissionData.paidThisMonth.toLocaleString() }}</dd>
                <dd class="text-xs text-purple-600">{{ commissionData.payoutCount }} payouts</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Partners & Commission Rates -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Partners & Commission Rates</h2>
            <p class="mt-1 text-sm text-gray-500">Manage partner relationships and commission structures.</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="showAddPartner = true"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              Add Partner
            </button>
          </div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partner</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission Rate</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Volume</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission Earned</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Payout</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="partner in partners" :key="partner.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ partner.name }}</div>
                <div class="text-sm text-gray-500">{{ partner.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ partner.commission_rate }}%
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ partner.sales_volume.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ partner.commission_earned.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(partner.last_payout) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  partner.status === 'active' ? 'bg-green-100 text-green-800' :
                  partner.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]">
                  {{ partner.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="editPartner(partner)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button 
                    @click="payoutPartner(partner)"
                    class="text-green-600 hover:text-green-900"
                  >
                    Payout
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const showAddPartner = ref(false)

const commissionData = ref({
  total: 45670,
  pending: 12450,
  pendingCount: 8,
  activePartners: 15,
  avgCommissionRate: 8.5,
  paidThisMonth: 33220,
  payoutCount: 12
})

const partners = ref([
  {
    id: 1,
    name: 'EventBrite Partner',
    email: 'partner@eventbrite.com',
    commission_rate: 10,
    sales_volume: 125000,
    commission_earned: 12500,
    last_payout: '2025-01-01',
    status: 'active'
  },
  {
    id: 2,
    name: 'Local Promoter',
    email: 'promoter@local.com',
    commission_rate: 15,
    sales_volume: 45000,
    commission_earned: 6750,
    last_payout: '2024-12-15',
    status: 'active'
  },
  {
    id: 3,
    name: 'Corporate Sales',
    email: 'corporate@sales.com',
    commission_rate: 5,
    sales_volume: 89000,
    commission_earned: 4450,
    last_payout: null,
    status: 'pending'
  }
])

const editPartner = (partner: any) => {
  console.log('Editing partner:', partner.id)
  alert(`Editing partner: ${partner.name}\nThis will open the partner settings editor.`)
  // TODO: Open edit partner modal
}

const payoutPartner = (partner: any) => {
  if (confirm(`Process payout of $${partner.commission_earned.toLocaleString()} to ${partner.name}?`)) {
    console.log('Processing payout for partner:', partner.id)
    // TODO: Process payout via API
    alert('Payout processed successfully!')
  }
}

const formatDate = (date: string) => {
  if (!date) return 'Never'
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch commission data from API
  console.log('Loading commission data...')
})
</script>
