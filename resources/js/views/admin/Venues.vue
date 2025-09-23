<template>
  <div>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Venue Management</h1>
        <p class="mt-2 text-sm text-gray-700">
          Manage all venues on the platform.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button
          @click="showCreateModal = true"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
        >
          Add Venue
        </button>
      </div>
    </div>

    <!-- Venues Table -->
    <div class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Venue
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Location
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Capacity
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Events Hosted
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="venue in venues" :key="venue.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ venue.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ venue.address }}</div>
                    <div class="text-sm text-gray-500">{{ venue.city }}, {{ venue.state }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ venue.capacity.toLocaleString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ venue.events_count || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="{
                        'bg-green-100 text-green-800': venue.is_active,
                        'bg-red-100 text-red-800': !venue.is_active
                      }"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ venue.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                      <button 
                        @click="editVenue(venue)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Edit
                      </button>
                      <button 
                        @click="toggleVenueStatus(venue)"
                        :class="venue.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                      >
                        {{ venue.is_active ? 'Deactivate' : 'Activate' }}
                      </button>
                      <button 
                        @click="deleteVenue(venue)"
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
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ editingVenue ? 'Edit Venue' : 'Add New Venue' }}
          </h3>
          <form @submit.prevent="createVenue">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Venue Name</label>
                <input
                  v-model="venueForm.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <textarea
                  v-model="venueForm.address"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">City</label>
                  <input
                    v-model="venueForm.city"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">State</label>
                  <input
                    v-model="venueForm.state"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Capacity</label>
                <input
                  v-model="venueForm.capacity"
                  type="number"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                @click="showCreateModal = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
              >
                {{ editingVenue ? 'Update Venue' : 'Add Venue' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiService from '@/services/api'
import type { CreateVenueRequest } from '@/types'

const venues = ref<any[]>([])
const showCreateModal = ref(false)
const editingVenue = ref<any>(null)
const venueForm = ref({
  name: '',
  address: '',
  city: '',
  state: '',
  capacity: 100
})

const editVenue = (venue: any) => {
  editingVenue.value = venue
  // Populate form with venue data
  venueForm.value = {
    name: venue.name,
    address: venue.address,
    city: venue.city,
    state: venue.state,
    capacity: venue.capacity
  }
  showCreateModal.value = true
}

const resetForm = () => {
  venueForm.value = {
    name: '',
    address: '',
    city: '',
    state: '',
    capacity: 100
  }
  editingVenue.value = null
}

const toggleVenueStatus = (venue: any) => {
  venue.is_active = !venue.is_active
  console.log('Toggling venue status:', venue.id, venue.is_active)
  // TODO: Update via API
}

const deleteVenue = async (venue: any) => {
  if (confirm(`Are you sure you want to delete "${venue.name}"?\n\nThis action cannot be undone.`)) {
    try {
      await apiService.deleteVenue(venue.id)
      const venueIndex = venues.value.findIndex(v => v.id === venue.id)
      if (venueIndex !== -1) {
        venues.value.splice(venueIndex, 1)
      }
      alert(`Venue "${venue.name}" has been deleted successfully!`)
    } catch (error) {
      console.error('Failed to delete venue:', error)
      alert(`Failed to delete venue: ${apiService.handleError(error)}`)
    }
  }
}

const createVenue = async () => {
  try {
    if (editingVenue.value) {
      // Update existing venue
      const updatedVenueData = {
        name: venueForm.value.name,
        address: venueForm.value.address,
        city: venueForm.value.city,
        state: venueForm.value.state,
        country: 'USA', // Default country
        postal_code: '00000', // Default postal code
        capacity: parseInt(venueForm.value.capacity.toString()),
        is_active: true
      }
      
      const updatedVenue = await apiService.updateVenue(editingVenue.value.id, updatedVenueData)
      
      const venueIndex = venues.value.findIndex(v => v.id === editingVenue.value.id)
      if (venueIndex !== -1) {
        venues.value[venueIndex] = {
          ...venues.value[venueIndex],
          ...updatedVenue,
          events_count: venues.value[venueIndex].events_count // Preserve events_count
        }
      }
      alert('Venue updated successfully!')
    } else {
      // Create new venue
      const newVenueData: CreateVenueRequest = {
        name: venueForm.value.name,
        address: venueForm.value.address,
        city: venueForm.value.city,
        state: venueForm.value.state,
        country: 'USA',
        postal_code: '00000',
        capacity: parseInt(venueForm.value.capacity.toString())
      }
      
      const newVenue = await apiService.createVenue(newVenueData)
      venues.value.push({
        ...newVenue,
        events_count: 0
      })
      alert('Venue created successfully!')
    }
    
    showCreateModal.value = false
    resetForm()
  } catch (error) {
    console.error('Failed to create/update venue:', error)
    alert(`Failed to save venue: ${apiService.handleError(error)}`)
  }
}

onMounted(async () => {
  try {
    // Load venues from backend API
    const venuesData = await apiService.getVenues()
    venues.value = venuesData.data.map(venue => ({
      ...venue,
      events_count: 0 // This would come from events count query
    }))
  } catch (error) {
    console.error('Failed to load venues:', error)
    // Fallback to default venues if API fails
    venues.value = [
      {
        id: 1,
        name: 'Convention Center',
        address: '123 Main Street',
        city: 'New York',
        state: 'NY',
        capacity: 1000,
        events_count: 15,
        is_active: true
      },
      {
        id: 2,
        name: 'Community Hall',
        address: '456 Oak Avenue',
        city: 'Los Angeles',
        state: 'CA',
        capacity: 200,
        events_count: 8,
        is_active: true
      }
    ]
  }
})
</script>
