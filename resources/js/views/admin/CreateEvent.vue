<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Create New Event</h1>
      <p class="mt-2 text-sm text-gray-600">Set up a new event with all the details and configurations.</p>
    </div>

    <!-- Success Message -->
    <div v-if="showSuccessMessage" class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-green-800">
            Event Created Successfully!
          </h3>
          <div class="mt-2 text-sm text-green-700">
            <p>Your event has been created and is being processed. Redirecting to events list...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">
            Error Creating Event
          </h3>
          <div class="mt-2 text-sm text-red-700">
            <p>{{ errorMessage }}</p>
          </div>
        </div>
      </div>
    </div>

    <form @submit.prevent="createEvent" class="space-y-8">
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
            <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
            <select
              v-model="form.venue_id"
              id="venue"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">Select Venue</option>
              <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                {{ venue.name }} ({{ venue.capacity }} capacity)
              </option>
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

      <!-- Additional Settings -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Settings</h2>
        <div class="space-y-4">
          <div class="flex items-center">
            <input
              v-model="form.is_featured"
              id="featured"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="featured" class="ml-2 block text-sm text-gray-900">
              Feature this event on homepage
            </label>
          </div>

          <div class="flex items-center">
            <input
              v-model="form.requires_approval"
              id="approval"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="approval" class="ml-2 block text-sm text-gray-900">
              Require admin approval before publishing
            </label>
          </div>

          <div class="flex items-center">
            <input
              v-model="form.allow_refunds"
              id="refunds"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="refunds" class="ml-2 block text-sm text-gray-900">
              Allow ticket refunds
            </label>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex justify-end space-x-3">
        <button
          type="button"
          @click="$router.go(-1)"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ loading ? 'Creating...' : 'Create Event' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import apiService from '@/services/api'
import type { CreateEventRequest } from '@/types'

const router = useRouter()
const loading = ref(false)
const venues = ref<any[]>([])
const showSuccessMessage = ref(false)
const errorMessage = ref('')

const form = ref({
  title: '',
  description: '',
  category: '',
  venue_id: '',
  start_date: '',
  start_time: '',
  end_time: '',
  is_featured: false,
  requires_approval: true,
  allow_refunds: false,
  ticket_types: [
    {
      name: 'General Admission',
      price: 0,
      quantity: 100,
      description: 'Standard entry ticket'
    }
  ]
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

const validateForm = () => {
  const errors = []
  
  if (!form.value.title.trim()) {
    errors.push('Event title is required')
  }
  
  if (!form.value.category) {
    errors.push('Category is required')
  }
  
  if (!form.value.venue_id) {
    errors.push('Venue is required')
  }
  
  if (!form.value.start_date) {
    errors.push('Start date is required')
  }
  
  if (!form.value.start_time) {
    errors.push('Start time is required')
  }
  
  if (!form.value.end_time) {
    errors.push('End time is required')
  }
  
  // Validate ticket types
  let hasValidTicket = false
  form.value.ticket_types.forEach((ticket, index) => {
    if (!ticket.name.trim()) {
      errors.push(`Ticket type ${index + 1}: Name is required`)
    }
    if (ticket.price < 0) {
      errors.push(`Ticket type ${index + 1}: Price cannot be negative`)
    }
    if (ticket.quantity < 1) {
      errors.push(`Ticket type ${index + 1}: Quantity must be at least 1`)
    }
    if (ticket.name.trim() && ticket.quantity > 0) {
      hasValidTicket = true
    }
  })
  
  if (!hasValidTicket) {
    errors.push('At least one valid ticket type is required')
  }
  
  // Validate date and time
  if (form.value.start_date && form.value.start_time && form.value.end_time) {
    const startDateTime = new Date(`${form.value.start_date}T${form.value.start_time}`)
    const endDateTime = new Date(`${form.value.start_date}T${form.value.end_time}`)
    
    if (endDateTime <= startDateTime) {
      errors.push('End time must be after start time')
    }
    
    // Check if event is in the past
    if (startDateTime < new Date()) {
      errors.push('Event cannot be scheduled in the past')
    }
  }
  
  return errors
}

const createEvent = async () => {
  // Clear previous error messages
  errorMessage.value = ''
  
  // Validate form
  const validationErrors = validateForm()
  if (validationErrors.length > 0) {
    errorMessage.value = validationErrors.join(', ')
    return
  }
  
  loading.value = true
  
  try {
    // Prepare event data for API
    const eventData: CreateEventRequest = {
      title: form.value.title,
      description: form.value.description,
      venue_id: parseInt(form.value.venue_id),
      start_date: form.value.start_date,
      end_date: form.value.start_date, // Using start_date as end_date for now
      total_capacity: form.value.ticket_types.reduce((total, ticket) => total + ticket.quantity, 0),
      tags: [form.value.category]
    }
    
    console.log('Creating event:', eventData)
    
    // Call backend API
    const createdEvent = await apiService.createEvent(eventData)
    
    console.log('Event created successfully:', createdEvent)
    
    // Show success message
    showSuccessMessage.value = true
    
    // Redirect after showing success message
    setTimeout(() => {
      router.push('/admin/events')
    }, 2000)
    
  } catch (error) {
    console.error('Failed to create event:', error)
    errorMessage.value = apiService.handleError(error)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    // Fetch venues from backend API
    const venuesData = await apiService.getVenues()
    venues.value = venuesData.data
  } catch (error) {
    console.error('Failed to load venues:', error)
    // Fallback to default venues if API fails
    venues.value = [
      { id: 1, name: 'Convention Center', capacity: 1000 },
      { id: 2, name: 'Community Hall', capacity: 200 },
      { id: 3, name: 'Outdoor Amphitheater', capacity: 500 }
    ]
  }
})
</script>
