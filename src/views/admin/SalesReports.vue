<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Sales Reports</h1>
      <p class="mt-2 text-sm text-gray-600">Detailed sales analytics and performance reports.</p>
    </div>

    <!-- Report Filters -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Report Filters</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
          <div>
            <label for="date-range" class="block text-sm font-medium text-gray-700">Date Range</label>
            <select
              v-model="filters.dateRange"
              id="date-range"
              @change="applyFilters"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="7d">Last 7 days</option>
              <option value="30d">Last 30 days</option>
              <option value="90d">Last 90 days</option>
              <option value="1y">Last year</option>
              <option value="custom">Custom range</option>
            </select>
          </div>
          <div>
            <label for="event-filter" class="block text-sm font-medium text-gray-700">Event</label>
            <select
              v-model="filters.eventId"
              id="event-filter"
              @change="applyFilters"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Events</option>
              <option v-for="event in events" :key="event.id" :value="event.id">
                {{ event.name }}
              </option>
            </select>
          </div>
          <div>
            <label for="ticket-type" class="block text-sm font-medium text-gray-700">Ticket Type</label>
            <select
              v-model="filters.ticketType"
              id="ticket-type"
              @change="applyFilters"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Types</option>
              <option value="general">General Admission</option>
              <option value="vip">VIP</option>
              <option value="early-bird">Early Bird</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="exportReport"
              class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Export Report
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sales Summary -->
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
                <dd class="text-lg font-medium text-gray-900">${{ reportData.totalRevenue.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">+{{ reportData.revenueGrowth }}% vs previous period</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Tickets Sold</dt>
                <dd class="text-lg font-medium text-gray-900">{{ reportData.ticketsSold.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">{{ reportData.salesGrowth }}% growth</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Avg Ticket Price</dt>
                <dd class="text-lg font-medium text-gray-900">${{ reportData.avgTicketPrice }}</dd>
                <dd class="text-xs text-yellow-600">{{ reportData.priceChange > 0 ? '+' : '' }}{{ reportData.priceChange }}% change</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ reportData.conversionRate }}%</dd>
                <dd class="text-xs text-purple-600">{{ reportData.conversionChange > 0 ? '+' : '' }}{{ reportData.conversionChange }}% change</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Revenue Trend</h2>
        </div>
        <div class="p-6">
          <div class="h-64 flex items-center justify-center text-gray-500">
            Revenue trend chart placeholder - integrate with Chart.js
          </div>
        </div>
      </div>

      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Sales by Event Type</h2>
        </div>
        <div class="p-6">
          <div class="h-64 flex items-center justify-center text-gray-500">
            Event type pie chart placeholder - integrate with Chart.js
          </div>
        </div>
      </div>
    </div>

    <!-- Detailed Sales Table -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Detailed Sales Report</h2>
      </div>
      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="sale in filteredSales" :key="sale.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(sale.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.event_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.ticket_type }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ sale.quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ sale.unit_price }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ sale.total.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ sale.commission.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${{ sale.net.toLocaleString() }}
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

const filters = ref({
  dateRange: '30d',
  eventId: '',
  ticketType: ''
})

const reportData = ref({
  totalRevenue: 125750,
  revenueGrowth: 12.3,
  ticketsSold: 2456,
  salesGrowth: 8.7,
  avgTicketPrice: 51.25,
  priceChange: 3.2,
  conversionRate: 4.8,
  conversionChange: 0.5
})

const events = ref([
  { id: 1, name: 'Summer Music Festival' },
  { id: 2, name: 'Tech Conference 2025' },
  { id: 3, name: 'Art Exhibition' }
])

const sales = ref([
  {
    id: 1,
    date: '2025-01-10',
    event_name: 'Summer Music Festival',
    ticket_type: 'General Admission',
    quantity: 2,
    unit_price: 50,
    total: 100,
    commission: 10,
    net: 90
  },
  {
    id: 2,
    date: '2025-01-10',
    event_name: 'Summer Music Festival',
    ticket_type: 'VIP',
    quantity: 1,
    unit_price: 150,
    total: 150,
    commission: 15,
    net: 135
  },
  {
    id: 3,
    date: '2025-01-09',
    event_name: 'Tech Conference 2025',
    ticket_type: 'Standard',
    quantity: 3,
    unit_price: 75,
    total: 225,
    commission: 22.5,
    net: 202.5
  }
])

const filteredSales = computed(() => {
  let filtered = sales.value

  if (filters.value.eventId) {
    filtered = filtered.filter(sale => sale.event_name === events.value.find(e => e.id.toString() === filters.value.eventId)?.name)
  }

  if (filters.value.ticketType) {
    filtered = filtered.filter(sale => sale.ticket_type.toLowerCase().includes(filters.value.ticketType))
  }

  return filtered
})

const applyFilters = () => {
  console.log('Applying filters:', filters.value)
  // TODO: Fetch filtered data from API
}

const exportReport = () => {
  console.log('Exporting sales report with filters:', filters.value)
  // TODO: Generate and download report
  alert('Report exported successfully!')
}

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch sales data from API
  console.log('Loading sales report data...')
})
</script>
