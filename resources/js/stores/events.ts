import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Event } from '@/types'
import apiService from '@/services/api'

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
                  const response = await apiService.getEvents(params)

                  if (response) {
                        events.value = response.data as Event[]
                        pagination.value = {
                              current_page: response.current_page,
                              last_page: response.last_page,
                              per_page: response.per_page,
                              total: response.total,
                        }
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await apiService.getEvent(id)

                  if (response) {
                        currentEvent.value = response as Event as Event
                  }

                  return response
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
                  const response = await apiService.getMyEvents(params)

                  if (response) {
                        myEvents.value = response.data as Event[]
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const createEvent = async (eventData: Partial<Event>) => {
            try {
                  isLoading.value = true
                  const response = await apiService.createEvent(eventData)

                  if (response) {
                        myEvents.value.unshift(response as Event)
                        currentEvent.value = response as Event as Event
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const updateEvent = async (id: string | number, eventData: Partial<Event>) => {
            try {
                  isLoading.value = true
                  const response = await apiService.updateEvent(id, eventData)

                  if (response) {
                        // Update in myEvents array
                        const index = myEvents.value.findIndex(event => event.id === id)
                        if (index !== -1) {
                              myEvents.value[index] = response as Event
                        }

                        // Update current event if it's the same
                        if (currentEvent.value?.id === id) {
                              currentEvent.value = response as Event
                        }
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const deleteEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await apiService.deleteEvent(id)

                  // Remove from myEvents array
                  myEvents.value = myEvents.value.filter(event => event.id !== id)

                  // Clear current event if it's the same
                  if (currentEvent.value?.id === id) {
                        currentEvent.value = null
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const publishEvent = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await apiService.publishEvent(id)

                  if (response) {
                        // Update in myEvents array
                        const index = myEvents.value.findIndex(event => event.id === id)
                        if (index !== -1) {
                              myEvents.value[index] = response as Event
                        }

                        // Update current event if it's the same
                        if (currentEvent.value?.id === id) {
                              currentEvent.value = response as Event
                        }
                  }

                  return response
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

