<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const form = ref({
  token: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const isLoading = ref(false)
const errors = ref<Record<string, string[]>>({})

onMounted(() => {
  form.value.token = route.query.token as string || ''
  form.value.email = route.query.email as string || ''
  
  if (!form.value.token || !form.value.email) {
    toast.error('Invalid reset link')
    router.push('/forgot-password')
  }
})

const togglePasswordVisibility = (field: 'password' | 'password_confirmation') => {
  if (field === 'password') {
    showPassword.value = !showPassword.value
  } else {
    showPasswordConfirmation.value = !showPasswordConfirmation.value
  }
}

const handleSubmit = async () => {
  if (isLoading.value) return

  errors.value = {}
  isLoading.value = true

  try {
    await authStore.resetPassword({
      token: form.value.token,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    })
    toast.success('Password reset successfully!')
    router.push('/login')
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      toast.error(error.response?.data?.message || 'Failed to reset password')
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
          Reset your password
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Enter your new password below
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              readonly
              class="mt-1 input-field bg-gray-50"
            />
          </div>
          
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              New password
            </label>
            <div class="mt-1 relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="new-password"
                required
                class="input-field pr-10"
                :class="{ 'input-error': getFieldError('password') }"
                placeholder="Enter new password"
              />
              <button
                type="button"
                @click="togglePasswordVisibility('password')"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <EyeIcon v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
            <p v-if="getFieldError('password')" class="mt-1 text-sm text-red-600">
              {{ getFieldError('password') }}
            </p>
          </div>
          
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirm new password
            </label>
            <div class="mt-1 relative">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                autocomplete="new-password"
                required
                class="input-field pr-10"
                :class="{ 'input-error': getFieldError('password_confirmation') }"
                placeholder="Confirm new password"
              />
              <button
                type="button"
                @click="togglePasswordVisibility('password_confirmation')"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <EyeIcon v-if="!showPasswordConfirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
            <p v-if="getFieldError('password_confirmation')" class="mt-1 text-sm text-red-600">
              {{ getFieldError('password_confirmation') }}
            </p>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading" class="loading-spinner mr-2"></span>
            {{ isLoading ? 'Resetting...' : 'Reset password' }}
          </button>
        </div>

        <div class="text-center">
          <RouterLink to="/login" class="text-sm text-primary-600 hover:text-primary-500">
            Remember your password? Sign in
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>

