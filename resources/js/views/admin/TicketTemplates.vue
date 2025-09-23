<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Ticket Templates</h1>
      <p class="mt-2 text-sm text-gray-600">Design and manage ticket templates with QR codes and branding.</p>
    </div>

    <!-- Template Gallery -->
    <div class="bg-white shadow rounded-lg mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Template Gallery</h2>
            <p class="mt-1 text-sm text-gray-500">Choose from pre-designed templates or create your own.</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="showCreateTemplate = true"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              Create Template
            </button>
          </div>
        </div>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="template in templates" :key="template.id" class="border border-gray-200 rounded-lg overflow-hidden">
            <!-- Template Preview -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white relative">
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-lg font-bold">{{ template.event_name }}</h3>
                  <p class="text-sm opacity-90">{{ template.event_date }}</p>
                </div>
                <div class="text-right">
                  <div class="text-xs opacity-75">TICKET</div>
                  <div class="text-sm font-mono">#{{ template.sample_number }}</div>
                </div>
              </div>
              
              <div class="flex justify-between items-end">
                <div>
                  <div class="text-xs opacity-75">{{ template.ticket_type }}</div>
                  <div class="text-lg font-bold">${{ template.price }}</div>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded flex items-center justify-center">
                  <div class="text-xs">QR</div>
                </div>
              </div>
              
              <div class="absolute bottom-2 left-6 text-xs opacity-75">
                {{ template.venue }}
              </div>
            </div>

            <!-- Template Info -->
            <div class="p-4">
              <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-medium text-gray-900">{{ template.name }}</h4>
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  template.status === 'active' ? 'bg-green-100 text-green-800' :
                  template.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-gray-100 text-gray-800'
                ]">
                  {{ template.status }}
                </span>
              </div>
              <p class="text-xs text-gray-500 mb-3">{{ template.description }}</p>
              
              <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                <span>Used by {{ template.events_count }} events</span>
                <span>{{ template.tickets_generated.toLocaleString() }} tickets</span>
              </div>

              <div class="flex space-x-2">
                <button 
                  @click="editTemplate(template)"
                  class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50"
                >
                  Edit
                </button>
                <button 
                  @click="duplicateTemplate(template)"
                  class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50"
                >
                  Duplicate
                </button>
                <button 
                  @click="deleteTemplate(template.id)"
                  class="px-3 py-2 border border-red-300 rounded-md text-xs font-medium text-red-700 hover:bg-red-50"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Template Settings -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Template Settings</h2>
        <p class="mt-1 text-sm text-gray-500">Configure global template settings and branding options.</p>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <!-- Branding Settings -->
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Branding</h3>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Company Logo</label>
                <div class="mt-1 flex items-center">
                  <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-xs text-gray-500">Logo</span>
                  </div>
                  <button class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Upload Logo
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Primary Color</label>
                <div class="mt-1 flex items-center space-x-2">
                  <input
                    v-model="brandingSettings.primaryColor"
                    type="color"
                    class="h-8 w-16 border border-gray-300 rounded"
                  />
                  <input
                    v-model="brandingSettings.primaryColor"
                    type="text"
                    class="block w-24 rounded-md border-gray-300 text-sm"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Secondary Color</label>
                <div class="mt-1 flex items-center space-x-2">
                  <input
                    v-model="brandingSettings.secondaryColor"
                    type="color"
                    class="h-8 w-16 border border-gray-300 rounded"
                  />
                  <input
                    v-model="brandingSettings.secondaryColor"
                    type="text"
                    class="block w-24 rounded-md border-gray-300 text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="font-family" class="block text-sm font-medium text-gray-700">Font Family</label>
                <select
                  v-model="brandingSettings.fontFamily"
                  id="font-family"
                  class="mt-1 block w-full rounded-md border-gray-300 text-sm"
                >
                  <option value="Arial">Arial</option>
                  <option value="Helvetica">Helvetica</option>
                  <option value="Times New Roman">Times New Roman</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Roboto">Roboto</option>
                </select>
              </div>
            </div>
          </div>

          <!-- QR Code Settings -->
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">QR Code Configuration</h3>
            <div class="space-y-4">
              <div>
                <label for="qr-size" class="block text-sm font-medium text-gray-700">QR Code Size</label>
                <select
                  v-model="qrSettings.size"
                  id="qr-size"
                  class="mt-1 block w-full rounded-md border-gray-300 text-sm"
                >
                  <option value="small">Small (64x64)</option>
                  <option value="medium">Medium (128x128)</option>
                  <option value="large">Large (256x256)</option>
                </select>
              </div>

              <div>
                <label for="qr-position" class="block text-sm font-medium text-gray-700">QR Code Position</label>
                <select
                  v-model="qrSettings.position"
                  id="qr-position"
                  class="mt-1 block w-full rounded-md border-gray-300 text-sm"
                >
                  <option value="top-right">Top Right</option>
                  <option value="top-left">Top Left</option>
                  <option value="bottom-right">Bottom Right</option>
                  <option value="bottom-left">Bottom Left</option>
                  <option value="center">Center</option>
                </select>
              </div>

              <div class="flex items-center">
                <input
                  v-model="qrSettings.includeLogo"
                  id="qr-logo"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="qr-logo" class="ml-2 block text-sm text-gray-900">
                  Include logo in QR code
                </label>
              </div>

              <div class="flex items-center">
                <input
                  v-model="qrSettings.highErrorCorrection"
                  id="qr-error"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="qr-error" class="ml-2 block text-sm text-gray-900">
                  High error correction
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
          <button
            @click="resetSettings"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Reset to Defaults
          </button>
          <button
            @click="saveSettings"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Save Settings
          </button>
        </div>
      </div>
    </div>

    <!-- Create Template Modal -->
    <div v-if="showCreateTemplate" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Template</h3>
          <form @submit.prevent="createTemplate">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Template Name</label>
                <input
                  v-model="newTemplate.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  v-model="newTemplate.description"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Template Type</label>
                <select
                  v-model="newTemplate.type"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="standard">Standard</option>
                  <option value="vip">VIP</option>
                  <option value="backstage">Backstage Pass</option>
                  <option value="custom">Custom</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                @click="showCreateTemplate = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
              >
                Create Template
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

