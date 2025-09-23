import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Event, PaginatedResponse } from '@/types'
import { api } from '@/services/api'

export const useEventsStore = defineStore('events', () => {
      // State
      const events = ref<Event[]>([])
      const currentEvent = ref<Event | null>(null)
      const myEvents = ref<Event[]>([])
      const isLoading = ref(false)
      const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
      })

      // Actions
      const fetchEvents = async (params?: {
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
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get('/events', { params })

                  events.value = response.data.data || []
                  pagination.value = {
                        current_page: response.data.data.current_page || 1,
                        last_page: response.data.data.last_page || 1,
                        per_page: response.data.data.per_page || 15,
                        total: response.data.data.total || 0,
                  }

                  return response.data.data
            } catch (error) {
                  console.error('Error fetching events:', error)
                  events.value = []
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.get(`/events/${id}`)

                  currentEvent.value = response.data.data

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchMyEvents = async (params?: {
            status?: string
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get('/my-events', { params })

                  myEvents.value = response.data.data || []

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const createEvent = async (eventData: Partial<Event>) => {
            try {
                  isLoading.value = true
                  const response = await api.post('/events', eventData)

                  myEvents.value.unshift(response.data.data)
                  currentEvent.value = response.data.data

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const updateEvent = async (id: string | number, eventData: Partial<Event>) => {
            try {
                  isLoading.value = true
                  const response = await api.put(`/events/${id}`, eventData)

                  // Update in myEvents array
                  const index = myEvents.value.findIndex(event => event.id === id)
                  if (index !== -1) {
                        myEvents.value[index] = response.data.data
                  }

                  // Update current event if it's the same
                  if (currentEvent.value?.id === id) {
                        currentEvent.value = response.data.data
                  }

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const deleteEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  await api.delete(`/events/${id}`)

                  // Remove from myEvents array
                  myEvents.value = myEvents.value.filter(event => event.id !== id)

                  // Clear current event if it's the same
                  if (currentEvent.value?.id === id) {
                        currentEvent.value = null
                  }
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const publishEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.post(`/events/${id}/publish`)

                  // Update in myEvents array
                  const index = myEvents.value.findIndex(event => event.id === id)
                  if (index !== -1) {
                        myEvents.value[index] = response.data.data
                  }

                  // Update current event if it's the same
                  if (currentEvent.value?.id === id) {
                        currentEvent.value = response.data.data
                  }

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const clearCurrentEvent = () => {
            currentEvent.value = null
      }

      const clearEvents = () => {
            events.value = []
            myEvents.value = []
            currentEvent.value = null
            pagination.value = {
                  current_page: 1,
                  last_page: 1,
                  per_page: 15,
                  total: 0,
            }
      }

      return {
            // State
            events,
            currentEvent,
            myEvents,
            isLoading,
            pagination,

            // Actions
            fetchEvents,
            fetchEvent,
            fetchMyEvents,
            createEvent,
            updateEvent,
            deleteEvent,
            publishEvent,
            clearCurrentEvent,
            clearEvents,
      }
})

