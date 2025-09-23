<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
})

const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const errors = ref<Record<string, string[]>>({})
const isLoading = ref(false)

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
    await authStore.register({
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    })
    toast.success('Account created successfully!')
    router.push('/admin')
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      toast.error(error.response?.data?.message || 'Registration failed')
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
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <RouterLink to="/login" class="font-medium text-primary-600 hover:text-primary-500">
            sign in to your existing account
          </RouterLink>
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Full name
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              autocomplete="name"
              required
              class="mt-1 input-field"
              :class="{ 'input-error': getFieldError('name') }"
              placeholder="Enter your full name"
            />
            <p v-if="getFieldError('name')" class="mt-1 text-sm text-red-600">
              {{ getFieldError('name') }}
            </p>
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <input
              id="email"
              v-model="form.email"
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
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
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
                placeholder="Create a password"
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
              Confirm password
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
                placeholder="Confirm your password"
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

        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="terms"
              v-model="form.terms"
              type="checkbox"
              required
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            />
          </div>
          <div class="ml-2 text-sm">
            <label for="terms" class="text-gray-900">
              I agree to the
              <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a>
              and
              <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
            </label>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="isLoading || !form.terms"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading" class="loading-spinner mr-2"></span>
            {{ isLoading ? 'Creating account...' : 'Create account' }}
          </button>
        </div>

        <div class="text-center">
          <p class="text-xs text-gray-500">
            Register new admin user
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

