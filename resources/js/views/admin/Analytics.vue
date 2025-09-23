<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
      <p class="mt-2 text-sm text-gray-600">Comprehensive analytics and performance metrics for your events platform.</p>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
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
                <dt class="text-sm font-medium text-gray-500 truncate">Tickets Sold vs Remaining</dt>
                <dd class="text-lg font-medium text-gray-900">{{ kpis.ticketsSold.toLocaleString() }} / {{ kpis.totalTickets.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">{{ ((kpis.ticketsSold / kpis.totalTickets) * 100).toFixed(1) }}% sold</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Actual Attendance</dt>
                <dd class="text-lg font-medium text-gray-900">{{ kpis.actualAttendance.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">{{ ((kpis.actualAttendance / kpis.ticketsSold) * 100).toFixed(1) }}% attendance rate</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Average Ticket Price</dt>
                <dd class="text-lg font-medium text-gray-900">${{ kpis.avgTicketPrice }}</dd>
                <dd class="text-xs text-yellow-600">+$3.20 from last month</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Revenue Growth</dt>
                <dd class="text-lg font-medium text-gray-900">+{{ kpis.revenueGrowth }}%</dd>
                <dd class="text-xs text-purple-600">vs previous period</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
      <!-- Sales Trend Chart -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">Sales Trends</h2>
            <div class="flex space-x-2">
              <button
                v-for="period in ['7D', '30D', '90D', '1Y']"
                :key="period"
                @click="salesPeriod = period"
                :class="[
                  salesPeriod === period
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
            Sales trend chart placeholder - integrate with Chart.js
          </div>
        </div>
      </div>

      <!-- Event Performance Chart -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Event Performance</h2>
        </div>
        <div class="p-6">
          <div class="h-64 flex items-center justify-center text-gray-500">
            Event performance chart placeholder - integrate with Chart.js
          </div>
        </div>
      </div>

      <!-- Top Events by Revenue -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Top Performing Events</h2>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="event in topEvents" :key="event.id" class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                  <span class="text-sm font-medium text-indigo-600">{{ event.rank }}</span>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-900">{{ event.name }}</p>
                  <p class="text-xs text-gray-500">{{ event.tickets_sold }} tickets sold</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">${{ event.revenue.toLocaleString() }}</p>
                <p class="text-xs text-gray-500">{{ event.growth }}% growth</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Sources -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Sales Sources</h2>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="source in salesSources" :key="source.name" class="flex items-center justify-between">
              <div class="flex items-center">
                <div :class="[
                  'w-3 h-3 rounded-full',
                  source.name === 'Direct' ? 'bg-blue-500' :
                  source.name === 'Website' ? 'bg-green-500' :
                  source.name === 'Partners' ? 'bg-yellow-500' :
                  'bg-purple-500'
                ]"></div>
                <span class="ml-3 text-sm font-medium text-gray-900">{{ source.name }}</span>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ source.percentage }}%</p>
                <p class="text-xs text-gray-500">{{ source.sales.toLocaleString() }} sales</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Event Category Performance -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Event Category Performance</h2>
        <p class="mt-1 text-sm text-gray-500">Performance breakdown by event category.</p>
      </div>
      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets Sold</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="category in categoryPerformance" :key="category.name">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div :class="[
                    'w-3 h-3 rounded-full mr-3',
                    category.color
                  ]"></div>
                  <span class="text-sm font-medium text-gray-900">{{ category.name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ category.events }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ category.tickets_sold.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ category.revenue.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ category.avg_price }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                    <div 
                      class="bg-indigo-600 h-2 rounded-full" 
                      :style="{ width: `${category.attendance_rate}%` }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-900">{{ category.attendance_rate }}%</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Geographic Performance -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Geographic Performance</h2>
        <p class="mt-1 text-sm text-gray-500">Sales distribution by location.</p>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Top Cities</h3>
            <div class="space-y-3">
              <div v-for="city in topCities" :key="city.name" class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ city.name }}</p>
                  <p class="text-xs text-gray-500">{{ city.events }} events</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-900">${{ city.revenue.toLocaleString() }}</p>
                  <p class="text-xs text-gray-500">{{ city.tickets }} tickets</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Regional Distribution</h3>
            <div class="h-64 flex items-center justify-center text-gray-500">
              Geographic map placeholder - integrate with mapping library
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const salesPeriod = ref('30D')

const kpis = ref({
  ticketsSold: 4187,
  totalTickets: 5420,
  actualAttendance: 3945,
  avgTicketPrice: 67.50,
  revenueGrowth: 12.3
})

const topEvents = ref([
  {
    id: 1,
    rank: 1,
    name: 'Summer Music Festival',
    tickets_sold: 450,
    revenue: 22500,
    growth: 15.2
  },
  {
    id: 2,
    rank: 2,
    name: 'Tech Conference 2025',
    tickets_sold: 200,
    revenue: 15000,
    growth: 8.7
  },
  {
    id: 3,
    rank: 3,
    name: 'Art Exhibition',
    tickets_sold: 180,
    revenue: 5400,
    growth: -2.1
  },
  {
    id: 4,
    rank: 4,
    name: 'Business Workshop',
    tickets_sold: 120,
    revenue: 4800,
    growth: 22.5
  }
])

const salesSources = ref([
  { name: 'Direct', percentage: 45, sales: 1883 },
  { name: 'Website', percentage: 32, sales: 1340 },
  { name: 'Partners', percentage: 15, sales: 628 },
  { name: 'Social Media', percentage: 8, sales: 335 }
])

const categoryPerformance = ref([
  {
    name: 'Music',
    color: 'bg-blue-500',
    events: 12,
    tickets_sold: 1250,
    revenue: 62500,
    avg_price: 50,
    attendance_rate: 94
  },
  {
    name: 'Conference',
    color: 'bg-green-500',
    events: 8,
    tickets_sold: 890,
    revenue: 66750,
    avg_price: 75,
    attendance_rate: 89
  },
  {
    name: 'Workshop',
    color: 'bg-yellow-500',
    events: 15,
    tickets_sold: 675,
    revenue: 20250,
    avg_price: 30,
    attendance_rate: 96
  },
  {
    name: 'Sports',
    color: 'bg-red-500',
    events: 6,
    tickets_sold: 980,
    revenue: 39200,
    avg_price: 40,
    attendance_rate: 87
  },
  {
    name: 'Arts & Culture',
    color: 'bg-purple-500',
    events: 10,
    tickets_sold: 392,
    revenue: 11760,
    avg_price: 30,
    attendance_rate: 92
  }
])

const topCities = ref([
  { name: 'New York', events: 18, revenue: 125000, tickets: 2500 },
  { name: 'Los Angeles', events: 15, revenue: 98000, tickets: 1960 },
  { name: 'Chicago', events: 12, revenue: 76000, tickets: 1520 },
  { name: 'San Francisco', events: 10, revenue: 85000, tickets: 1133 },
  { name: 'Miami', events: 8, revenue: 45000, tickets: 900 }
])

onMounted(() => {
  // TODO: Fetch analytics data from API
  console.log('Loading analytics data...')
})
</script>
