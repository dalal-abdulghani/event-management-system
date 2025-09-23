<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">All Events</h1>
      <p class="mt-2 text-sm text-gray-600">Manage all events in the system.</p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      <span class="ml-2 text-gray-600">Loading events...</span>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-red-800">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Events Table -->
    <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Events List</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">All events currently in the system.</p>
      </div>
      
      <div class="border-t border-gray-200">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="events.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                  No events found. Create your first event!
                </td>
              </tr>
              <tr v-for="event in events" :key="event.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ event.title }}</div>
                  <div class="text-sm text-gray-500">{{ event.short_description || event.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ event.venue?.name || 'No venue' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(event.start_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusClass(event.status)">
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="viewEvent(event.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                  <button @click="editEvent(event.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                  <button @click="deleteEvent(event.id)" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="showSuccessMessage" class="mt-4 bg-green-50 border border-green-200 rounded-md p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useEventsStore } from '@/stores/events'
import type { Event } from '@/types'

const eventsStore = useEventsStore()

// State
const events = ref<Event[]>([])
const isLoading = ref(false)
const error = ref('')
const showSuccessMessage = ref(false)
const successMessage = ref('')

// Methods
const loadEvents = async () => {
  try {
    isLoading.value = true
    error.value = ''
    await eventsStore.fetchEvents()
    events.value = eventsStore.events
    successMessage.value = 'Events loaded successfully!'
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 3000)
  } catch (err: any) {
    error.value = err.message || 'Failed to load events'
    console.error('Error loading events:', err)
  } finally {
    isLoading.value = false
  }
}

const viewEvent = (id: number) => {
  console.log('View event:', id)
  // Navigate to event details
}

const editEvent = (id: number) => {
  console.log('Edit event:', id)
  // Navigate to edit event
}

const deleteEvent = async (id: number) => {
  if (confirm('Are you sure you want to delete this event?')) {
    try {
      await eventsStore.deleteEvent(id)
      events.value = eventsStore.events
      successMessage.value = 'Event deleted successfully!'
      showSuccessMessage.value = true
      setTimeout(() => {
        showSuccessMessage.value = false
      }, 3000)
    } catch (err: any) {
      error.value = err.message || 'Failed to delete event'
    }
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const getStatusClass = (status: string) => {
  switch (status) {
    case 'published':
      return 'bg-green-100 text-green-800'
    case 'draft':
      return 'bg-yellow-100 text-yellow-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

onMounted(() => {
  console.log('Events page mounted successfully')
  loadEvents()
})
</script>