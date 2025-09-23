// API Service for connecting Frontend with Backend
import axios, { type AxiosInstance, type AxiosResponse } from 'axios'

// API Configuration
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1'

// Create axios instance
const api: AxiosInstance = axios.create({
      baseURL: API_BASE_URL,
      timeout: 10000,
      headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
      },
      withCredentials: true,
})

// Request interceptor to add auth token
api.interceptors.request.use(
      (config) => {
            const token = localStorage.getItem('auth_token')
            if (token) {
                  config.headers.Authorization = `Bearer ${token}`
            }
            return config
      },
      (error) => {
            return Promise.reject(error)
      }
)

// Response interceptor for error handling
api.interceptors.response.use(
      (response: AxiosResponse) => {
            return response
      },
      (error) => {
            if (error.response?.status === 401) {
                  // Unauthorized - clear token and redirect to login
                  localStorage.removeItem('auth_token')
                  window.location.href = '/login'
            }
            return Promise.reject(error)
      }
)

// API Response Types
export interface ApiResponse<T = any> {
      message: string
      data: T
}

export interface PaginatedResponse<T = any> {
      data: T[]
      current_page: number
      last_page: number
      per_page: number
      total: number
}

// Event Types
export interface Event {
      id: number
      title: string
      description: string
      short_description?: string
      slug: string
      venue_id: number
      organizer_id: number
      start_date: string
      end_date: string
      registration_start?: string
      registration_end?: string
      status: 'draft' | 'published' | 'cancelled'
      images?: string[]
      cancellation_policy?: string
      additional_info?: any
      is_featured: boolean
      total_capacity?: number
      available_capacity?: number
      tags?: string[]
      created_at: string
      updated_at: string
      venue?: Venue
      organizer?: User
      ticket_types?: TicketType[]
}

export interface CreateEventRequest {
      title: string
      description: string
      short_description?: string
      venue_id: number
      start_date: string
      end_date: string
      registration_start?: string
      registration_end?: string
      images?: string[]
      cancellation_policy?: string
      additional_info?: any
      total_capacity?: number
      tags?: string[]
}

export interface UpdateEventRequest {
      title?: string
      description?: string
      short_description?: string
      venue_id?: number
      start_date?: string
      end_date?: string
      registration_start?: string
      registration_end?: string
      status?: 'draft' | 'published' | 'cancelled'
      images?: string[]
      cancellation_policy?: string
      additional_info?: any
      is_featured?: boolean
      total_capacity?: number
      tags?: string[]
}

// Venue Types
export interface Venue {
      id: number
      name: string
      address: string
      city: string
      state: string
      country: string
      postal_code: string
      capacity: number
      amenities?: string[]
      images?: string[]
      contact_email?: string
      contact_phone?: string
      website?: string
      is_active: boolean
      created_at: string
      updated_at: string
}

export interface CreateVenueRequest {
      name: string
      address: string
      city: string
      state: string
      country: string
      postal_code: string
      capacity: number
      amenities?: string[]
      images?: string[]
      contact_email?: string
      contact_phone?: string
      website?: string
}

// User Types
export interface User {
      id: number
      name: string
      email: string
      email_verified_at?: string
      roles?: Role[]
      created_at: string
      updated_at: string
}

export interface Role {
      id: number
      name: string
      display_name: string
      description?: string
}

// Ticket Type Types
export interface TicketType {
      id: number
      event_id: number
      name: string
      description?: string
      price: number
      quantity: number
      sold_quantity: number
      is_active: boolean
      sort_order: number
      created_at: string
      updated_at: string
}

// API Service Class
class ApiService {
      // Events API
      async getEvents(params?: {
            search?: string
            category?: string
            start_date?: string
            end_date?: string
            min_price?: number
            max_price?: number
            city?: string
            featured?: boolean
            sort_by?: string
            sort_order?: 'asc' | 'desc'
            per_page?: number
            page?: number
      }): Promise<PaginatedResponse<Event>> {
            const response = await api.get('/events', { params })
            return response.data.data
      }

      async getEvent(id: number): Promise<Event> {
            const response = await api.get(`/events/${id}`)
            return response.data.data
      }

      async createEvent(eventData: CreateEventRequest): Promise<Event> {
            const response = await api.post('/events', eventData)
            return response.data.data
      }

      async updateEvent(id: number, eventData: UpdateEventRequest): Promise<Event> {
            const response = await api.put(`/events/${id}`, eventData)
            return response.data.data
      }

      async deleteEvent(id: number): Promise<void> {
            await api.delete(`/events/${id}`)
      }

      async getMyEvents(params?: {
            status?: string
            per_page?: number
            page?: number
      }): Promise<PaginatedResponse<Event>> {
            const response = await api.get('/my-events', { params })
            return response.data.data
      }

      async publishEvent(id: number): Promise<Event> {
            const response = await api.post(`/events/${id}/publish`)
            return response.data.data
      }

      // Venues API
      async getVenues(params?: {
            search?: string
            city?: string
            state?: string
            capacity_min?: number
            capacity_max?: number
            per_page?: number
            page?: number
      }): Promise<PaginatedResponse<Venue>> {
            const response = await api.get('/venues', { params })
            return response.data.data
      }

      async getVenue(id: number): Promise<Venue> {
            const response = await api.get(`/venues/${id}`)
            return response.data.data
      }

      async createVenue(venueData: CreateVenueRequest): Promise<Venue> {
            const response = await api.post('/venues', venueData)
            return response.data.data
      }

      async updateVenue(id: number, venueData: Partial<CreateVenueRequest>): Promise<Venue> {
            const response = await api.put(`/venues/${id}`, venueData)
            return response.data.data
      }

      async deleteVenue(id: number): Promise<void> {
            await api.delete(`/venues/${id}`)
      }

      // Authentication API
      async login(email: string, password: string): Promise<{ token: string; user: User }> {
            const response = await api.post('/login', { email, password })
            return response.data
      }

      async register(userData: {
            name: string
            email: string
            password: string
            password_confirmation: string
      }): Promise<{ token: string; user: User }> {
            const response = await api.post('/register', userData)
            return response.data
      }

      async logout(): Promise<void> {
            await api.post('/logout')
      }

      async getCurrentUser(): Promise<User> {
            const response = await api.get('/user')
            return response.data.data
      }

      // Dashboard API
      async getDashboardStats(): Promise<any> {
            const response = await api.get('/dashboard/stats')
            return response.data.data
      }

      // Error handling helper
      handleError(error: any): string {
            if (error.response?.data?.message) {
                  return error.response.data.message
            }
            if (error.response?.data?.errors) {
                  const errors = error.response.data.errors
                  return Object.values(errors).flat().join(', ')
            }
            return error.message || 'An error occurred'
      }
}

// Create singleton instance
export const apiService = new ApiService()

// Export types and service
export { api }
export default apiService