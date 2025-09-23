<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Discount Codes</h1>
      <p class="mt-2 text-sm text-gray-600">Create and manage promotional discount codes and coupons.</p>
    </div>

    <!-- Discount Statistics -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Active Codes</dt>
                <dd class="text-lg font-medium text-gray-900">{{ discountStats.activeCodes }}</dd>
                <dd class="text-xs text-green-600">{{ discountStats.usedToday }} used today</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Savings</dt>
                <dd class="text-lg font-medium text-gray-900">${{ discountStats.totalSavings.toLocaleString() }}</dd>
                <dd class="text-xs text-blue-600">Customer savings</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Usage Rate</dt>
                <dd class="text-lg font-medium text-gray-900">{{ discountStats.usageRate }}%</dd>
                <dd class="text-xs text-yellow-600">{{ discountStats.totalUses }} total uses</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Avg Discount</dt>
                <dd class="text-lg font-medium text-gray-900">{{ discountStats.avgDiscount }}%</dd>
                <dd class="text-xs text-purple-600">Per transaction</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Discount Codes Table -->
    <div class="mt-8 bg-white shadow rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h2 class="text-lg font-medium text-gray-900">Discount Codes</h2>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="showCreateDiscount = true"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              Create Discount Code
            </button>
          </div>
        </div>
      </div>

      <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Until</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="discount in discountCodes" :key="discount.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 font-mono">{{ discount.code }}</div>
                <div class="text-sm text-gray-500">{{ discount.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  discount.type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'
                ]">
                  {{ discount.type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ discount.type === 'percentage' ? discount.value + '%' : '$' + discount.value }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ discount.used_count }} / {{ discount.max_uses || '∞' }}</div>
                <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                  <div 
                    class="bg-indigo-600 h-1 rounded-full" 
                    :style="{ width: discount.max_uses ? `${(discount.used_count / discount.max_uses) * 100}%` : '0%' }"
                  ></div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(discount.expires_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  discount.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                  {{ discount.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="editDiscount(discount)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button 
                    @click="toggleDiscount(discount)"
                    :class="discount.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                  >
                    {{ discount.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button 
                    @click="deleteDiscount(discount.id)"
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

    <!-- Edit Discount Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Discount Code</h3>
          <form @submit.prevent="updateDiscount">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Code</label>
                <input
                  v-model="editForm.code"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select
                  v-model="editForm.type"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="percentage">Percentage</option>
                  <option value="fixed">Fixed Amount</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Value</label>
                <input
                  v-model="editForm.value"
                  type="number"
                  required
                  min="0"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Max Uses</label>
                <input
                  v-model="editForm.max_uses"
                  type="number"
                  min="1"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Expires At</label>
                <input
                  v-model="editForm.expires_at"
                  type="date"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div class="flex items-center">
                <input
                  v-model="editForm.is_active"
                  id="is_active"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                  Active
                </label>
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="closeEditModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
              >
                Update Discount
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

const showCreateDiscount = ref(false)
const showEditModal = ref(false)
const editingDiscount = ref<any>(null)
const editForm = ref({
  code: '',
  type: '',
  value: 0,
  max_uses: null,
  expires_at: '',
  is_active: true
})

const discountStats = ref({
  activeCodes: 12,
  usedToday: 34,
  totalSavings: 15670,
  usageRate: 68.5,
  totalUses: 456,
  avgDiscount: 15.2
})

const discountCodes = ref([
  {
    id: 1,
    code: 'SUMMER20',
    description: 'Summer festival 20% discount',
    type: 'percentage',
    value: 20,
    used_count: 45,
    max_uses: 100,
    expires_at: '2025-07-01',
    is_active: true
  },
  {
    id: 2,
    code: 'EARLYBIRD',
    description: 'Early bird special $15 off',
    type: 'fixed',
    value: 15,
    used_count: 23,
    max_uses: 50,
    expires_at: '2025-06-01',
    is_active: true
  },
  {
    id: 3,
    code: 'VIP50',
    description: 'VIP upgrade 50% discount',
    type: 'percentage',
    value: 50,
    used_count: 8,
    max_uses: 20,
    expires_at: '2025-12-31',
    is_active: true
  },
  {
    id: 4,
    code: 'STUDENT10',
    description: 'Student discount',
    type: 'percentage',
    value: 10,
    used_count: 156,
    max_uses: null,
    expires_at: '2025-12-31',
    is_active: true
  }
])

const editDiscount = (discount: any) => {
  editingDiscount.value = discount
  editForm.value = {
    code: discount.code,
    type: discount.type,
    value: discount.value,
    max_uses: discount.max_uses,
    expires_at: discount.expires_at,
    is_active: discount.is_active
  }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingDiscount.value = null
  editForm.value = {
    code: '',
    type: '',
    value: 0,
    max_uses: null,
    expires_at: '',
    is_active: true
  }
}

const updateDiscount = () => {
  if (editingDiscount.value) {
    const discountIndex = discounts.value.findIndex(d => d.id === editingDiscount.value.id)
    if (discountIndex !== -1) {
      discounts.value[discountIndex] = {
        ...discounts.value[discountIndex],
        ...editForm.value
      }
    }
    alert('Discount updated successfully!')
    closeEditModal()
  }
}

const toggleDiscount = (discount: any) => {
  discount.is_active = !discount.is_active
  console.log(`Discount ${discount.code} ${discount.is_active ? 'activated' : 'deactivated'}`)
  // TODO: Update via API
}

const deleteDiscount = (discountId: number) => {
  const discount = discounts.value.find(d => d.id === discountId)
  if (confirm(`Are you sure you want to delete discount code "${discount?.code}"?\n\nThis action cannot be undone.`)) {
    const discountIndex = discounts.value.findIndex(d => d.id === discountId)
    if (discountIndex !== -1) {
      const deletedDiscount = discounts.value[discountIndex]
      discounts.value.splice(discountIndex, 1)
      alert(`Discount code "${deletedDiscount.code}" has been deleted successfully!`)
    }
    console.log('Deleted discount:', discountId)
    // TODO: Delete via API
  }
}

const formatDate = (date: string) => {
  if (!date) return 'No expiry'
  return new Date(date).toLocaleDateString()
}

onMounted(() => {
  // TODO: Fetch discount codes from API
  console.log('Loading discount codes...')
})
</script>
