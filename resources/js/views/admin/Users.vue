<template>
  <div>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">User Management</h1>
        <p class="mt-2 text-sm text-gray-700">
          Manage all users on the platform.
        </p>
      </div>
    </div>

    <!-- Search and Filters -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700">Search Users</label>
          <input
            v-model="searchQuery"
            type="text"
            id="search"
            placeholder="Search by name or email..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>
        <div>
          <label for="role-filter" class="block text-sm font-medium text-gray-700">Filter by Role</label>
          <select
            v-model="roleFilter"
            id="role-filter"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          >
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="organizer">Organizer</option>
            <option value="customer">Customer</option>
          </select>
        </div>
        <div>
          <label for="status-filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
          <select
            v-model="statusFilter"
            id="status-filter"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          >
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    User
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Joined
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Last Active
                  </th>
                  <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in filteredUsers" :key="user.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">
                            {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ user.first_name }} {{ user.last_name }}
                        </div>
                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="{
                        'bg-red-100 text-red-800': user.role === 'admin',
                        'bg-blue-100 text-blue-800': user.role === 'organizer',
                        'bg-gray-100 text-gray-800': user.role === 'customer'
                      }"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ user.role }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="{
                        'bg-green-100 text-green-800': user.status === 'active',
                        'bg-gray-100 text-gray-800': user.status === 'inactive',
                        'bg-red-100 text-red-800': user.status === 'suspended'
                      }"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ user.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.last_login_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                      <button 
                        @click="viewUser(user)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        View
                      </button>
                      <button 
                        @click="editUser(user)"
                        class="text-green-600 hover:text-green-900"
                      >
                        Edit
                      </button>
                      <button 
                        @click="toggleUserStatus(user)"
                        :class="user.status === 'suspended' ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900'"
                      >
                        {{ user.status === 'suspended' ? 'Unsuspend' : 'Suspend' }}
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Edit User</h3>
          <form @submit.prevent="updateUser">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  v-model="editForm.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  v-model="editForm.email"
                  type="email"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select
                  v-model="editForm.role"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="user">User</option>
                  <option value="organizer">Organizer</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select
                  v-model="editForm.status"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="active">Active</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
              >
                Update User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const users = ref<any[]>([])
const showEditModal = ref(false)
const editingUser = ref<any>(null)
const editForm = ref({
  name: '',
  email: '',
  role: '',
  status: ''
})

const searchQuery = ref('')
const roleFilter = ref('')
const statusFilter = ref('')

const filteredUsers = computed(() => {
  return users.value.filter(user => {
    const matchesSearch = !searchQuery.value || 
      user.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.last_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesRole = !roleFilter.value || user.role === roleFilter.value
    const matchesStatus = !statusFilter.value || user.status === statusFilter.value
    
    return matchesSearch && matchesRole && matchesStatus
  })
})

const formatDate = (date: string) => {
  if (!date) return 'Never'
  return new Date(date).toLocaleDateString()
}

const viewUser = (user: any) => {
  console.log('Viewing user:', user.id)
  // TODO: Implement user view modal
}

const editUser = (user: any) => {
  editingUser.value = user
  editForm.value = {
    name: `${user.first_name} ${user.last_name}`,
    email: user.email,
    role: user.role,
    status: user.status
  }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingUser.value = null
  editForm.value = {
    name: '',
    email: '',
    role: '',
    status: ''
  }
}

const updateUser = () => {
  if (editingUser.value) {
    const userIndex = users.value.findIndex(u => u.id === editingUser.value.id)
    if (userIndex !== -1) {
      const [firstName, ...lastNameParts] = editForm.value.name.split(' ')
      users.value[userIndex] = {
        ...users.value[userIndex],
        first_name: firstName,
        last_name: lastNameParts.join(' ') || '',
        email: editForm.value.email,
        role: editForm.value.role,
        status: editForm.value.status
      }
    }
    alert('User updated successfully!')
    closeEditModal()
  }
}

const toggleUserStatus = (user: any) => {
  const newStatus = user.status === 'suspended' ? 'active' : 'suspended'
  if (confirm(`Are you sure you want to ${newStatus === 'suspended' ? 'suspend' : 'unsuspend'} this user?`)) {
    user.status = newStatus
    console.log(`User ${user.id} status changed to ${newStatus}`)
    // TODO: Update via API
  }
}

onMounted(() => {
  // TODO: Fetch users from API
  users.value = [
    {
      id: 1,
      first_name: 'John',
      last_name: 'Doe',
      email: 'john@example.com',
      role: 'organizer',
      status: 'active',
      created_at: '2024-12-01',
      last_login_at: '2025-01-10'
    },
    {
      id: 2,
      first_name: 'Jane',
      last_name: 'Smith',
      email: 'jane@example.com',
      role: 'customer',
      status: 'active',
      created_at: '2024-11-15',
      last_login_at: '2025-01-09'
    },
    {
      id: 3,
      first_name: 'Bob',
      last_name: 'Johnson',
      email: 'bob@example.com',
      role: 'admin',
      status: 'active',
      created_at: '2024-10-01',
      last_login_at: '2025-01-10'
    }
  ]
})
</script>
