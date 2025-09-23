<template>
  <div>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">System Settings</h1>
        <p class="mt-2 text-sm text-gray-700">
          Configure global platform settings.
        </p>
      </div>
    </div>

    <div class="mt-8 space-y-8">
      <!-- General Settings -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
          <form @submit.prevent="saveGeneralSettings">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="site-name" class="block text-sm font-medium text-gray-700">Site Name</label>
                <input
                  v-model="generalSettings.siteName"
                  type="text"
                  id="site-name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="site-email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input
                  v-model="generalSettings.contactEmail"
                  type="email"
                  id="site-email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div class="sm:col-span-2">
                <label for="site-description" class="block text-sm font-medium text-gray-700">Site Description</label>
                <textarea
                  v-model="generalSettings.siteDescription"
                  id="site-description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>
            </div>
            <div class="mt-6">
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Save General Settings
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Payment Settings -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Settings</h3>
          <form @submit.prevent="savePaymentSettings">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="stripe-key" class="block text-sm font-medium text-gray-700">Stripe Publishable Key</label>
                <input
                  v-model="paymentSettings.stripePublishableKey"
                  type="text"
                  id="stripe-key"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="commission-rate" class="block text-sm font-medium text-gray-700">Commission Rate (%)</label>
                <input
                  v-model="paymentSettings.commissionRate"
                  type="number"
                  id="commission-rate"
                  min="0"
                  max="100"
                  step="0.1"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="min-payout" class="block text-sm font-medium text-gray-700">Minimum Payout ($)</label>
                <input
                  v-model="paymentSettings.minimumPayout"
                  type="number"
                  id="min-payout"
                  min="0"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="payout-schedule" class="block text-sm font-medium text-gray-700">Payout Schedule</label>
                <select
                  v-model="paymentSettings.payoutSchedule"
                  id="payout-schedule"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                  <option value="manual">Manual</option>
                </select>
              </div>
            </div>
            <div class="mt-6">
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Save Payment Settings
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Email Settings -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Email Settings</h3>
          <form @submit.prevent="saveEmailSettings">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="smtp-host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                <input
                  v-model="emailSettings.smtpHost"
                  type="text"
                  id="smtp-host"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="smtp-port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                <input
                  v-model="emailSettings.smtpPort"
                  type="number"
                  id="smtp-port"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="smtp-username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                <input
                  v-model="emailSettings.smtpUsername"
                  type="text"
                  id="smtp-username"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label for="from-email" class="block text-sm font-medium text-gray-700">From Email</label>
                <input
                  v-model="emailSettings.fromEmail"
                  type="email"
                  id="from-email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
            </div>
            <div class="mt-6">
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Save Email Settings
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Settings -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900">Require Email Verification</h4>
                <p class="text-sm text-gray-500">Users must verify their email before accessing the platform</p>
              </div>
              <button
                @click="securitySettings.requireEmailVerification = !securitySettings.requireEmailVerification"
                :class="securitySettings.requireEmailVerification ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <span
                  :class="securitySettings.requireEmailVerification ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                ></span>
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900">Two-Factor Authentication</h4>
                <p class="text-sm text-gray-500">Require 2FA for admin accounts</p>
              </div>
              <button
                @click="securitySettings.require2FA = !securitySettings.require2FA"
                :class="securitySettings.require2FA ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <span
                  :class="securitySettings.require2FA ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                ></span>
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900">Event Approval Required</h4>
                <p class="text-sm text-gray-500">New events require admin approval before going live</p>
              </div>
              <button
                @click="securitySettings.requireEventApproval = !securitySettings.requireEventApproval"
                :class="securitySettings.requireEventApproval ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <span
                  :class="securitySettings.requireEventApproval ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                ></span>
              </button>
            </div>
          </div>
          <div class="mt-6">
            <button
              @click="saveSecuritySettings"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
            >
              Save Security Settings
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const generalSettings = ref({
  siteName: 'EventHub',
  contactEmail: 'admin@eventhub.com',
  siteDescription: 'Your premier event management platform'
})

const paymentSettings = ref({
  stripePublishableKey: '',
  commissionRate: 5.0,
  minimumPayout: 50,
  payoutSchedule: 'weekly'
})

const emailSettings = ref({
  smtpHost: 'smtp.mailgun.org',
  smtpPort: 587,
  smtpUsername: '',
  fromEmail: 'noreply@eventhub.com'
})

const securitySettings = ref({
  requireEmailVerification: true,
  require2FA: false,
  requireEventApproval: true
})

const saveGeneralSettings = async () => {
  console.log('Saving general settings:', generalSettings.value)
  // TODO: Submit to API
  alert('General settings saved!')
}

const savePaymentSettings = async () => {
  console.log('Saving payment settings:', paymentSettings.value)
  // TODO: Submit to API
  alert('Payment settings saved!')
}

const saveEmailSettings = async () => {
  console.log('Saving email settings:', emailSettings.value)
  // TODO: Submit to API
  alert('Email settings saved!')
}

const saveSecuritySettings = async () => {
  console.log('Saving security settings:', securitySettings.value)
  // TODO: Submit to API
  alert('Security settings saved!')
}

onMounted(() => {
  // TODO: Fetch current settings from API
  console.log('Loading system settings...')
})
</script>
