<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Sales & Finance</h1>
      <p class="mt-2 text-sm text-gray-600">Track revenue, payments, and financial performance across all events.</p>
    </div>

    <!-- Financial Summary Cards -->
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
                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                <dd class="text-lg font-medium text-gray-900">${{ financials.totalRevenue.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">+12.3% from last month</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Net Revenue</dt>
                <dd class="text-lg font-medium text-gray-900">${{ financials.netRevenue.toLocaleString() }}</dd>
                <dd class="text-xs text-gray-500">After fees & commissions</dd>
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
                <dd class="text-lg font-medium text-gray-900">${{ financials.pendingPayouts.toLocaleString() }}</dd>
                <dd class="text-xs text-yellow-600">{{ financials.pendingCount }} transactions</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Commission Earned</dt>
                <dd class="text-lg font-medium text-gray-900">${{ financials.commissionEarned.toLocaleString() }}</dd>
                <dd class="text-xs text-purple-600">{{ financials.commissionRate }}% avg rate</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-medium text-gray-900">Revenue Trends</h2>
          <div class="flex space-x-2">
            <button
              v-for="period in ['7D', '30D', '90D', '1Y']"
              :key="period"
              @click="selectedPeriod = period"
              :class="[
                selectedPeriod === period
                  ? 'bg-indigo-100 text-indigo-700'
                  : 'text-gray-500 hover:text-gray-700',
                'px-3 py-1 text-sm font-medium rounded-md'
              ]"
            >
              {{ period }}
            </button>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="h-64 flex items-center justify-center text-gray-500">
          Revenue chart placeholder - integrate with Chart.js
        </div>
      </div>
    </div>

    <!-- Payment Methods & Transaction Details -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
      <!-- Payment Methods -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Payment Methods</h2>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="method in paymentMethods" :key="method.type" class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div :class="[
                    'h-3 w-3 rounded-full',
                    method.type === 'card' ? 'bg-blue-500' :
                    method.type === 'transfer' ? 'bg-green-500' :
                    'bg-yellow-500'
                  ]"></div>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-900">{{ method.name }}</p>
                  <p class="text-xs text-gray-500">{{ method.transactions }} transactions</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">${{ method.amount.toLocaleString() }}</p>
                <p class="text-xs text-gray-500">{{ method.percentage }}%</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Recent Transactions</h2>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            <div v-for="transaction in recentTransactions" :key="transaction.id" class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-900">{{ transaction.event }}</p>
                <p class="text-xs text-gray-500">{{ transaction.customer }} • {{ formatDate(transaction.date) }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">${{ transaction.amount }}</p>
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  transaction.status === 'completed' ? 'bg-green-100 text-green-800' :
                  transaction.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]">
                  {{ transaction.status }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sales Reports Table -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Sales Reports</h2>
            <p class="mt-1 text-sm text-gray-500">Detailed breakdown by event, date, and ticket type.</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <router-link
              to="/admin/sales/reports"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              View All Reports
            </router-link>
          </div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets Sold</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Revenue</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Revenue</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="report in salesReports" :key="report.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ report.event_name }}</div>
                <div class="text-sm text-gray-500">{{ report.organizer }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(report.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ report.tickets_sold }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ report.gross_revenue.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ report.net_revenue.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ report.commission.toLocaleString() }}
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

const selectedPeriod = ref('30D')

const financials = ref({
  totalRevenue: 567890,
  netRevenue: 485230,
  pendingPayouts: 45670,
  pendingCount: 23,
  commissionEarned: 82660,
  commissionRate: 8.5
})

const paymentMethods = ref([
  { type: 'card', name: 'Credit/Debit Cards', amount: 425000, percentage: 75, transactions: 2156 },
  { type: 'transfer', name: 'Bank Transfer', amount: 98500, percentage: 17.4, transactions: 234 },
  { type: 'cash', name: 'Cash/Other', amount: 44390, percentage: 7.6, transactions: 89 }
])

const recentTransactions = ref([
  {
    id: 1,
    event: 'Summer Music Festival',
    customer: 'John Doe',
    amount: 150,
    status: 'completed',
    date: '2025-01-10'
  },
  {
    id: 2,
    event: 'Tech Conference',
    customer: 'Jane Smith',
    amount: 75,
    status: 'pending',
    date: '2025-01-10'
  },
  {
    id: 3,
    event: 'Art Exhibition',
    customer: 'Bob Johnson',
    amount: 30,
    status: 'completed',
    date: '2025-01-09'
  }
])

const salesReports = ref([
  {
    id: 1,
    event_name: 'Summer Music Festival',
    organizer: 'Music Events Co.',
    date: '2025-07-15',
    tickets_sold: 450,
    gross_revenue: 22500,
    net_revenue: 20250,
    commission: 2250
  },
  {
    id: 2,
    event_name: 'Tech Conference 2025',
    organizer: 'Tech Corp',
    date: '2025-03-20',
    tickets_sold: 200,
    gross_revenue: 15000,
    net_revenue: 13500,
    commission: 1500
  }
])

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch financial data from API
  console.log('Loading sales and finance data...')
})
</script>
