<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Event Schedule</h1>
      <p class="mt-2 text-sm text-gray-600">Manage sessions, speakers, and agenda for your event.</p>
    </div>

    <!-- Event Info -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-medium text-gray-900">{{ event.title }}</h2>
            <p class="text-sm text-gray-500">{{ formatDate(event.date) }} • {{ event.venue }}</p>
          </div>
          <span :class="[
            'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
            event.status === 'published' ? 'bg-green-100 text-green-800' :
            event.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
            'bg-gray-100 text-gray-800'
          ]">
            {{ event.status }}
          </span>
        </div>
      </div>
    </div>

    <!-- Schedule Management Tabs -->
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
        <!-- Sessions Tab -->
        <div v-if="activeTab === 'sessions'">
          <div class="sm:flex sm:items-center mb-6">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">Event Sessions</h3>
              <p class="mt-1 text-sm text-gray-500">Organize your event into sessions and time slots.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <button
                @click="showCreateSession = true"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Add Session
              </button>
            </div>
          </div>

          <div class="space-y-4">
            <div v-for="session in sessions" :key="session.id" class="bg-gray-50 rounded-lg p-6">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center">
                    <h4 class="text-md font-medium text-gray-900">{{ session.title }}</h4>
                    <span :class="[
                      'ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      session.type === 'keynote' ? 'bg-purple-100 text-purple-800' :
                      session.type === 'workshop' ? 'bg-blue-100 text-blue-800' :
                      session.type === 'panel' ? 'bg-green-100 text-green-800' :
                      'bg-gray-100 text-gray-800'
                    ]">
                      {{ session.type }}
                    </span>
                  </div>
                  <p class="mt-1 text-sm text-gray-600">{{ session.description }}</p>
                  <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                    <span>🕒 {{ session.start_time }} - {{ session.end_time }}</span>
                    <span>📍 {{ session.location }}</span>
                    <span>👥 {{ session.capacity }} capacity</span>
                  </div>
                  <div v-if="session.speakers.length" class="mt-2">
                    <span class="text-sm text-gray-500">Speakers: </span>
                    <span class="text-sm text-gray-900">{{ session.speakers.join(', ') }}</span>
                  </div>
                </div>
                <div class="flex space-x-2 ml-4">
                  <button 
                    @click="editSession(session)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteSession(session.id)"
                    class="text-red-600 hover:text-red-900 text-sm"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Speakers Tab -->
        <div v-if="activeTab === 'speakers'">
          <div class="sm:flex sm:items-center mb-6">
            <div class="sm:flex-auto">
              <h3 class="text-lg font-medium text-gray-900">Event Speakers</h3>
              <p class="mt-1 text-sm text-gray-500">Manage speakers and their information.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
              <button
                @click="showCreateSpeaker = true"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
              >
                Add Speaker
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="speaker in speakers" :key="speaker.id" class="bg-white border border-gray-200 rounded-lg p-6">
              <div class="flex items-center">
                <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                  <span class="text-lg font-medium text-gray-700">
                    {{ speaker.name.split(' ').map(n => n[0]).join('') }}
                  </span>
                </div>
                <div class="ml-4">
                  <h4 class="text-md font-medium text-gray-900">{{ speaker.name }}</h4>
                  <p class="text-sm text-gray-500">{{ speaker.title }}</p>
                </div>
              </div>
              <p class="mt-3 text-sm text-gray-600">{{ speaker.bio }}</p>
              <div class="mt-4 flex space-x-2">
                <button 
                  @click="editSpeaker(speaker)"
                  class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                  Edit
                </button>
                <button 
                  @click="deleteSpeaker(speaker.id)"
                  class="flex-1 text-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-50"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Agenda Tab -->
        <div v-if="activeTab === 'agenda'">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Event Agenda</h3>
            <p class="mt-1 text-sm text-gray-500">Complete schedule overview for attendees.</p>
          </div>

          <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h4 class="text-md font-medium text-gray-900">{{ formatDate(event.date) }}</h4>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div v-for="item in sortedAgenda" :key="item.id" class="flex items-start space-x-4 pb-4 border-b border-gray-100 last:border-b-0">
                  <div class="flex-shrink-0 w-20 text-sm font-medium text-gray-900">
                    {{ item.start_time }}
                  </div>
                  <div class="flex-1">
                    <h5 class="text-sm font-medium text-gray-900">{{ item.title }}</h5>
                    <p class="text-sm text-gray-500">{{ item.description }}</p>
                    <div class="mt-1 flex items-center space-x-2 text-xs text-gray-400">
                      <span>{{ item.location }}</span>
                      <span>•</span>
                      <span>{{ item.duration }} minutes</span>
                      <span v-if="item.speaker">• {{ item.speaker }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="exportAgenda"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Export Agenda
            </button>
            <button
              @click="publishAgenda"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
            >
              Publish Agenda
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const activeTab = ref('sessions')
const showCreateSession = ref(false)
const showCreateSpeaker = ref(false)

const tabs = [
  { name: 'sessions', label: 'Sessions' },
  { name: 'speakers', label: 'Speakers' },
  { name: 'agenda', label: 'Agenda' }
]

const event = ref({
  title: 'Summer Music Festival',
  date: '2025-07-15',
  venue: 'Central Park Amphitheater',
  status: 'published'
})

const sessions = ref([
  {
    id: 1,
    title: 'Opening Ceremony',
    description: 'Welcome and introduction to the festival',
    type: 'keynote',
    start_time: '10:00',
    end_time: '10:30',
    location: 'Main Stage',
    capacity: 5000,
    speakers: ['John Doe', 'Jane Smith']
  },
  {
    id: 2,
    title: 'Acoustic Workshop',
    description: 'Learn acoustic guitar techniques from the pros',
    type: 'workshop',
    start_time: '11:00',
    end_time: '12:00',
    location: 'Workshop Tent',
    capacity: 50,
    speakers: ['Bob Johnson']
  },
  {
    id: 3,
    title: 'Music Industry Panel',
    description: 'Discussion on the future of music industry',
    type: 'panel',
    start_time: '14:00',
    end_time: '15:30',
    location: 'Conference Hall',
    capacity: 200,
    speakers: ['Alice Wilson', 'Mike Brown', 'Sarah Davis']
  }
])

const speakers = ref([
  {
    id: 1,
    name: 'John Doe',
    title: 'Festival Director',
    bio: 'Experienced festival organizer with 15 years in the music industry.',
    email: 'john@example.com'
  },
  {
    id: 2,
    name: 'Jane Smith',
    title: 'Headlining Artist',
    bio: 'Grammy-winning artist and performer.',
    email: 'jane@example.com'
  },
  {
    id: 3,
    name: 'Bob Johnson',
    title: 'Guitar Instructor',
    bio: 'Professional musician and teacher with 20+ years experience.',
    email: 'bob@example.com'
  }
])

const sortedAgenda = computed(() => {
  return sessions.value
    .map(session => ({
      id: session.id,
      title: session.title,
      description: session.description,
      start_time: session.start_time,
      location: session.location,
      duration: calculateDuration(session.start_time, session.end_time),
      speaker: session.speakers.length > 0 ? session.speakers[0] : null
    }))
    .sort((a, b) => a.start_time.localeCompare(b.start_time))
})

const calculateDuration = (startTime: string, endTime: string) => {
  const start = new Date(`2000-01-01 ${startTime}`)
  const end = new Date(`2000-01-01 ${endTime}`)
  return Math.round((end.getTime() - start.getTime()) / 60000)
}

const editSession = (session: any) => {
  console.log('Editing session:', session.id)
  alert(`Editing session: ${session.title}\nThis will open the session editor.`)
  // TODO: Open edit session modal
}

const deleteSession = (sessionId: number) => {
  const session = sessions.value.find(s => s.id === sessionId)
  if (confirm(`Are you sure you want to delete session "${session?.title}"?\n\nThis action cannot be undone.`)) {
    const sessionIndex = sessions.value.findIndex(s => s.id === sessionId)
    if (sessionIndex !== -1) {
      const deletedSession = sessions.value[sessionIndex]
      sessions.value.splice(sessionIndex, 1)
      alert(`Session "${deletedSession.title}" has been deleted successfully!`)
    }
    console.log('Deleted session:', sessionId)
    // TODO: Delete session via API
  }
}

const editSpeaker = (speaker: any) => {
  console.log('Editing speaker:', speaker.id)
  alert(`Editing speaker: ${speaker.name}\nThis will open the speaker profile editor.`)
  // TODO: Open edit speaker modal
}

const deleteSpeaker = (speakerId: number) => {
  const speaker = speakers.value.find(s => s.id === speakerId)
  if (confirm(`Are you sure you want to remove speaker "${speaker?.name}"?\n\nThis action cannot be undone.`)) {
    const speakerIndex = speakers.value.findIndex(s => s.id === speakerId)
    if (speakerIndex !== -1) {
      const deletedSpeaker = speakers.value[speakerIndex]
      speakers.value.splice(speakerIndex, 1)
      alert(`Speaker "${deletedSpeaker.name}" has been removed successfully!`)
    }
    console.log('Deleted speaker:', speakerId)
    // TODO: Delete speaker via API
  }
}

const exportAgenda = () => {
  console.log('Exporting agenda...')
  // TODO: Generate and download agenda PDF
}

const publishAgenda = () => {
  console.log('Publishing agenda...')
  // TODO: Make agenda public for attendees
  alert('Agenda published successfully!')
}

const formatDate = (date: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  // TODO: Fetch event and schedule data from API
  console.log('Loading event schedule for event:', route.params.id)
})
</script>
