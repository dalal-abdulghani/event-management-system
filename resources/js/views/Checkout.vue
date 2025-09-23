<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
      <p class="text-gray-600">Checkout page - Coming soon</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

onMounted(() => {
  const eventId = route.params.eventId
  console.log('Checkout page loaded for event:', eventId)
})
</script>
