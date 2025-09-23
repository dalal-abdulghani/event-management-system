<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Security & Permissions</h1>
      <p class="mt-2 text-sm text-gray-600">Manage user roles, permissions, and security settings.</p>
    </div>

    <!-- Security Overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Security Score</dt>
                <dd class="text-lg font-medium text-gray-900">{{ securityStats.score }}/100</dd>
                <dd class="text-xs text-green-600">Excellent</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                <dd class="text-lg font-medium text-gray-900">{{ securityStats.activeUsers }}</dd>
                <dd class="text-xs text-blue-600">{{ securityStats.onlineNow }} online now</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Security Alerts</dt>
                <dd class="text-lg font-medium text-gray-900">{{ securityStats.alerts }}</dd>
                <dd class="text-xs text-yellow-600">{{ securityStats.newAlerts }} new</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Failed Logins</dt>
                <dd class="text-lg font-medium text-gray-900">{{ securityStats.failedLogins }}</dd>
                <dd class="text-xs text-red-600">Last 24 hours</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Security Tabs -->
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
        <!-- Roles & Permissions Tab -->
        <div v-if="activeTab === 'roles'">
          <div class="sm:flex sm:items-center mb-6">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">User Roles & Permissions</h3>
              <p class="mt-1 text-sm text-gray-500">Manage access levels and permissions for different user types.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <button
                @click="showCreateRole = true"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Create Role
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="role in userRoles" :key="role.id" class="bg-gray-50 rounded-lg p-6">
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-medium text-gray-900">{{ role.name }}</h4>
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  role.level === 'high' ? 'bg-red-100 text-red-800' :
                  role.level === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-green-100 text-green-800'
                ]">
                  {{ role.level }} access
                </span>
              </div>
              
              <p class="text-sm text-gray-600 mb-4">{{ role.description }}</p>
              
              <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Users</span>
                  <span class="font-medium">{{ role.user_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Permissions</span>
                  <span class="font-medium">{{ role.permissions.length }}</span>
                </div>
              </div>

              <div class="flex space-x-2">
                <button 
                  @click="editRole(role)"
                  class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                  Edit
                </button>
                <button 
                  @click="deleteRole(role.id)"
                  class="flex-1 text-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-50"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Activity Monitoring Tab -->
        <div v-if="activeTab === 'activity'">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Activity Monitoring</h3>
            <p class="mt-1 text-sm text-gray-500">Track user activities and system access.</p>
          </div>

          <div class="mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div>
                <label for="activity-filter" class="block text-sm font-medium text-gray-700">Filter by Activity</label>
                <select
                  v-model="activityFilter"
                  id="activity-filter"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                  <option value="">All Activities</option>
                  <option value="login">Login</option>
                  <option value="logout">Logout</option>
                  <option value="create">Create</option>
                  <option value="update">Update</option>
                  <option value="delete">Delete</option>
                </select>
              </div>
              <div>
                <label for="user-filter" class="block text-sm font-medium text-gray-700">Filter by User</label>
                <select
                  v-model="userFilter"
                  id="user-filter"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                  <option value="">All Users</option>
                  <option v-for="user in systemUsers" :key="user.id" :value="user.id">
                    {{ user.name }}
                  </option>
                </select>
              </div>
              <div>
                <label for="time-filter" class="block text-sm font-medium text-gray-700">Time Range</label>
                <select
                  v-model="timeFilter"
                  id="time-filter"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                  <option value="24h">Last 24 hours</option>
                  <option value="7d">Last 7 days</option>
                  <option value="30d">Last 30 days</option>
                  <option value="90d">Last 90 days</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="activity in filteredActivities" :key="activity.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ activity.user_name }}</div>
                    <div class="text-sm text-gray-500">{{ activity.user_role }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      activity.action === 'login' ? 'bg-green-100 text-green-800' :
                      activity.action === 'logout' ? 'bg-gray-100 text-gray-800' :
                      activity.action === 'create' ? 'bg-blue-100 text-blue-800' :
                      activity.action === 'update' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'
                    ]">
                      {{ activity.action }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ activity.resource }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ activity.ip_address }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDateTime(activity.timestamp) }}
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

        <!-- Security Settings Tab -->
        <div v-if="activeTab === 'settings'">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Security Settings</h3>
            <p class="mt-1 text-sm text-gray-500">Configure system-wide security policies.</p>
          </div>

          <div class="space-y-6">
            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Authentication Settings</h4>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="text-sm font-medium text-gray-900">Require Two-Factor Authentication</h5>
                    <p class="text-sm text-gray-500">Force all admin users to enable 2FA</p>
                  </div>
                  <button
                    @click="securitySettings.require2FA = !securitySettings.require2FA"
                    :class="securitySettings.require2FA ? 'bg-indigo-600' : 'bg-gray-200'"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    <span
                      :class="securitySettings.require2FA ? 'translate-x-5' : 'translate-x-0'"
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    ></span>
                  </button>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="text-sm font-medium text-gray-900">Session Timeout</h5>
                    <p class="text-sm text-gray-500">Automatically log out inactive users</p>
                  </div>
                  <select
                    v-model="securitySettings.sessionTimeout"
                    class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  >
                    <option value="30">30 minutes</option>
                    <option value="60">1 hour</option>
                    <option value="120">2 hours</option>
                    <option value="240">4 hours</option>
                  </select>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="text-sm font-medium text-gray-900">Password Complexity</h5>
                    <p class="text-sm text-gray-500">Minimum password requirements</p>
                  </div>
                  <select
                    v-model="securitySettings.passwordComplexity"
                    class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  >
                    <option value="basic">Basic</option>
                    <option value="medium">Medium</option>
                    <option value="strong">Strong</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Access Control</h4>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="text-sm font-medium text-gray-900">IP Whitelist</h5>
                    <p class="text-sm text-gray-500">Restrict admin access to specific IP addresses</p>
                  </div>
                  <button
                    @click="securitySettings.ipWhitelist = !securitySettings.ipWhitelist"
                    :class="securitySettings.ipWhitelist ? 'bg-indigo-600' : 'bg-gray-200'"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    <span
                      :class="securitySettings.ipWhitelist ? 'translate-x-5' : 'translate-x-0'"
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    ></span>
                  </button>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <h5 class="text-sm font-medium text-gray-900">Failed Login Lockout</h5>
                    <p class="text-sm text-gray-500">Lock accounts after failed attempts</p>
                  </div>
                  <select
                    v-model="securitySettings.loginAttempts"
                    class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                  >
                    <option value="3">3 attempts</option>
                    <option value="5">5 attempts</option>
                    <option value="10">10 attempts</option>
                    <option value="0">Disabled</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <button
                @click="saveSecuritySettings"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Save Settings
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

