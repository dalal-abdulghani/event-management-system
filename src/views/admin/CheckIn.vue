<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Check-In System</h1>
      <p class="mt-2 text-sm text-gray-600">Scan tickets and manage event entry validation.</p>
    </div>

    <!-- Today's Check-in Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
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
                <dt class="text-sm font-medium text-gray-500 truncate">Checked In Today</dt>
                <dd class="text-lg font-medium text-gray-900">{{ checkInStats.checkedInToday.toLocaleString() }}</dd>
                <dd class="text-xs text-green-600">{{ checkInStats.attendanceRate }}% attendance rate</dd>
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
                <dt class="text-sm font-medium text-gray-500 truncate">Pending Entry</dt>
                <dd class="text-lg font-medium text-gray-900">{{ checkInStats.pendingEntry }}</dd>
                <dd class="text-xs text-yellow-600">Awaiting check-in</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">No-shows</dt>
                <dd class="text-lg font-medium text-gray-900">{{ checkInStats.noShows }}</dd>
                <dd class="text-xs text-red-600">{{ checkInStats.noShowRate }}% no-show rate</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Avg Check-in Time</dt>
                <dd class="text-lg font-medium text-gray-900">{{ checkInStats.avgCheckInTime }}s</dd>
                <dd class="text-xs text-blue-600">Per attendee</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Check-in Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <!-- QR Scanner -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">QR Code Scanner</h2>
        </div>
        <div class="p-6">
          <div class="border-2 border-dashed border-gray-300 rounded-lg h-64 flex items-center justify-center mb-4">
            <div class="text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M12 8h4.01"></path>
              </svg>
              <p class="mt-2 text-sm text-gray-500">
                {{ scannerActive ? 'Scanner is active - point camera at QR code' : 'Click to activate scanner' }}
              </p>
              <button 
                @click="toggleScanner"
                class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                {{ scannerActive ? 'Stop Scanner' : 'Start Scanner' }}
              </button>
            </div>
          </div>
          
          <div v-if="lastScanResult" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
            <div class="flex">
              <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Successfully checked in!</p>
                <p class="text-xs text-green-700">{{ lastScanResult }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Manual Entry -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Manual Entry</h2>
        </div>
        <div class="p-6">
          <form @submit.prevent="manualCheckIn">
            <div class="space-y-4">
              <div>
                <label for="ticket-number" class="block text-sm font-medium text-gray-700">Ticket Number</label>
                <input
                  v-model="ticketNumber"
                  type="text"
                  id="ticket-number"
                  placeholder="Enter ticket number or email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              
              <div>
                <label for="event-select" class="block text-sm font-medium text-gray-700">Event (Optional)</label>
                <select
                  v-model="selectedEvent"
                  id="event-select"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="">Auto-detect from ticket</option>
                  <option v-for="event in activeEvents" :key="event.id" :value="event.id">
                    {{ event.name }} - {{ formatDate(event.date) }}
                  </option>
                </select>
              </div>

              <button
                type="submit"
                :disabled="!ticketNumber || processing"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
              >
                {{ processing ? 'Processing...' : 'Check In' }}
              </button>
            </div>
          </form>

          <div v-if="manualCheckInResult" class="mt-4">
            <div v-if="manualCheckInResult.success" class="p-3 bg-green-50 border border-green-200 rounded-md">
              <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800">Check-in successful!</p>
                  <p class="text-xs text-green-700">{{ manualCheckInResult.message }}</p>
                </div>
              </div>
            </div>
            <div v-else class="p-3 bg-red-50 border border-red-200 rounded-md">
              <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">Check-in failed</p>
                  <p class="text-xs text-red-700">{{ manualCheckInResult.message }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Check-ins -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Recent Check-ins</h2>
            <p class="mt-1 text-sm text-gray-500">Latest attendee entries and validations.</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="refreshCheckIns"
              class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              Refresh
            </button>
          </div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="checkin in recentCheckIns" :key="checkin.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ checkin.ticket_number }}</div>
                <div class="text-sm text-gray-500">{{ checkin.ticket_type }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ checkin.attendee_name }}</div>
                <div class="text-sm text-gray-500">{{ checkin.attendee_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ checkin.event_title }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDateTime(checkin.checked_in_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  checkin.method === 'qr' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                ]">
                  {{ checkin.method === 'qr' ? 'QR Scan' : 'Manual' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                  Checked In
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button 
                  @click="viewTicketDetails(checkin)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  View Details
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const scannerActive = ref(false)
const lastScanResult = ref('')
const ticketNumber = ref('')
const selectedEvent = ref('')
const processing = ref(false)
const manualCheckInResult = ref<any>(null)

const checkInStats = ref({
  checkedInToday: 1247,
  attendanceRate: 94.2,
  pendingEntry: 89,
  noShows: 34,
  noShowRate: 5.8,
  avgCheckInTime: 2.3
})

const activeEvents = ref([
  { id: 1, name: 'Summer Music Festival', date: '2025-01-20' },
  { id: 2, name: 'Tech Conference', date: '2025-01-21' },
  { id: 3, name: 'Art Exhibition', date: '2025-01-22' }
])

const recentCheckIns = ref([
  {
    id: 1,
    ticket_number: 'T001234567',
    ticket_type: 'General Admission',
    attendee_name: 'John Doe',
    attendee_email: 'john@example.com',
    event_title: 'Summer Music Festival',
    checked_in_at: new Date().toISOString(),
    method: 'qr'
  },
  {
    id: 2,
    ticket_number: 'T001234568',
    ticket_type: 'VIP',
    attendee_name: 'Jane Smith',
    attendee_email: 'jane@example.com',
    event_title: 'Tech Conference',
    checked_in_at: new Date(Date.now() - 300000).toISOString(),
    method: 'manual'
  }
])

const toggleScanner = () => {
  scannerActive.value = !scannerActive.value
  if (scannerActive.value) {
    // TODO: Integrate with vue3-qr-reader
    console.log('Starting QR scanner...')
  }
}

const manualCheckIn = async () => {
  if (!ticketNumber.value) return
  
  processing.value = true
  manualCheckInResult.value = null
  
  try {
    console.log('Manual check-in for:', ticketNumber.value)
    // TODO: Submit to API
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Mock success
    manualCheckInResult.value = {
      success: true,
      message: `Successfully checked in ticket ${ticketNumber.value}`
    }
    
    // Add to recent check-ins
    recentCheckIns.value.unshift({
      id: Date.now(),
      ticket_number: ticketNumber.value,
      ticket_type: 'General Admission',
      attendee_name: 'Manual Entry',
      attendee_email: 'manual@example.com',
      event_title: 'Current Event',
      checked_in_at: new Date().toISOString(),
      method: 'manual'
    })
    
    ticketNumber.value = ''
  } catch (error) {
    manualCheckInResult.value = {
      success: false,
      message: 'Invalid ticket number or already checked in'
    }
  } finally {
    processing.value = false
  }
}

const refreshCheckIns = () => {
  console.log('Refreshing check-ins...')
  // TODO: Fetch latest check-ins from API
}

const viewTicketDetails = (checkin: any) => {
  console.log('Viewing details for:', checkin.id)
  // TODO: Show ticket details modal
}

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

const formatDateTime = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleString()
}

onMounted(() => {
  // TODO: Fetch check-in data from API
  console.log('Loading check-in system...')
})
</script>
