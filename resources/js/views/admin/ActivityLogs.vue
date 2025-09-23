<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
      <p class="mt-2 text-sm text-gray-600">Monitor system activities and user actions.</p>
    </div>

    <!-- Activity Statistics -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Activities</dt>
                <dd class="text-lg font-medium text-gray-900">{{ activityStats.total.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">{{ activityStats.today }} today</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Successful Actions</dt>
                <dd class="text-lg font-medium text-gray-900">{{ activityStats.successful.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">{{ activityStats.successRate }}% success rate</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Failed Actions</dt>
                <dd class="text-lg font-medium text-gray-900">{{ activityStats.failed }}</dd>
                <dd class="text-xs text-red-600">{{ activityStats.failedToday }} today</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Unique Users</dt>
                <dd class="text-lg font-medium text-gray-900">{{ activityStats.uniqueUsers }}</dd>
                <dd class="text-xs text-purple-600">Active users</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity Filters -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Filter Activities</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
          <div>
            <label for="user-filter" class="block text-sm font-medium text-gray-700">User</label>
            <select
              v-model="filters.userId"
              id="user-filter"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Users</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
          <div>
            <label for="action-filter" class="block text-sm font-medium text-gray-700">Action</label>
            <select
              v-model="filters.action"
              id="action-filter"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Actions</option>
              <option value="login">Login</option>
              <option value="logout">Logout</option>
              <option value="create">Create</option>
              <option value="update">Update</option>
              <option value="delete">Delete</option>
              <option value="view">View</option>
            </select>
          </div>
          <div>
            <label for="resource-filter" class="block text-sm font-medium text-gray-700">Resource</label>
            <select
              v-model="filters.resource"
              id="resource-filter"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="">All Resources</option>
              <option value="events">Events</option>
              <option value="users">Users</option>
              <option value="tickets">Tickets</option>
              <option value="venues">Venues</option>
              <option value="settings">Settings</option>
            </select>
          </div>
          <div>
            <label for="time-filter" class="block text-sm font-medium text-gray-700">Time Range</label>
            <select
              v-model="filters.timeRange"
              id="time-filter"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >
              <option value="1h">Last hour</option>
              <option value="24h">Last 24 hours</option>
              <option value="7d">Last 7 days</option>
              <option value="30d">Last 30 days</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="exportLogs"
              class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Export Logs
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity Log Table -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Activity Log</h2>
        <p class="mt-1 text-sm text-gray-500">Detailed log of all system activities.</p>
      </div>
      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="activity in filteredActivities" :key="activity.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDateTime(activity.timestamp) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ activity.user_name }}</div>
                <div class="text-sm text-gray-500">{{ activity.user_role }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  activity.action === 'create' ? 'bg-green-100 text-green-800' :
                  activity.action === 'update' ? 'bg-blue-100 text-blue-800' :
                  activity.action === 'delete' ? 'bg-red-100 text-red-800' :
                  activity.action === 'login' ? 'bg-indigo-100 text-indigo-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ activity.action }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ activity.resource_type }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ activity.details }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ activity.ip_address }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  activity.status === 'success' ? 'bg-green-100 text-green-800' :
                  activity.status === 'failed' ? 'bg-red-100 text-red-800' :
                  'bg-yellow-100 text-yellow-800'
                ]">
                  {{ activity.status }}
                </span>
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
  userId: '',
  action: '',
  resource: '',
  timeRange: '24h'
})

const activityStats = ref({
  total: 15678,
  today: 234,
  successful: 14890,
  successRate: 95.0,
  failed: 788,
  failedToday: 12,
  uniqueUsers: 145
})

const users = ref([
  { id: 1, name: 'John Admin' },
  { id: 2, name: 'Jane Manager' },
  { id: 3, name: 'Bob Staff' },
  { id: 4, name: 'Alice Organizer' }
])

const activities = ref([
  {
    id: 1,
    timestamp: new Date().toISOString(),
    user_name: 'John Admin',
    user_role: 'Super Admin',
    action: 'create',
    resource_type: 'Event',
    details: 'Created "Summer Music Festival"',
    ip_address: '192.168.1.100',
    status: 'success'
  },
  {
    id: 2,
    timestamp: new Date(Date.now() - 300000).toISOString(),
    user_name: 'Jane Manager',
    user_role: 'Admin',
    action: 'update',
    resource_type: 'User',
    details: 'Updated user permissions for Bob Staff',
    ip_address: '192.168.1.101',
    status: 'success'
  },
  {
    id: 3,
    timestamp: new Date(Date.now() - 600000).toISOString(),
    user_name: 'Bob Staff',
    user_role: 'Staff',
    action: 'login',
    resource_type: 'System',
    details: 'Logged into check-in system',
    ip_address: '192.168.1.102',
    status: 'success'
  },
  {
    id: 4,
    timestamp: new Date(Date.now() - 900000).toISOString(),
    user_name: 'Unknown User',
    user_role: 'Guest',
    action: 'login',
    resource_type: 'System',
    details: 'Failed login attempt',
    ip_address: '203.0.113.1',
    status: 'failed'
  },
  {
    id: 5,
    timestamp: new Date(Date.now() - 1200000).toISOString(),
    user_name: 'Alice Organizer',
    user_role: 'Organizer',
    action: 'delete',
    resource_type: 'Event',
    details: 'Cancelled "Outdoor Concert"',
    ip_address: '192.168.1.103',
    status: 'success'
  }
])

const filteredActivities = computed(() => {
  let filtered = activities.value

  if (filters.value.userId) {
    filtered = filtered.filter(activity => 
      activity.user_name.includes(users.value.find(u => u.id.toString() === filters.value.userId)?.name || '')
    )
  }

  if (filters.value.action) {
    filtered = filtered.filter(activity => activity.action === filters.value.action)
  }

  if (filters.value.resource) {
    filtered = filtered.filter(activity => 
      activity.resource_type.toLowerCase().includes(filters.value.resource)
    )
  }

  return filtered.sort((a, b) => new Date(b.timestamp).getTime() - new Date(a.timestamp).getTime())
})

const exportLogs = () => {
  console.log('Exporting activity logs with filters:', filters.value)
  // TODO: Generate and download activity log report
  alert('Activity logs exported successfully!')
}

const formatDateTime = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleString()
}

onMounted(() => {
  // TODO: Fetch activity logs from API
  console.log('Loading activity logs...')
})
</script>
