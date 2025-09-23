import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Order, Ticket, PaginatedResponse } from '@/types'
import api from '@/services/api'

export const useOrdersStore = defineStore('orders', () => {
      // State
      const orders = ref<Order[]>([])
      const myOrders = ref<Order[]>([])
      const myTickets = ref<Ticket[]>([])
      const currentOrder = ref<Order | null>(null)
      const isLoading = ref(false)

      // Actions
      const fetchOrders = async (params?: {
            status?: string
            event_id?: number
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get<PaginatedResponse<Order>>('/orders', params)

                  if (response.data) {
                        orders.value = response.data.data
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchMyOrders = async (params?: {
            status?: string
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get<PaginatedResponse<Order>>('/my-orders', params)

                  if (response.data) {
                        myOrders.value = response.data.data
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchMyTickets = async (params?: {
            status?: string
            event_id?: number
            page?: number
            per_page?: number
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.get<PaginatedResponse<Ticket>>('/my-tickets', params)

                  if (response.data) {
                        myTickets.value = response.data.data
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const fetchOrder = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.get<Order>(`/orders/${id}`)

                  if (response.data) {
                        currentOrder.value = response.data
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const createOrder = async (orderData: {
            event_id: number
            items: Array<{
                  ticket_type_id: number
                  quantity: number
            }>
            coupon_code?: string
            referral_code?: string
            billing_info?: Record<string, any>
      }) => {
            try {
                  isLoading.value = true
                  const response = await api.post<Order>('/orders', orderData)

                  if (response.data) {
                        currentOrder.value = response.data
                        myOrders.value.unshift(response.data)
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const updateOrder = async (id: string | number, orderData: Partial<Order>) => {
            try {
                  isLoading.value = true
                  const response = await api.put<Order>(`/orders/${id}`, orderData)

                  if (response.data) {
                        // Update in myOrders array
                        const index = myOrders.value.findIndex(order => order.id === id)
                        if (index !== -1) {
                              myOrders.value[index] = response.data
                        }

                        // Update current order if it's the same
                        if (currentOrder.value?.id === id) {
                              currentOrder.value = response.data
                        }
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const cancelOrder = async (id: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.post<Order>(`/orders/${id}/cancel`)

                  if (response.data) {
                        // Update in myOrders array
                        const index = myOrders.value.findIndex(order => order.id === id)
                        if (index !== -1) {
                              myOrders.value[index] = response.data
                        }

                        // Update current order if it's the same
                        if (currentOrder.value?.id === id) {
                              currentOrder.value = response.data
                        }
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const checkInTicket = async (ticketId: string | number) => {
            try {
                  isLoading.value = true
                  const response = await api.post(`/tickets/${ticketId}/checkin`)

                  if (response.data) {
                        // Update ticket in myTickets array
                        const index = myTickets.value.findIndex(ticket => ticket.id === ticketId)
                        if (index !== -1) {
                              myTickets.value[index] = response.data
                        }
                  }

                  return response
            } catch (error) {
                  throw error
            } finally {
                  isLoading.value = false
            }
      }

      const validateCoupon = async (code: string, eventId: number, amount: number) => {
            try {
                  const response = await api.post('/coupons/validate', {
                        code,
                        event_id: eventId,
                        amount,
                  })

                  return response
            } catch (error) {
                  throw error
            }
      }

      const clearCurrentOrder = () => {
            currentOrder.value = null
      }

      const clearOrders = () => {
            orders.value = []
            myOrders.value = []
            myTickets.value = []
            currentOrder.value = null
      }

      return {
            // State
            orders,
            myOrders,
            myTickets,
            currentOrder,
            isLoading,

            // Actions
            fetchOrders,
            fetchMyOrders,
            fetchMyTickets,
            fetchOrder,
            createOrder,
            updateOrder,
            cancelOrder,
            checkInTicket,
            validateCoupon,
            clearCurrentOrder,
            clearOrders,
      }
})

