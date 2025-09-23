import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Venue, PaginatedResponse } from '@/types'
import { api } from '@/services/api'

export const useVenuesStore = defineStore('venues', () => {
      // State
      const venues = ref<Venue[]>([])
      const currentVenue = ref<Venue | null>(null)
      const myVenues = ref<Venue[]>([])
      const isLoading = ref(false)
      const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
      })

      // Actions
      const fetchVenues = async (params?: {
            search?: string
            city?: string
            capacity_min?: number
            capacity_max?: number
            amenities?: string[]
            sort_by?: string
            sort_order?: 'asc' | 'desc'
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get('/venues', { params })

                  venues.value = response.data.data || []
                  pagination.value = {
                        current_page: response.data.current_page || 1,
                        last_page: response.data.last_page || 1,
                        per_page: response.data.per_page || 15,
                        total: response.data.total || 0,
                  }

                  return response.data
            } catch (error) {
                  console.error('Error fetching venues:', error)
                  venues.value = []
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchVenue = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.get(`/venues/${id}`)

                  currentVenue.value = response.data.data

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchMyVenues = async (params?: {
            status?: string
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get('/my-venues', { params })

                  myVenues.value = response.data.data || []

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const createVenue = async (venueData: Partial<Venue>) => {
            try {
                  isLoading.value = true
                  const response = await api.post('/venues', venueData)

                  myVenues.value.unshift(response.data.data)
                  currentVenue.value = response.data.data

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const updateVenue = async (id: string | number, venueData: Partial<Venue>) => {
            try {
                  isLoading.value = true
                  const response = await api.put(`/venues/${id}`, venueData)

                  // Update in myVenues array
                  const index = myVenues.value.findIndex(venue => venue.id === id)
                  if (index !== -1) {
                        myVenues.value[index] = response.data.data
                  }

                  // Update current venue if it's the same
                  if (currentVenue.value?.id === id) {
                        currentVenue.value = response.data.data
                  }

                  return response.data.data
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const deleteVenue = async (id: string | number) => {
            try {
                  isLoading.value = true
                  await api.delete(`/venues/${id}`)

                  // Remove from myVenues array
                  myVenues.value = myVenues.value.filter(venue => venue.id !== id)

                  // Clear current venue if it's the same
                  if (currentVenue.value?.id === id) {
                        currentVenue.value = null
                  }
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const clearCurrentVenue = () => {
            currentVenue.value = null
      }

      const clearVenues = () => {
            venues.value = []
            myVenues.value = []
            currentVenue.value = null
            pagination.value = {
                  current_page: 1,
                  last_page: 1,
                  per_page: 15,
                  total: 0,
            }
      }

      return {
            // State
            venues,
            currentVenue,
            myVenues,
            isLoading,
            pagination,

            // Actions
            fetchVenues,
            fetchVenue,
            fetchMyVenues,
            createVenue,
            updateVenue,
            deleteVenue,
            clearCurrentVenue,
            clearVenues,
      }
})
