<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Customer Support</h1>
      <p class="mt-2 text-sm text-gray-600">Handle customer inquiries, complaints, and support requests.</p>
    </div>

    <!-- Support Statistics -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Open Tickets</dt>
                <dd class="text-lg font-medium text-gray-900">{{ supportStats.openTickets }}</dd>
                <dd class="text-xs text-blue-600">{{ supportStats.newToday }} new today</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Avg Response Time</dt>
                <dd class="text-lg font-medium text-gray-900">{{ supportStats.avgResponseTime }}h</dd>
                <dd class="text-xs text-yellow-600">{{ supportStats.responseTimeChange }}% improvement</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Resolution Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ supportStats.resolutionRate }}%</dd>
                <dd class="text-xs text-green-600">{{ supportStats.resolvedToday }} resolved today</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Satisfaction Score</dt>
                <dd class="text-lg font-medium text-gray-900">{{ supportStats.satisfactionScore }}/5</dd>
                <dd class="text-xs text-purple-600">{{ supportStats.totalRatings }} ratings</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Support Tickets -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Support Tickets</h2>
            <p class="mt-1 text-sm text-gray-500">Manage customer support requests and inquiries.</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
            <select
              v-model="statusFilter"
              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              <option value="">All Status</option>
              <option value="open">Open</option>
              <option value="in-progress">In Progress</option>
              <option value="resolved">Resolved</option>
              <option value="closed">Closed</option>
            </select>
            <select
              v-model="priorityFilter"
              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              <option value="">All Priority</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned</th>
              <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="ticket in filteredTickets" :key="ticket.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ ticket.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ ticket.customer_name }}</div>
                <div class="text-sm text-gray-500">{{ ticket.customer_email }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ ticket.subject }}</div>
                <div class="text-sm text-gray-500 truncate max-w-xs">{{ ticket.message }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  ticket.priority === 'urgent' ? 'bg-red-100 text-red-800' :
                  ticket.priority === 'high' ? 'bg-orange-100 text-orange-800' :
                  ticket.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-green-100 text-green-800'
                ]">
                  {{ ticket.priority }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  ticket.status === 'resolved' ? 'bg-green-100 text-green-800' :
                  ticket.status === 'in-progress' ? 'bg-blue-100 text-blue-800' :
                  ticket.status === 'open' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ ticket.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(ticket.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ ticket.assigned_to || 'Unassigned' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewTicket(ticket)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    View
                  </button>
                  <button 
                    @click="assignTicket(ticket)"
                    class="text-green-600 hover:text-green-900"
                  >
                    Assign
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

const statusFilter = ref('')
const priorityFilter = ref('')

const supportStats = ref({
  openTickets: 23,
  newToday: 5,
  avgResponseTime: 2.4,
  responseTimeChange: -15,
  resolutionRate: 94.5,
  resolvedToday: 12,
  satisfactionScore: 4.7,
  totalRatings: 156
})

const supportTickets = ref([
  {
    id: 1001,
    customer_name: 'John Doe',
    customer_email: 'john@example.com',
    subject: 'Ticket refund request',
    message: 'I need to cancel my ticket due to a family emergency. Can I get a refund?',
    priority: 'medium',
    status: 'open',
    created_at: '2025-01-10',
    assigned_to: null
  },
  {
    id: 1002,
    customer_name: 'Jane Smith',
    customer_email: 'jane@example.com',
    subject: 'Unable to download tickets',
    message: 'I purchased tickets but the download link is not working.',
    priority: 'high',
    status: 'in-progress',
    created_at: '2025-01-10',
    assigned_to: 'Support Agent 1'
  },
  {
    id: 1003,
    customer_name: 'Bob Johnson',
    customer_email: 'bob@example.com',
    subject: 'Event venue change inquiry',
    message: 'I heard the venue changed. Is this true and do I need new tickets?',
    priority: 'low',
    status: 'resolved',
    created_at: '2025-01-09',
    assigned_to: 'Support Agent 2'
  },
  {
    id: 1004,
    customer_name: 'Alice Williams',
    customer_email: 'alice@example.com',
    subject: 'Payment processing error',
    message: 'My payment was charged but I did not receive confirmation or tickets.',
    priority: 'urgent',
    status: 'open',
    created_at: '2025-01-10',
    assigned_to: null
  }
])

const filteredTickets = computed(() => {
  let filtered = supportTickets.value

  if (statusFilter.value) {
    filtered = filtered.filter(ticket => ticket.status === statusFilter.value)
  }

  if (priorityFilter.value) {
    filtered = filtered.filter(ticket => ticket.priority === priorityFilter.value)
  }

  return filtered.sort((a, b) => {
    // Sort by priority (urgent first) then by date
    const priorityOrder = { urgent: 4, high: 3, medium: 2, low: 1 }
    const aPriority = priorityOrder[a.priority] || 0
    const bPriority = priorityOrder[b.priority] || 0
    
    if (aPriority !== bPriority) {
      return bPriority - aPriority
    }
    
    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
  })
})

const viewTicket = (ticket: any) => {
  console.log('Viewing ticket:', ticket.id)
  // TODO: Open ticket details modal
}

const assignTicket = (ticket: any) => {
  console.log('Assigning ticket:', ticket.id)
  // TODO: Open assignment modal
}

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch support data from API
  console.log('Loading customer support data...')
})
</script>
