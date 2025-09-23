import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User } from '@/types'
import apiService, { api } from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
      // State
      const user = ref<User | null>(null)
      const isLoading = ref(false)

      // Getters
      const isAuthenticated = computed(() => {
            const auth = !!user.value
            console.log('isAuthenticated check:', {
                  hasUser: !!user.value,
                  userId: user.value?.id,
                  result: auth,
                  tokenExists: !!localStorage.getItem('auth_token')
            })
            return auth
      })
      const hasRole = computed(() => (roleName: string) => {
            return user.value?.roles?.some(role => role.name === roleName) ?? false
      })
      const hasPermission = computed(() => (permissionName: string) => {
            return user.value?.roles?.some(role =>
                  role.permissions?.some(permission => permission.name === permissionName)
            ) ?? false
      })

      // Actions
      const setAuth = (userData: User) => {
            console.log('Setting auth:', { user: userData })
            user.value = userData
            localStorage.setItem('user', JSON.stringify(userData))
            console.log('Auth set successfully, user.value:', !!user.value)
      }

      const clearAuth = () => {
            user.value = null
            localStorage.removeItem('user')
            localStorage.removeItem('auth_token') // Remove any legacy tokens
      }

      const fetchUser = async () => {
            try {
                  isLoading.value = true
                  const response = await apiService.getCurrentUser()
                  user.value = response as User
                  localStorage.setItem('user', JSON.stringify(response))
                  console.log('User fetched successfully:', response)
                  return response
            } catch (error: any) {
                  console.error('Failed to fetch user:', error)
                  // If 401 Unauthorized, clear authentication as token is invalid
                  if (error.response?.status === 401) {
                        console.log('Token invalid, clearing authentication')
                        clearAuth()
                  }
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const login = async (email: string, password: string) => {
            try {
                  isLoading.value = true

                  // Use API authentication instead of web authentication
                  const response = await apiService.login(email, password)

                  if (response.data?.token && response.data?.user) {
                        setAuth(response.data.user as User)
                        localStorage.setItem('auth_token', response.data.token)
                        return { success: true, user: response.data.user }
                  } else {
                        throw new Error('Invalid response format')
                  }
            } catch (error) {
                  console.error('Login error:', error)
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const register = async (userData: {
            name: string
            email: string
            password: string
            password_confirmation: string
      }) => {
            try {
                  isLoading.value = true

                  // Use form-based registration with Laravel
                  const formData = new FormData()
                  formData.append('name', userData.name)
                  formData.append('email', userData.email)
                  formData.append('password', userData.password)
                  formData.append('password_confirmation', userData.password_confirmation)
                  formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')

                  const response = await fetch('/register', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                              'X-Requested-With': 'XMLHttpRequest',
                        }
                  })

                  if (response.ok) {
                        // Fetch user data after successful registration
                        const userResponse = await apiService.getCurrentUser()
                        setAuth(userResponse as User)
                        return { success: true, user: userResponse }
                  } else {
                        const errorData = await response.json()
                        throw new Error(errorData.message || 'Registration failed')
                  }
            } catch (error) {
                  console.error('Registration error:', error)
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const logout = async () => {
            try {
                  const formData = new FormData()
                  formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')

                  await fetch('/logout', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                              'X-Requested-With': 'XMLHttpRequest',
                        }
                  })
            } catch (error) {
                  console.error('Logout error:', error)
            } finally {
                  clearAuth()
            }
      }

      const resendVerification = async () => {
            try {
                  const response = await api.post('/email/verification-notification')
                  return response
            } catch (error) {
                  throw error
            }
      }

      const forgotPassword = async (email: string) => {
            try {
                  const response = await api.post('/forgot-password', { email })
                  return response
            } catch (error) {
                  throw error
            }
      }

      const resetPassword = async (data: {
            token: string
            email: string
            password: string
            password_confirmation: string
      }) => {
            try {
                  const response = await api.post('/reset-password', data)
                  return response
            } catch (error) {
                  throw error
            }
      }

      // Initialize store
      const init = async () => {
            const storedUser = localStorage.getItem('user')
            const storedToken = localStorage.getItem('auth_token')

            if (storedUser && storedToken) {
                  try {
                        user.value = JSON.parse(storedUser)
                        console.log('Auth initialized with stored data:', user.value?.name)

                        // Try to fetch fresh user data to verify session is still valid
                        try {
                              await fetchUser()
                              console.log('User session verified successfully')
                        } catch (error) {
                              console.warn('Session may have expired, clearing stored user')
                              clearAuth()
                        }
                  } catch (error) {
                        console.error('Auth init error:', error)
                        clearAuth()
                  }
            } else {
                  console.log('No stored authentication found, user will need to login')
            }
      }

      return {
            // State
            user,
            isLoading,

            // Getters
            isAuthenticated,
            hasRole,
            hasPermission,

            // Actions
            setAuth,
            clearAuth,
            fetchUser,
            login,
            register,
            logout,
            resendVerification,
            forgotPassword,
            resetPassword,
            init,
      }
})

