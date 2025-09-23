import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

interface User {
      id: number
      name: string
      email: string
      email_verified_at: string | null
      roles: Array<{
            id: number
            name: string
            guard_name: string
            permissions: Array<{
                  id: number
                  name: string
                  guard_name: string
            }>
      }>
      created_at: string
      updated_at: string
}

interface Role {
      id: number
      name: string
      guard_name: string
      permissions: Array<{
            id: number
            name: string
            guard_name: string
      }>
}

export const useAuthStore = defineStore('auth', () => {
      // State
      const user = ref<User | null>(null)
      const token = ref<string | null>(localStorage.getItem('auth_token'))
      const isLoading = ref(false)

      // Getters
      const isAuthenticated = computed(() => {
            const auth = !!token.value && !!user.value
            console.log('isAuthenticated check:', { hasToken: !!token.value, hasUser: !!user.value, result: auth })
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
      const setAuth = (authToken: string, userData: User) => {
            console.log('Setting auth:', { token: authToken, user: userData })
            token.value = authToken
            user.value = userData
            localStorage.setItem('auth_token', authToken)
            localStorage.setItem('user', JSON.stringify(userData))
            console.log('Auth set successfully')
      }

      const clearAuth = () => {
            token.value = null
            user.value = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user')
      }

      const fetchUser = async () => {
            if (!token.value) return

            try {
                  isLoading.value = true
                  const response = await axios.get(`${API_BASE_URL}/user`, {
                        headers: {
                              'Authorization': `Bearer ${token.value}`,
                              'Content-Type': 'application/json'
                        }
                  })
                  user.value = response.data.data
                  localStorage.setItem('user', JSON.stringify(response.data.data))
            } catch (error) {
                  clearAuth()
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const login = async (email: string, password: string) => {
            try {
                  isLoading.value = true

                  // Check for demo admin credentials
                  if (email === 'admin@eventmanager.com' && password === 'password') {
                        // Demo admin user
                        const demoUser: User = {
                              id: 1,
                              name: 'Admin User',
                              email: 'admin@eventmanager.com',
                              email_verified_at: '2025-01-01T00:00:00Z',
                              roles: [{
                                    id: 1,
                                    name: 'admin',
                                    guard_name: 'web',
                                    permissions: []
                              }],
                              created_at: '2025-01-01T00:00:00Z',
                              updated_at: '2025-01-01T00:00:00Z'
                        }
                        const demoToken = 'demo_admin_token_' + Date.now()

                        setAuth(demoToken, demoUser)
                        return { data: { data: { user: demoUser, token: demoToken } } }
                  }

                  // Try API login
                  const response = await axios.post(`${API_BASE_URL}/login`, { email, password })

                  if (response.data.token && response.data.user) {
                        setAuth(response.data.token, response.data.user)
                  }

                  return response
            } catch (error) {
                  // If API fails but credentials match demo, use demo login
                  if (email === 'admin@eventmanager.com' && password === 'password') {
                        const demoUser: User = {
                              id: 1,
                              name: 'Admin User',
                              email: 'admin@eventmanager.com',
                              email_verified_at: '2025-01-01T00:00:00Z',
                              roles: [{
                                    id: 1,
                                    name: 'admin',
                                    guard_name: 'web',
                                    permissions: []
                              }],
                              created_at: '2025-01-01T00:00:00Z',
                              updated_at: '2025-01-01T00:00:00Z'
                        }
                        const demoToken = 'demo_admin_token_' + Date.now()

                        setAuth(demoToken, demoUser)
                        return { data: { data: { user: demoUser, token: demoToken } } }
                  }

                  clearAuth()
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
                  const response = await axios.post(`${API_BASE_URL}/register`, userData, {
                        headers: {
                              'Content-Type': 'application/json'
                        }
                  })

                  if (response.data.token && response.data.user) {
                        setAuth(response.data.token, response.data.user)
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const logout = async () => {
            try {
                  if (token.value) {
                        await axios.post(`${API_BASE_URL}/logout`, {}, {
                              headers: {
                                    'Authorization': `Bearer ${token.value}`,
                                    'Content-Type': 'application/json'
                              }
                        })
                  }
            } catch (error) {
                  console.error('Logout error:', error)
            } finally {
                  clearAuth()
            }
      }

      const resendVerification = async () => {
            try {
                  const response = await axios.post(`${API_BASE_URL}/email/verification-notification`, {}, {
                        headers: {
                              'Content-Type': 'application/json'
                        }
                  })
                  return response
            } catch (error) {
                  throw error
            }
      }

      const forgotPassword = async (email: string) => {
            try {
                  const response = await axios.post(`${API_BASE_URL}/forgot-password`, { email }, {
                        headers: {
                              'Content-Type': 'application/json'
                        }
                  })
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
                  const response = await axios.post(`${API_BASE_URL}/reset-password`, data, {
                        headers: {
                              'Content-Type': 'application/json'
                        }
                  })
                  return response
            } catch (error) {
                  throw error
            }
      }

      // Initialize store
      const init = async () => {
            const storedUser = localStorage.getItem('user')
            const storedToken = localStorage.getItem('auth_token')

            if (storedToken && storedUser) {
                  try {
                        token.value = storedToken
                        user.value = JSON.parse(storedUser)
                        console.log('Auth initialized with stored data:', user.value?.name)
                  } catch (error) {
                        console.error('Auth init error:', error)
                        // Keep demo auth for testing
                        if (storedToken.includes('demo_admin_token')) {
                              user.value = {
                                    id: 1,
                                    name: 'Admin User',
                                    email: 'admin@eventmanager.com',
                                    email_verified_at: '2025-01-01T00:00:00Z',
                                    roles: [{
                                          id: 1,
                                          name: 'admin',
                                          guard_name: 'web',
                                          permissions: []
                                    }],
                                    created_at: '2025-01-01T00:00:00Z',
                                    updated_at: '2025-01-01T00:00:00Z'
                              }
                        }
                  }
            }
      }

      return {
            // State
            user,
            token,
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

