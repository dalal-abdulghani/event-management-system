<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Demo credentials: admin@eventmanager.com / password
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            {{ isLoading ? 'Signing in...' : 'Sign in' }}
          </button>
        </div>

        <div v-if="errorMessage" class="text-red-600 text-sm text-center">
          {{ errorMessage }}
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: 'admin@eventmanager.com',
  password: 'password'
})

const isLoading = ref(false)
const errorMessage = ref('')

const handleSubmit = async () => {
  if (isLoading.value) return

  errorMessage.value = ''
  isLoading.value = true

  try {
    console.log('=== Login Process ===')
    console.log('Attempting login with:', form.value.email)
    const result = await authStore.login(form.value.email, form.value.password)
    console.log('Login result:', result)
    console.log('Auth store status after login:', {
      isAuthenticated: authStore.isAuthenticated,
      userId: authStore.user?.id
    })
    console.log('Attempting to navigate to dashboard')
    await router.push('/dashboard')
    console.log('Navigation to dashboard successful')
  } catch (error: any) {
    console.error('Login error:', error)
    errorMessage.value = error.message || 'Login failed'
  } finally {
    isLoading.value = false
  }
}
</script>