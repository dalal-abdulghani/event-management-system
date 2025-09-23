<template>
  <div>
    <div class="mb-6">
      <div class="flex items-center space-x-4">
        <button 
          @click="$router.go(-1)"
          class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
        >
          <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Customers
        </button>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Customer Profile</h1>
      <p class="mt-2 text-sm text-gray-600">Detailed view of customer information and purchase history.</p>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="mt-4 text-gray-500">Loading customer profile...</p>
    </div>

    <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Customer Info -->
      <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="text-center">
            <div class="h-20 w-20 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl font-medium text-gray-700">
                {{ customer.first_name?.[0] }}{{ customer.last_name?.[0] }}
              </span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ customer.first_name }} {{ customer.last_name }}</h2>
            <p class="text-sm text-gray-500">{{ customer.email }}</p>
            <p class="text-sm text-gray-500">{{ customer.phone }}</p>
            
            <div class="mt-4 flex justify-center">
              <span :class="[
                'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
                customer.segment === 'vip' ? 'bg-purple-100 text-purple-800' :
                customer.segment === 'loyal' ? 'bg-green-100 text-green-800' :
                customer.segment === 'new' ? 'bg-blue-100 text-blue-800' :
                'bg-gray-100 text-gray-800'
              ]">
                {{ customer.segment }} Customer
              </span>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500">Member Since</span>
              <span class="text-sm font-medium">{{ formatDate(customer.created_at) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500">Total Spent</span>
              <span class="text-sm font-medium">${{ customer.total_spent.toLocaleString() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500">Events Attended</span>
              <span class="text-sm font-medium">{{ customer.events_attended }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500">Last Purchase</span>
              <span class="text-sm font-medium">{{ formatDate(customer.last_purchase) }}</span>
            </div>
          </div>

          <div class="mt-6 space-y-2">
            <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
              Send Message
            </button>
            <button class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200">
              Edit Profile
            </button>
          </div>
        </div>
      </div>

      <!-- Purchase History & Activity -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Purchase History -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Purchase History</h3>
          </div>
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="order in customer.orders" :key="order.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ order.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ order.event_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ order.ticket_quantity }} x {{ order.ticket_type }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ order.total }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      order.status === 'completed' ? 'bg-green-100 text-green-800' :
                      order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'
                    ]">
                      {{ order.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Support Requests -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Support Requests</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div v-for="request in customer.support_requests" :key="request.id" class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">{{ request.subject }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ request.message }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ formatDate(request.created_at) }}</p>
                  </div>
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    request.status === 'resolved' ? 'bg-green-100 text-green-800' :
                    request.status === 'in-progress' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-red-100 text-red-800'
                  ]">
                    {{ request.status }}
                  </span>
                </div>
              </div>
              
              <div v-if="customer.support_requests.length === 0" class="text-center py-8 text-gray-500">
                No support requests found.
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Notes -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Internal Notes</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div v-for="note in customer.notes" :key="note.id" class="border-l-4 border-indigo-400 pl-4">
                <p class="text-sm text-gray-900">{{ note.content }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ note.author }} • {{ formatDate(note.created_at) }}</p>
              </div>
              
              <div class="mt-4">
                <textarea
                  v-model="newNote"
                  placeholder="Add a note about this customer..."
                  rows="3"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                ></textarea>
                <button
                  @click="addNote"
                  :disabled="!newNote.trim()"
                  class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
                >
                  Add Note
                </button>
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
import { useRoute } from 'vue-router'

const route = useRoute()
const loading = ref(true)
const newNote = ref('')

const customer = ref({
  id: null,
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  segment: '',
  total_spent: 0,
  events_attended: 0,
  last_purchase: '',
  created_at: '',
  orders: [],
  support_requests: [],
  notes: []
})

const addNote = () => {
  if (!newNote.value.trim()) return
  
  console.log('Adding note:', newNote.value)
  // TODO: Save note via API
  
  customer.value.notes.unshift({
    id: Date.now(),
    content: newNote.value,
    author: 'Admin User',
    created_at: new Date().toISOString()
  })
  
  newNote.value = ''
}

const formatDate = (date: string) => {
  if (!date) return 'Never'
  return new Date(date).toLocaleDateString()
}

onMounted(async () => {
  try {
    console.log('Loading customer profile:', route.params.id)
    
    // Simulate loading
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Mock customer data
    customer.value = {
      id: parseInt(route.params.id as string),
      first_name: 'John',
      last_name: 'Doe',
      email: 'john.doe@example.com',
      phone: '+1-555-0123',
      segment: 'vip',
      total_spent: 1250,
      events_attended: 8,
      last_purchase: '2025-01-08',
      created_at: '2024-06-15',
      orders: [
        {
          id: 1001,
          event_name: 'Summer Music Festival',
          ticket_type: 'VIP',
          ticket_quantity: 2,
          total: 300,
          created_at: '2025-01-08',
          status: 'completed'
        },
        {
          id: 1002,
          event_name: 'Tech Conference',
          ticket_type: 'Standard',
          ticket_quantity: 1,
          total: 75,
          created_at: '2024-12-15',
          status: 'completed'
        }
      ],
      support_requests: [
        {
          id: 1,
          subject: 'Ticket transfer request',
          message: 'I need to transfer my VIP ticket to my colleague.',
          status: 'resolved',
          created_at: '2025-01-05'
        }
      ],
      notes: [
        {
          id: 1,
          content: 'VIP customer, always purchases premium tickets.',
          author: 'Sales Team',
          created_at: '2024-12-01'
        }
      ]
    }
  } catch (error) {
    console.error('Failed to load customer:', error)
  } finally {
    loading.value = false
  }
})
</script>
