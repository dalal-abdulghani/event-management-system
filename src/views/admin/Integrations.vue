<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Integrations</h1>
      <p class="mt-2 text-sm text-gray-600">Connect with payment gateways, marketing tools, and other services.</p>
    </div>

    <!-- Integration Categories -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Payment Gateways -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Payment Gateways</h2>
          <p class="text-sm text-gray-500">Secure payment processing</p>
        </div>
        <div class="p-6 space-y-4">
          <div v-for="gateway in paymentGateways" :key="gateway.id" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                <span class="text-sm font-medium">{{ gateway.name[0] }}</span>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-900">{{ gateway.name }}</h3>
                <p class="text-xs text-gray-500">{{ gateway.description }}</p>
              </div>
            </div>
            <div class="flex items-center">
              <span :class="[
                'inline-flex px-2 py-1 text-xs font-semibold rounded-full mr-2',
                gateway.connected ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
              ]">
                {{ gateway.connected ? 'Connected' : 'Not Connected' }}
              </span>
              <button
                @click="toggleIntegration(gateway)"
                :class="[
                  'text-sm font-medium',
                  gateway.connected ? 'text-red-600 hover:text-red-900' : 'text-indigo-600 hover:text-indigo-900'
                ]"
              >
                {{ gateway.connected ? 'Disconnect' : 'Connect' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Marketing Tools -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Marketing Tools</h2>
          <p class="text-sm text-gray-500">Email and social media marketing</p>
        </div>
        <div class="p-6 space-y-4">
          <div v-for="tool in marketingTools" :key="tool.id" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                <span class="text-sm font-medium">{{ tool.name[0] }}</span>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-900">{{ tool.name }}</h3>
                <p class="text-xs text-gray-500">{{ tool.description }}</p>
              </div>
            </div>
            <div class="flex items-center">
              <span :class="[
                'inline-flex px-2 py-1 text-xs font-semibold rounded-full mr-2',
                tool.connected ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
              ]">
                {{ tool.connected ? 'Connected' : 'Not Connected' }}
              </span>
              <button
                @click="toggleIntegration(tool)"
                :class="[
                  'text-sm font-medium',
                  tool.connected ? 'text-red-600 hover:text-red-900' : 'text-indigo-600 hover:text-indigo-900'
                ]"
              >
                {{ tool.connected ? 'Disconnect' : 'Connect' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- CRM & ERP -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">CRM & ERP</h2>
          <p class="text-sm text-gray-500">Customer and business management</p>
        </div>
        <div class="p-6 space-y-4">
          <div v-for="system in crmSystems" :key="system.id" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                <span class="text-sm font-medium">{{ system.name[0] }}</span>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-900">{{ system.name }}</h3>
                <p class="text-xs text-gray-500">{{ system.description }}</p>
              </div>
            </div>
            <div class="flex items-center">
              <span :class="[
                'inline-flex px-2 py-1 text-xs font-semibold rounded-full mr-2',
                system.connected ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
              ]">
                {{ system.connected ? 'Connected' : 'Not Connected' }}
              </span>
              <button
                @click="toggleIntegration(system)"
                :class="[
                  'text-sm font-medium',
                  system.connected ? 'text-red-600 hover:text-red-900' : 'text-indigo-600 hover:text-indigo-900'
                ]"
              >
                {{ system.connected ? 'Disconnect' : 'Connect' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- API Configuration -->
    <div class="mt-8 bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">API Configuration</h2>
        <p class="text-sm text-gray-500">Manage API keys and webhook settings.</p>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <!-- API Keys -->
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">API Keys</h3>
            <div class="space-y-3">
              <div v-for="apiKey in apiKeys" :key="apiKey.id" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">{{ apiKey.name }}</h4>
                  <p class="text-xs text-gray-500">{{ apiKey.description }}</p>
                  <p class="text-xs text-gray-400 mt-1">Last used: {{ formatDate(apiKey.last_used) }}</p>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="regenerateApiKey(apiKey)"
                    class="text-sm text-indigo-600 hover:text-indigo-900"
                  >
                    Regenerate
                  </button>
                  <button
                    @click="deleteApiKey(apiKey.id)"
                    class="text-sm text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
            <button
              @click="showCreateApiKey = true"
              class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
            >
              Create API Key
            </button>
          </div>

          <!-- Webhooks -->
          <div>
            <h3 class="text-md font-medium text-gray-900 mb-4">Webhooks</h3>
            <div class="space-y-3">
              <div v-for="webhook in webhooks" :key="webhook.id" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">{{ webhook.name }}</h4>
                  <p class="text-xs text-gray-500">{{ webhook.url }}</p>
                  <div class="flex items-center mt-1">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      webhook.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ]">
                      {{ webhook.active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-xs text-gray-400 ml-2">{{ webhook.events.join(', ') }}</span>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="testWebhook(webhook)"
                    class="text-sm text-blue-600 hover:text-blue-900"
                  >
                    Test
                  </button>
                  <button
                    @click="editWebhook(webhook)"
                    class="text-sm text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteWebhook(webhook.id)"
                    class="text-sm text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
            <button
              @click="showCreateWebhook = true"
              class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
            >
              Create Webhook
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Integration Statistics -->
    <div class="mt-8 bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Integration Statistics</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ integrationStats.totalIntegrations }}</div>
            <div class="text-sm text-gray-500">Total Integrations</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ integrationStats.activeIntegrations }}</div>
            <div class="text-sm text-gray-500">Active Connections</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ integrationStats.apiCalls.toLocaleString() }}</div>
            <div class="text-sm text-gray-500">API Calls (30d)</div>
          </div>
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ integrationStats.webhookDeliveries.toLocaleString() }}</div>
            <div class="text-sm text-gray-500">Webhook Deliveries</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create API Key Modal -->
    <div v-if="showCreateApiKey" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Create API Key</h3>
          <form @submit.prevent="createApiKey">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  v-model="newApiKey.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  v-model="newApiKey.description"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                <div class="mt-2 space-y-2">
                  <div v-for="permission in availablePermissions" :key="permission" class="flex items-center">
                    <input
                      v-model="newApiKey.permissions"
                      :value="permission"
                      type="checkbox"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label class="ml-2 block text-sm text-gray-900">{{ permission }}</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                @click="showCreateApiKey = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
              >
                Create API Key
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

