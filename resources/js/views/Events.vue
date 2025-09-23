<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Events</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="event in events" :key="event.id" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ event.title }}</h3>
          <p class="text-gray-600 mb-4">{{ event.description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">{{ event.date }}</span>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
              {{ event.status }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const events = ref([
  {
    id: 1,
    title: 'Sample Event',
    description: 'This is a sample event description.',
    date: '2025-01-15',
    status: 'active'
  }
])

onMounted(() => {
  // Load events from API
  console.log('Events page loaded')
})
</script>