const showCreateTemplate = ref(false)

const templates = ref([
  {
    id: 1,
    name: 'Standard Event Ticket',
    description: 'Clean and professional design for general events',
    event_name: 'Sample Event',
    event_date: 'July 15, 2025',
    ticket_type: 'General Admission',
    price: 50,
    sample_number: 'T001234',
    venue: 'Sample Venue',
    status: 'active',
    events_count: 12,
    tickets_generated: 4567
  },
  {
    id: 2,
    name: 'VIP Experience',
    description: 'Premium design for VIP and special access tickets',
    event_name: 'VIP Event',
    event_date: 'August 20, 2025',
    ticket_type: 'VIP Access',
    price: 150,
    sample_number: 'V001234',
    venue: 'Premium Venue',
    status: 'active',
    events_count: 5,
    tickets_generated: 890
  },
  {
    id: 3,
    name: 'Festival Pass',
    description: 'Colorful design perfect for music festivals',
    event_name: 'Music Festival',
    event_date: 'September 10, 2025',
    ticket_type: 'Festival Pass',
    price: 75,
    sample_number: 'F001234',
    venue: 'Festival Grounds',
    status: 'draft',
    events_count: 0,
    tickets_generated: 0
  }
])

const brandingSettings = ref({
  primaryColor: '#4F46E5',
  secondaryColor: '#7C3AED',
  fontFamily: 'Arial'
})

const qrSettings = ref({
  size: 'medium',
  position: 'bottom-right',
  includeLogo: true,
  highErrorCorrection: true
})

const newTemplate = ref({
  name: '',
  description: '',
  type: 'standard'
})

const editTemplate = (template: any) => {
  console.log('Editing template:', template.id)
  alert(`Editing template: ${template.name}\nThis will open the template editor with design tools.`)
  // TODO: Open template editor with design functionality
}

const duplicateTemplate = (template: any) => {
  console.log('Duplicating template:', template.id)
  // TODO: Create a copy of the template
}

const deleteTemplate = (templateId: number) => {
  const template = templates.value.find(t => t.id === templateId)
  if (confirm(`Are you sure you want to delete template "${template?.name}"?\n\nThis action cannot be undone.`)) {
    const templateIndex = templates.value.findIndex(t => t.id === templateId)
    if (templateIndex !== -1) {
      const deletedTemplate = templates.value[templateIndex]
      templates.value.splice(templateIndex, 1)
      alert(`Template "${deletedTemplate.name}" has been deleted successfully!`)
    }
    console.log('Deleted template:', templateId)
    // TODO: Delete template via API
  }
}

const createTemplate = () => {
  console.log('Creating template:', newTemplate.value)
  // TODO: Create template via API
  
  // Mock adding to list
  templates.value.push({
    id: Date.now(),
    name: newTemplate.value.name,
    description: newTemplate.value.description,
    event_name: 'New Event',
    event_date: 'TBD',
    ticket_type: newTemplate.value.type,
    price: 0,
    sample_number: 'N001234',
    venue: 'TBD',
    status: 'draft',
    events_count: 0,
    tickets_generated: 0
  })
  
  showCreateTemplate.value = false
  newTemplate.value = { name: '', description: '', type: 'standard' }
}

const saveSettings = () => {
  console.log('Saving template settings:', { brandingSettings: brandingSettings.value, qrSettings: qrSettings.value })
  // TODO: Save settings via API
  alert('Template settings saved successfully!')
}

const resetSettings = () => {
  if (confirm('Are you sure you want to reset all settings to defaults?')) {
    brandingSettings.value = {
      primaryColor: '#4F46E5',
      secondaryColor: '#7C3AED',
      fontFamily: 'Arial'
    }
    qrSettings.value = {
      size: 'medium',
      position: 'bottom-right',
      includeLogo: true,
      highErrorCorrection: true
    }
  }
}

onMounted(() => {
  // TODO: Fetch templates and settings from API
  console.log('Loading ticket templates...')
})
</script>
