<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Data Management</h1>
      <p class="mt-2 text-sm text-gray-600">Manage application data and persistence settings.</p>
    </div>

    <!-- Data Statistics -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Data Statistics</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div v-for="(count, key) in dataStats" :key="key" class="bg-gray-50 p-4 rounded-lg">
          <div class="text-sm font-medium text-gray-500 capitalize">{{ key.replace(/([A-Z])/g, ' $1').trim() }}</div>
          <div class="text-2xl font-bold text-gray-900">{{ count }}</div>
        </div>
      </div>
    </div>

    <!-- Data Actions -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Data Actions</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Export Data -->
        <div class="border border-gray-200 rounded-lg p-4">
          <h3 class="text-sm font-medium text-gray-900 mb-2">Export Data</h3>
          <p class="text-sm text-gray-500 mb-3">Download all application data as JSON file for backup.</p>
          <button
            @click="exportData"
            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
          >
            Export Data
          </button>
        </div>

        <!-- Import Data -->
        <div class="border border-gray-200 rounded-lg p-4">
          <h3 class="text-sm font-medium text-gray-900 mb-2">Import Data</h3>
          <p class="text-sm text-gray-500 mb-3">Upload and restore data from a JSON backup file.</p>
          <input
            type="file"
            ref="fileInput"
            accept=".json"
            @change="handleFileImport"
            class="hidden"
          />
          <button
            @click="$refs.fileInput.click()"
            class="w-full bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700"
          >
            Import Data
          </button>
        </div>

        <!-- Clear Data -->
        <div class="border border-gray-200 rounded-lg p-4">
          <h3 class="text-sm font-medium text-gray-900 mb-2">Clear All Data</h3>
          <p class="text-sm text-gray-500 mb-3">Remove all saved data and reset to defaults.</p>
          <button
            @click="clearAllData"
            class="w-full bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700"
          >
            Clear Data
          </button>
        </div>
      </div>
    </div>

    <!-- Data Status -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Data Persistence Status</h2>
      <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>
              <h3 class="text-sm font-medium text-green-800">Data Persistence Active</h3>
              <p class="text-sm text-green-600">All create, edit, and delete operations are saved to localStorage.</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div>
              <h3 class="text-sm font-medium text-blue-800">Last Updated</h3>
              <p class="text-sm text-blue-600">{{ lastUpdated }}</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
              <h3 class="text-sm font-medium text-yellow-800">Production Note</h3>
              <p class="text-sm text-yellow-600">This demo uses localStorage. In production, data would be saved to a real database.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import dataPersistence from '@/services/dataPersistence'

const dataStats = ref<{ [key: string]: number }>({})
const lastUpdated = ref('')

const loadDataStats = () => {
  dataStats.value = dataPersistence.getDataStats()
  const allData = dataPersistence.loadAllData()
  lastUpdated.value = allData.lastUpdated ? new Date(allData.lastUpdated).toLocaleString() : 'Never'
}

const exportData = () => {
  const data = dataPersistence.exportData()
  const blob = new Blob([data], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `event-management-backup-${new Date().toISOString().split('T')[0]}.json`
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
  
  alert('Data exported successfully!')
}

const handleFileImport = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      try {
        const data = e.target?.result as string
        const success = dataPersistence.importData(data)
        
        if (success) {
          alert('Data imported successfully!')
          loadDataStats()
          // Refresh the page to show updated data
          window.location.reload()
        } else {
          alert('Failed to import data. Please check the file format.')
        }
      } catch (error) {
        alert('Error reading file. Please try again.')
      }
    }
    reader.readAsText(file)
  }
}

const clearAllData = () => {
  if (confirm('Are you sure you want to clear all data?\n\nThis action cannot be undone and will remove all saved information.')) {
    dataPersistence.clearAllData()
    alert('All data has been cleared successfully!')
    loadDataStats()
    // Refresh the page to show default data
    window.location.reload()
  }
}

onMounted(() => {
  loadDataStats()
})
</script>
