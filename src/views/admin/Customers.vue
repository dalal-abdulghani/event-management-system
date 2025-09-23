<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Customer Management</h1>
      <p class="mt-2 text-sm text-gray-600">Manage customer database, segments, and purchase history.</p>
    </div>

    <!-- Customer Statistics -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                <dd class="text-lg font-medium text-gray-900">{{ customerStats.total.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">+{{ customerStats.newThisMonth }} this month</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">VIP Customers</dt>
                <dd class="text-lg font-medium text-gray-900">{{ customerStats.vip }}</dd>
                <dd class="text-xs text-green-600">{{ ((customerStats.vip / customerStats.total) * 100).toFixed(1) }}% of total</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Avg Lifetime Value</dt>
                <dd class="text-lg font-medium text-gray-900">${{ customerStats.avgLifetimeValue }}</dd>
                <dd class="text-xs text-yellow-600">+$12 from last quarter</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Repeat Customers</dt>
                <dd class="text-lg font-medium text-gray-900">{{ customerStats.repeat }}%</dd>
                <dd class="text-xs text-purple-600">{{ customerStats.repeatCount }} customers</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Segmentation and Search -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Customer Database</h2>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="showExportModal = true"
              class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 mr-3"
            >
              Export Data
            </button>
            <button
              @click="showImportModal = true"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              Import Customers
            </button>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
            <input
              v-model="searchQuery"
              type="text"
              id="search"
              placeholder="Name, email, or phone..."
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            />
          </div>
          <div>
            <label for="segment" class="block text-sm font-medium text-gray-700">Segment</label>
            <select
              v-model="selectedSegment"
              id="segment"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Customers</option>
              <option value="new">New Customers</option>
              <option value="loyal">Loyal Customers</option>
              <option value="vip">VIP Customers</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div>
            <label for="date-range" class="block text-sm font-medium text-gray-700">Registration Date</label>
            <select
              v-model="dateRange"
              id="date-range"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Time</option>
              <option value="7d">Last 7 days</option>
              <option value="30d">Last 30 days</option>
              <option value="90d">Last 90 days</option>
              <option value="1y">Last year</option>
            </select>
          </div>
          <div>
            <label for="spent-range" class="block text-sm font-medium text-gray-700">Total Spent</label>
            <select
              v-model="spentRange"
              id="spent-range"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">Any Amount</option>
              <option value="0-50">$0 - $50</option>
              <option value="51-200">$51 - $200</option>
              <option value="201-500">$201 - $500</option>
              <option value="500+">$500+</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Customer Table -->
      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Segment</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events Attended</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Purchase</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="customer in filteredCustomers" :key="customer.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-700">
                        {{ customer.first_name?.[0] }}{{ customer.last_name?.[0] }}
                      </span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ customer.first_name }} {{ customer.last_name }}
                    </div>
                    <div class="text-sm text-gray-500">{{ customer.email }}</div>
                    <div class="text-xs text-gray-400">{{ customer.phone }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  customer.segment === 'vip' ? 'bg-purple-100 text-purple-800' :
                  customer.segment === 'loyal' ? 'bg-green-100 text-green-800' :
                  customer.segment === 'new' ? 'bg-blue-100 text-blue-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ customer.segment }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ customer.total_spent.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ customer.events_attended }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(customer.last_purchase) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  customer.status === 'active' ? 'bg-green-100 text-green-800' :
                  customer.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]">
                  {{ customer.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                  <router-link 
                    :to="`/admin/customers/${customer.id}`"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    View
                  </router-link>
                  <button class="text-gray-600 hover:text-gray-900">Edit</button>
                  <button 
                    @click="deleteCustomer(customer)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Previous
          </button>
          <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of{' '}
              <span class="font-medium">{{ filteredCustomers.length }}</span> results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                Previous
              </button>
              <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                1
              </button>
              <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                Next
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Segments Overview -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Customer Segments</h2>
        <p class="mt-1 text-sm text-gray-500">Customer distribution and performance by segment.</p>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="segment in customerSegments" :key="segment.name" class="text-center">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
              <span :class="[
                'text-2xl font-bold',
                segment.name === 'VIP' ? 'text-purple-600' :
                segment.name === 'Loyal' ? 'text-green-600' :
                segment.name === 'New' ? 'text-blue-600' :
                'text-gray-600'
              ]">
                {{ segment.count }}
              </span>
            </div>
            <h3 class="text-sm font-medium text-gray-900">{{ segment.name }} Customers</h3>
            <p class="text-xs text-gray-500">{{ segment.percentage }}% of total</p>
            <p class="text-xs text-gray-400 mt-1">Avg spend: ${{ segment.avgSpend }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const searchQuery = ref('')
const selectedSegment = ref('')
const dateRange = ref('')
const spentRange = ref('')
const showExportModal = ref(false)
const showImportModal = ref(false)

const customerStats = ref({
  total: 2456,
  newThisMonth: 187,
  vip: 123,
  avgLifetimeValue: 245,
  repeat: 68.5,
  repeatCount: 1683
})

const customers = ref<any[]>([])

const customerSegments = ref([
  { name: 'VIP', count: 123, percentage: 5, avgSpend: 850 },
  { name: 'Loyal', count: 892, percentage: 36, avgSpend: 320 },
  { name: 'New', count: 456, percentage: 19, avgSpend: 85 },
  { name: 'Inactive', count: 985, percentage: 40, avgSpend: 45 }
])

const filteredCustomers = computed(() => {
  let filtered = customers.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(customer => 
      customer.first_name.toLowerCase().includes(query) ||
      customer.last_name.toLowerCase().includes(query) ||
      customer.email.toLowerCase().includes(query) ||
      customer.phone.includes(query)
    )
  }

  if (selectedSegment.value) {
    filtered = filtered.filter(customer => customer.segment === selectedSegment.value)
  }

  return filtered
})

const formatDate = (date: string) => {
  if (!date) return 'Never'
  return new Date(date).toLocaleDateString()
}

const deleteCustomer = (customer: any) => {
  if (confirm(`Are you sure you want to delete customer "${customer.first_name} ${customer.last_name}"?\n\nThis action cannot be undone.`)) {
    const customerIndex = customers.value.findIndex(c => c.id === customer.id)
    if (customerIndex !== -1) {
      const deletedCustomer = customers.value[customerIndex]
      customers.value.splice(customerIndex, 1)
      alert(`Customer "${deletedCustomer.first_name} ${deletedCustomer.last_name}" has been deleted successfully!`)
    }
    console.log('Deleted customer:', customer.id)
    // TODO: Delete via API
  }
}

onMounted(() => {
  // TODO: Fetch customers from API
  customers.value = [
    {
      id: 1,
      first_name: 'John',
      last_name: 'Doe',
      email: 'john.doe@example.com',
      phone: '+1-555-0123',
      segment: 'vip',
      total_spent: 1250,
      events_attended: 8,
      last_purchase: '2025-01-08',
      status: 'active'
    },
    {
      id: 2,
      first_name: 'Jane',
      last_name: 'Smith',
      email: 'jane.smith@example.com',
      phone: '+1-555-0124',
      segment: 'loyal',
      total_spent: 450,
      events_attended: 3,
      last_purchase: '2025-01-05',
      status: 'active'
    },
    {
      id: 3,
      first_name: 'Bob',
      last_name: 'Johnson',
      email: 'bob.johnson@example.com',
      phone: '+1-555-0125',
      segment: 'new',
      total_spent: 75,
      events_attended: 1,
      last_purchase: '2025-01-10',
      status: 'active'
    },
    {
      id: 4,
      first_name: 'Alice',
      last_name: 'Williams',
      email: 'alice.williams@example.com',
      phone: '+1-555-0126',
      segment: 'inactive',
      total_spent: 200,
      events_attended: 2,
      last_purchase: '2024-10-15',
      status: 'inactive'
    }
  ]
})
</script>