const showCreateApiKey = ref(false)
const showCreateWebhook = ref(false)

const paymentGateways = ref([
  { id: 1, name: 'Stripe', description: 'Credit card processing', connected: true },
  { id: 2, name: 'PayPal', description: 'PayPal payments', connected: false },
  { id: 3, name: 'Square', description: 'Point of sale integration', connected: false }
])

const marketingTools = ref([
  { id: 1, name: 'Mailchimp', description: 'Email marketing automation', connected: true },
  { id: 2, name: 'Facebook Ads', description: 'Social media advertising', connected: false },
  { id: 3, name: 'Google Analytics', description: 'Website analytics', connected: true }
])

const crmSystems = ref([
  { id: 1, name: 'Salesforce', description: 'Customer relationship management', connected: false },
  { id: 2, name: 'HubSpot', description: 'Inbound marketing and sales', connected: false },
  { id: 3, name: 'Zapier', description: 'Workflow automation', connected: true }
])

const apiKeys = ref([
  {
    id: 1,
    name: 'Mobile App API',
    description: 'API key for mobile application',
    last_used: '2025-01-10'
  },
  {
    id: 2,
    name: 'Third Party Integration',
    description: 'External service integration',
    last_used: '2025-01-08'
  }
])

const webhooks = ref([
  {
    id: 1,
    name: 'Payment Notifications',
    url: 'https://api.example.com/webhooks/payments',
    active: true,
    events: ['payment.success', 'payment.failed']
  },
  {
    id: 2,
    name: 'Event Updates',
    url: 'https://api.example.com/webhooks/events',
    active: false,
    events: ['event.created', 'event.updated']
  }
])

const integrationStats = ref({
  totalIntegrations: 12,
  activeIntegrations: 8,
  apiCalls: 45678,
  webhookDeliveries: 12345
})

const newApiKey = ref({
  name: '',
  description: '',
  permissions: []
})

const availablePermissions = [
  'read:events',
  'write:events',
  'read:tickets',
  'write:tickets',
  'read:users',
  'write:users'
]

const toggleIntegration = (integration: any) => {
  integration.connected = !integration.connected
  console.log(`${integration.name} ${integration.connected ? 'connected' : 'disconnected'}`)
  // TODO: Handle integration connection/disconnection
}

const regenerateApiKey = (apiKey: any) => {
  if (confirm('Are you sure you want to regenerate this API key? The old key will stop working immediately.')) {
    console.log('Regenerating API key:', apiKey.id)
    // TODO: Regenerate API key
  }
}

const deleteApiKey = (apiKeyId: number) => {
  const apiKey = apiKeys.value.find(key => key.id === apiKeyId)
  if (confirm(`Are you sure you want to delete API key "${apiKey?.name}"?\n\nThis action cannot be undone and may break integrations.`)) {
    const apiKeyIndex = apiKeys.value.findIndex(key => key.id === apiKeyId)
    if (apiKeyIndex !== -1) {
      const deletedApiKey = apiKeys.value[apiKeyIndex]
      apiKeys.value.splice(apiKeyIndex, 1)
      alert(`API key "${deletedApiKey.name}" has been deleted successfully!`)
    }
    console.log('Deleted API key:', apiKeyId)
    // TODO: Delete API key
  }
}

const testWebhook = (webhook: any) => {
  console.log('Testing webhook:', webhook.id)
  // TODO: Send test webhook
  alert('Test webhook sent successfully!')
}

const editWebhook = (webhook: any) => {
  console.log('Editing webhook:', webhook.id)
  alert(`Editing webhook: ${webhook.name}\nThis will open the webhook configuration editor.`)
  // TODO: Open webhook edit modal
}

const deleteWebhook = (webhookId: number) => {
  const webhook = webhooks.value.find(w => w.id === webhookId)
  if (confirm(`Are you sure you want to delete webhook "${webhook?.name}"?\n\nThis action cannot be undone and may break integrations.`)) {
    const webhookIndex = webhooks.value.findIndex(w => w.id === webhookId)
    if (webhookIndex !== -1) {
      const deletedWebhook = webhooks.value[webhookIndex]
      webhooks.value.splice(webhookIndex, 1)
      alert(`Webhook "${deletedWebhook.name}" has been deleted successfully!`)
    }
    console.log('Deleted webhook:', webhookId)
    // TODO: Delete webhook
  }
}

const createApiKey = () => {
  console.log('Creating API key:', newApiKey.value)
  // TODO: Create API key via API
  
  // Mock adding to list
  apiKeys.value.push({
    id: Date.now(),
    name: newApiKey.value.name,
    description: newApiKey.value.description,
    last_used: 'Never'
  })
  
  showCreateApiKey.value = false
  newApiKey.value = { name: '', description: '', permissions: [] }
}

const formatDate = (date: string) => {
  if (!date || date === 'Never') return date
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch integration data from API
  console.log('Loading integration data...')
})
</script>
