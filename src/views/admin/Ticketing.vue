<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Ticketing Management</h1>
      <p class="mt-2 text-sm text-gray-600">Manage tickets, pricing, and sales across all events.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Tickets</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.totalTickets.toLocaleString() }}</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Sold</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.soldTickets.toLocaleString() }}</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.pendingTickets.toLocaleString() }}</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Revenue</dt>
                <dd class="text-lg font-medium text-gray-900">${{ stats.revenue.toLocaleString() }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ticket Management Tabs -->
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
        <!-- Active Tickets Tab -->
        <div v-if="activeTab === 'active'">
          <div class="sm:flex sm:items-center mb-4">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">Active Tickets</h3>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <router-link
                to="/admin/ticketing/templates"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Manage Templates
              </router-link>
            </div>
          </div>

          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold/Total</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                  <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="ticket in activeTickets" :key="ticket.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ ticket.event_name }}</div>
                    <div class="text-sm text-gray-500">{{ formatDate(ticket.event_date) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ ticket.type_name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ ticket.price }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ ticket.sold }} / {{ ticket.total }}</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                      <div 
                        class="bg-indigo-600 h-2 rounded-full" 
                        :style="{ width: `${(ticket.sold / ticket.total) * 100}%` }"
                      ></div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ (ticket.price * ticket.sold).toLocaleString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                      <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                      <button class="text-red-600 hover:text-red-900">Disable</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pricing Tab -->
        <div v-if="activeTab === 'pricing'">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Pricing Management</h3>
            <p class="text-sm text-gray-500">Set up pricing tiers, discounts, and special offers.</p>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-900 mb-3">Early Bird Discounts</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Active Discounts</span>
                  <span class="text-sm font-medium">12</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Average Discount</span>
                  <span class="text-sm font-medium">15%</span>
                </div>
              </div>
              <button class="mt-3 w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Manage Discounts
              </button>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-900 mb-3">Bundle Offers</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Active Bundles</span>
                  <span class="text-sm font-medium">5</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Bundle Sales</span>
                  <span class="text-sm font-medium">23%</span>
                </div>
              </div>
              <button class="mt-3 w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Create Bundle
              </button>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-900 mb-3">Dynamic Pricing</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Enabled Events</span>
                  <span class="text-sm font-medium">8</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Avg Price Increase</span>
                  <span class="text-sm font-medium">+12%</span>
                </div>
              </div>
              <button class="mt-3 w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Configure Rules
              </button>
            </div>
          </div>
        </div>

        <!-- Sales Tracking Tab -->
        <div v-if="activeTab === 'tracking'">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Sales Tracking</h3>
            <p class="text-sm text-gray-500">Monitor ticket sales performance and trends.</p>
          </div>

          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Sales by Event Type</h4>
              <div class="h-64 flex items-center justify-center text-gray-500">
                Chart placeholder - Sales by event type
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Daily Sales Trend</h4>
              <div class="h-64 flex items-center justify-center text-gray-500">
                Chart placeholder - Daily sales trend
              </div>
            </div>
          </div>
        </div>

        <!-- Check-in System Tab -->
        <div v-if="activeTab === 'checkin'">
          <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Check-in System</h3>
            <p class="text-sm text-gray-500">Manage entry validation and attendance tracking.</p>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M12 8h4.01"></path>
              </svg>
              <h4 class="mt-2 text-md font-medium text-gray-900">QR Code Scanner</h4>
              <p class="mt-1 text-sm text-gray-500">Quick entry validation</p>
              <router-link
                to="/admin/ticketing/check-in"
                class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
              >
                Open Scanner
              </router-link>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-900 mb-3">Today's Check-ins</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Total Checked In</span>
                  <span class="text-sm font-medium">1,247</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Pending Entry</span>
                  <span class="text-sm font-medium">89</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">No-shows</span>
                  <span class="text-sm font-medium">34</span>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-md font-medium text-gray-900 mb-3">Entry Statistics</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Attendance Rate</span>
                  <span class="text-sm font-medium">94.2%</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Avg Check-in Time</span>
                  <span class="text-sm font-medium">2.3 min</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600">Peak Hour</span>
                  <span class="text-sm font-medium">7:00 PM</span>
                </div>
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

const activeTab = ref('active')
const stats = ref({
  totalTickets: 5420,
  soldTickets: 4187,
  pendingTickets: 234,
  revenue: 125750
})

const tabs = [
  { name: 'active', label: 'Active Tickets' },
  { name: 'pricing', label: 'Pricing' },
  { name: 'tracking', label: 'Sales Tracking' },
  { name: 'checkin', label: 'Check-in System' }
]

const activeTickets = ref<any[]>([])

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch ticket data from API
  activeTickets.value = [
    {
      id: 1,
      event_name: 'Summer Music Festival',
      event_date: '2025-07-15',
      type_name: 'General Admission',
      price: 50,
      sold: 450,
      total: 500
    },
    {
      id: 2,
      event_name: 'Summer Music Festival',
      event_date: '2025-07-15',
      type_name: 'VIP',
      price: 150,
      sold: 80,
      total: 100
    },
    {
      id: 3,
      event_name: 'Tech Conference 2025',
      event_date: '2025-03-20',
      type_name: 'Standard',
      price: 75,
      sold: 120,
      total: 200
    }
  ]
})
</script>
