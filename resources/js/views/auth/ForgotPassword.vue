<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const toast = useToast()

const email = ref('')
const isLoading = ref(false)
const isSuccess = ref(false)
const errors = ref<Record<string, string[]>>({})

const handleSubmit = async () => {
  if (isLoading.value) return

  errors.value = {}
  isLoading.value = true

  try {
    await authStore.forgotPassword(email.value)
    isSuccess.value = true
    toast.success('Password reset link sent to your email!')
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      toast.error(error.response?.data?.message || 'Failed to send reset link')
    }
  } finally {
    isLoading.value = false
  }
}

const getFieldError = (field: string) => {
  return errors.value[field]?.[0] || ''
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary-600">
          <span class="text-xl font-bold text-white">E</span>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Forgot your password?
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Enter your email address and we'll send you a link to reset your password.
        </p>
      </div>

      <div v-if="isSuccess" class="rounded-md bg-green-50 p-4">
        <div class="text-sm text-green-700">
          <p class="font-medium">Password reset link sent!</p>
          <p class="mt-1">Check your email for instructions to reset your password.</p>
        </div>
      </div>

      <form v-else class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">
            Email address
          </label>
          <input
            id="email"
            v-model="email"
            type="email"
            autocomplete="email"
            required
            class="mt-1 input-field"
            :class="{ 'input-error': getFieldError('email') }"
            placeholder="Enter your email"
          />
          <p v-if="getFieldError('email')" class="mt-1 text-sm text-red-600">
            {{ getFieldError('email') }}
          </p>
        </div>

        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading" class="loading-spinner mr-2"></span>
            {{ isLoading ? 'Sending...' : 'Send reset link' }}
          </button>
        </div>

        <div class="text-center space-y-2">
          <RouterLink to="/login" class="text-sm text-primary-600 hover:text-primary-500">
            Remember your password? Sign in
          </RouterLink>
          <br>
          <RouterLink to="/" class="text-sm text-gray-600 hover:text-gray-900">
            ← Back to home
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>

