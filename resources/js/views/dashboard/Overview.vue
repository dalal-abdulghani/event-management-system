<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Overview</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900">Total Events</h3>
        <p class="text-3xl font-bold text-blue-600">12</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900">Total Tickets Sold</h3>
        <p class="text-3xl font-bold text-green-600">456</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900">Revenue</h3>
        <p class="text-3xl font-bold text-purple-600">$12,345</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'

onMounted(() => {
  console.log('Dashboard overview loaded')
})
</script>
