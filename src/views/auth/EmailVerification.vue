<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'
import { EnvelopeIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const isLoading = ref(false)
const isResending = ref(false)

onMounted(() => {
  // If user is already verified, redirect to dashboard
  if (authStore.user?.email_verified_at) {
    router.push('/dashboard')
  }
})

const resendVerification = async () => {
  if (isResending.value) return

  isResending.value = true

  try {
    await authStore.resendVerification()
    toast.success('Verification email sent! Check your inbox.')
  } catch (error: any) {
    toast.error(error.response?.data?.message || 'Failed to send verification email')
  } finally {
    isResending.value = false
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-yellow-100">
          <EnvelopeIcon class="h-8 w-8 text-yellow-600" />
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Verify your email
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          We've sent a verification link to
          <span class="font-medium text-gray-900">{{ authStore.user?.email }}</span>
        </p>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <div class="text-center">
          <CheckCircleIcon class="mx-auto h-12 w-12 text-green-400 mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">Check your email</h3>
          <p class="text-sm text-gray-600 mb-6">
            Click the verification link in the email we sent you to activate your account.
          </p>
          
          <div class="space-y-4">
            <button
              @click="resendVerification"
              :disabled="isResending"
              class="w-full btn-primary"
            >
              <span v-if="isResending" class="loading-spinner mr-2"></span>
              {{ isResending ? 'Sending...' : 'Resend verification email' }}
            </button>
            
            <button
              @click="handleLogout"
              class="w-full btn-secondary"
            >
              Sign out
            </button>
          </div>
        </div>
      </div>

      <div class="text-center">
        <p class="text-sm text-gray-600">
          Didn't receive the email? Check your spam folder or
          <button
            @click="resendVerification"
            :disabled="isResending"
            class="font-medium text-primary-600 hover:text-primary-500"
          >
            try again
          </button>
        </p>
      </div>

      <div class="text-center">
        <RouterLink to="/" class="text-sm text-gray-600 hover:text-gray-900">
          ← Back to home
        </RouterLink>
      </div>
    </div>
  </div>
</template>

