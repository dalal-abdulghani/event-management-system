<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
      <p class="mt-2 text-sm text-gray-600">Update event details and configurations.</p>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="mt-4 text-gray-500">Loading event details...</p>
    </div>

    <form v-else @submit.prevent="updateEvent" class="space-y-8">
      <!-- Basic Information -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
            <input
              v-model="form.title"
              type="text"
              id="title"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <div class="sm:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              id="description"
              rows="4"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
          </div>

          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select
              v-model="form.category"
              id="category"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">Select Category</option>
              <option value="music">Music</option>
              <option value="conference">Conference</option>
              <option value="workshop">Workshop</option>
              <option value="sports">Sports</option>
              <option value="arts">Arts & Culture</option>
              <option value="networking">Networking</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              id="status"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Date & Time -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Date & Time</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div>
            <label for="start-date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input
              v-model="form.start_date"
              type="date"
              id="start-date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <div>
            <label for="start-time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input
              v-model="form.start_time"
              type="time"
              id="start-time"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <div>
            <label for="end-time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input
              v-model="form.end_time"
              type="time"
              id="end-time"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
        </div>
      </div>

      <!-- Ticket Types -->
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-medium text-gray-900">Ticket Types</h2>
          <button
            type="button"
            @click="addTicketType"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
          >
            Add Ticket Type
          </button>
        </div>

        <div class="space-y-4">
          <div v-for="(ticket, index) in form.ticket_types" :key="index" class="border border-gray-200 rounded-lg p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  v-model="ticket.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                <input
                  v-model="ticket.price"
                  type="number"
                  min="0"
                  step="0.01"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input
                  v-model="ticket.quantity"
                  type="number"
                  min="1"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div class="flex items-end">
                <button
                  type="button"
                  @click="removeTicketType(index)"
                  class="inline-flex items-center px-3 py-2 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100"
                >
                  Remove
                </button>
              </div>
            </div>
            <div class="mt-2">
              <label class="block text-sm font-medium text-gray-700">Description</label>
              <input
                v-model="ticket.description"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Event Statistics -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Event Statistics</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600">{{ eventStats.tickets_sold }}</div>
            <div class="text-sm text-gray-500">Tickets Sold</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">${{ eventStats.revenue.toLocaleString() }}</div>
            <div class="text-sm text-gray-500">Revenue Generated</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ eventStats.attendance_rate }}%</div>
            <div class="text-sm text-gray-500">Expected Attendance</div>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end space-x-3">
        <router-link
          to="/admin/events"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          Cancel
        </router-link>
        <button
          type="submit"
          :disabled="saving"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ saving ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const saving = ref(false)

const form = ref({
  title: '',
  description: '',
  category: '',
  status: 'draft',
  start_date: '',
  start_time: '',
  end_time: '',
  ticket_types: []
})

const eventStats = ref({
  tickets_sold: 0,
  revenue: 0,
  attendance_rate: 0
})

const addTicketType = () => {
  form.value.ticket_types.push({
    name: '',
    price: 0,
    quantity: 1,
    description: ''
  })
}

const removeTicketType = (index: number) => {
  if (form.value.ticket_types.length > 1) {
    form.value.ticket_types.splice(index, 1)
  }
}

const updateEvent = async () => {
  saving.value = true
  try {
    console.log('Updating event:', route.params.id, form.value)
    // TODO: Submit to API
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    router.push('/admin/events')
  } catch (error) {
    console.error('Failed to update event:', error)
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  try {
    console.log('Loading event for edit:', route.params.id)
    
    // Simulate loading
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Mock data
    form.value = {
      title: 'Summer Music Festival',
      description: 'A fantastic outdoor music festival featuring top artists.',
      category: 'music',
      status: 'published',
      start_date: '2025-07-15',
      start_time: '18:00',
      end_time: '23:00',
      ticket_types: [
        {
          name: 'General Admission',
          price: 50,
          quantity: 500,
          description: 'Standard entry ticket'
        },
        {
          name: 'VIP',
          price: 150,
          quantity: 100,
          description: 'Premium experience with perks'
        }
      ]
    }

    eventStats.value = {
      tickets_sold: 450,
      revenue: 22500,
      attendance_rate: 94
    }
  } catch (error) {
    console.error('Failed to load event:', error)
  } finally {
    loading.value = false
  }
})
</script>
