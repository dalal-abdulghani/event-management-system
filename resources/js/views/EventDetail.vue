<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Event Detail</h1>
    
    <div v-if="event" class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ event.title }}</h2>
      <p class="text-gray-600 mb-4">{{ event.description }}</p>
      <div class="flex justify-between items-center">
        <span class="text-sm text-gray-500">{{ event.date }}</span>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
          {{ event.status }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const event = ref(null)

onMounted(() => {
  const eventId = route.params.id
  // Load event details from API
  console.log('Event detail page loaded for event:', eventId)
})
</script>
