<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">All Events</h1>
      <p class="mt-2 text-sm text-gray-600">Manage all events in the system.</p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex items-center">
          <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600 mr-3"></div>
          <span class="text-sm text-gray-600">Loading events...</span>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-else-if="errorMessage" class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
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
              <tr v-for="event in events" :key="event.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ event.title }}</div>
                  <div class="text-sm text-gray-500">{{ event.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ event.venue }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ event.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusClass(event.status)">
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                  <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-900">Delete</button>
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
          <p class="text-sm font-medium text-green-800">Events page loaded successfully!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import apiService from '@/services/api'

const events = ref<any[]>([])
const isLoading = ref(false)
const errorMessage = ref('')

const showSuccessMessage = ref(false)

const fetchEvents = async () => {
  try {
    isLoading.value = true
    errorMessage.value = ''

    console.log('=== Fetching Events ===')

    // Check authentication status
    const authStore = useAuthStore()
    console.log('Auth status:', {
      isAuthenticated: authStore.isAuthenticated,
      userId: authStore.user?.id,
      tokenExists: !!localStorage.getItem('auth_token'),
      tokenValue: localStorage.getItem('auth_token')?.substring(0, 20) + '...'
    })

    // Check API service headers
    console.log('API service headers:', {
      Authorization: `Bearer ${localStorage.getItem('auth_token')?.substring(0, 20)}...`,
      ContentType: 'application/json'
    })

    // For admin users, we need to get all events
    // Use the all-events endpoint which checks permissions
    console.log('Attempting to fetch events from /all-events')
    const response = await apiService.get('/all-events')
    console.log('API response:', response)

    events.value = response.data.data || []
    console.log('Events loaded successfully:', events.value.length, 'events')

    // Show events data
    if (events.value.length > 0) {
      console.log('First event sample:', {
        id: events.value[0].id,
        title: events.value[0].title,
        venue: events.value[0].venue?.name,
        organizer: events.value[0].organizer?.name
      })
    }
  } catch (error: any) {
    console.error('Failed to fetch events:', error)
    console.error('Error details:', {
      message: error.message,
      status: error.response?.status,
      data: error.response?.data,
      request: {
        url: error.config?.url,
        headers: error.config?.headers
      }
    })

    errorMessage.value = `Failed to load events: ${error.response?.data?.message || error.message}`
    // Fallback to static data if API fails
    events.value = [
      {
        id: 1,
        title: 'Summer Music Festival',
        description: 'A spectacular summer music festival',
        venue: 'Central Park',
        date: '2025-07-15',
        status: 'active'
      },
      {
        id: 2,
        title: 'Tech Conference 2025',
        description: 'The largest technology conference',
        venue: 'Convention Center',
        date: '2025-03-20',
        status: 'pending'
      },
      {
        id: 3,
        title: 'Art Exhibition Opening',
        description: 'Contemporary art exhibition',
        venue: 'Modern Art Gallery',
        date: '2025-04-10',
        status: 'draft'
      }
    ]
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchEvents()
})

const getStatusClass = (status: string) => {
  switch (status) {
    case 'active':
      return 'bg-green-100 text-green-800'
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'draft':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

onMounted(() => {
  console.log('Events page mounted successfully')
  showSuccessMessage.value = true
  setTimeout(() => {
    showSuccessMessage.value = false
  }, 3000)
})
</script>