const activeTab = ref('roles')
const showCreateRole = ref(false)
const activityFilter = ref('')
const userFilter = ref('')
const timeFilter = ref('24h')

const securityStats = ref({
  score: 92,
  activeUsers: 45,
  onlineNow: 12,
  alerts: 3,
  newAlerts: 1,
  failedLogins: 8
})

const tabs = [
  { name: 'roles', label: 'Roles & Permissions' },
  { name: 'activity', label: 'Activity Monitoring' },
  { name: 'settings', label: 'Security Settings' }
]

const userRoles = ref([
  {
    id: 1,
    name: 'Super Admin',
    description: 'Full system access and control',
    level: 'high',
    user_count: 2,
    permissions: ['all']
  },
  {
    id: 2,
    name: 'Admin',
    description: 'Administrative access with some restrictions',
    level: 'high',
    user_count: 5,
    permissions: ['manage_events', 'manage_users', 'view_reports']
  },
  {
    id: 3,
    name: 'Event Organizer',
    description: 'Can create and manage their own events',
    level: 'medium',
    user_count: 23,
    permissions: ['create_events', 'manage_own_events', 'view_sales']
  },
  {
    id: 4,
    name: 'Staff',
    description: 'Check-in and basic event operations',
    level: 'low',
    user_count: 15,
    permissions: ['checkin_tickets', 'view_events']
  }
])

const systemUsers = ref([
  { id: 1, name: 'John Admin' },
  { id: 2, name: 'Jane Manager' },
  { id: 3, name: 'Bob Staff' }
])

const activities = ref([
  {
    id: 1,
    user_name: 'John Admin',
    user_role: 'Super Admin',
    action: 'login',
    resource: 'Admin Dashboard',
    ip_address: '192.168.1.100',
    timestamp: new Date().toISOString(),
    status: 'success'
  },
  {
    id: 2,
    user_name: 'Jane Manager',
    user_role: 'Admin',
    action: 'create',
    resource: 'Event: Summer Festival',
    ip_address: '192.168.1.101',
    timestamp: new Date(Date.now() - 300000).toISOString(),
    status: 'success'
  },
  {
    id: 3,
    user_name: 'Bob Staff',
    user_role: 'Staff',
    action: 'login',
    resource: 'Check-in System',
    ip_address: '192.168.1.102',
    timestamp: new Date(Date.now() - 600000).toISOString(),
    status: 'failed'
  }
])

const securitySettings = ref({
  require2FA: true,
  sessionTimeout: 60,
  passwordComplexity: 'strong',
  ipWhitelist: false,
  loginAttempts: 5
})

const filteredActivities = computed(() => {
  let filtered = activities.value

  if (activityFilter.value) {
    filtered = filtered.filter(activity => activity.action === activityFilter.value)
  }

  if (userFilter.value) {
    filtered = filtered.filter(activity => activity.user_name.includes(userFilter.value))
  }

  return filtered
})

const editRole = (role: any) => {
  console.log('Editing role:', role.id)
  alert(`Editing role: ${role.name}\nThis will open the role permissions editor.`)
  // TODO: Implement role editing with permissions management
}

const deleteRole = (roleId: number) => {
  const role = roles.value.find(r => r.id === roleId)
  if (confirm(`Are you sure you want to delete role "${role?.name}"?\n\nThis action cannot be undone and may affect users with this role.`)) {
    const roleIndex = roles.value.findIndex(r => r.id === roleId)
    if (roleIndex !== -1) {
      const deletedRole = roles.value[roleIndex]
      roles.value.splice(roleIndex, 1)
      alert(`Role "${deletedRole.name}" has been deleted successfully!`)
    }
    console.log('Deleted role:', roleId)
    // TODO: Delete role via API
  }
}

const saveSecuritySettings = async () => {
  console.log('Saving security settings:', securitySettings.value)
  // TODO: Submit to API
  alert('Security settings saved successfully!')
}

const formatDateTime = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleString()
}

onMounted(() => {
  // TODO: Fetch security data from API
  console.log('Loading security data...')
})
</script>
