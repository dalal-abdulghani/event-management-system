export interface User {
      id: number
      name: string
      email: string
      email_verified_at: string | null
      roles: Role[]
      created_at: string
      updated_at: string
}

export interface Role {
      id: number
      name: string
      guard_name: string
      permissions: Permission[]
}

export interface Permission {
      id: number
      name: string
      guard_name: string
}

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
      status: 'draft' | 'published' | 'cancelled' | 'completed'
      images?: string[]
      cancellation_policy?: string
      additional_info?: Record<string, any>
      is_featured: boolean
      min_price?: number
      max_price?: number
      total_capacity?: number
      available_capacity?: number
      tags?: string[]
      venue?: Venue
      organizer?: User
      ticket_types?: TicketType[]
      sessions?: Session[]
      created_at: string
      updated_at: string
}

export interface Venue {
      id: number
      name: string
      address: string
      city: string
      state: string
      country: string
      postal_code: string
      latitude?: number
      longitude?: number
      capacity?: number
      description?: string
      amenities?: string[]
      contact_info?: Record<string, any>
      created_at: string
      updated_at: string
}

export interface TicketType {
      id: number
      event_id: number
      name: string
      description?: string
      price: number
      quantity: number
      available_quantity: number
      sale_start_date?: string
      sale_end_date?: string
      is_active: boolean
      sort_order: number
      metadata?: Record<string, any>
      created_at: string
      updated_at: string
}

export interface Ticket {
      id: number
      event_id: number
      ticket_type_id: number
      order_id: number
      user_id: number
      ticket_number: string
      qr_code: string
      status: 'active' | 'used' | 'refunded' | 'cancelled'
      checked_in_at?: string
      checked_in_by?: number
      metadata?: Record<string, any>
      event?: Event
      ticket_type?: TicketType
      order?: Order
      user?: User
      created_at: string
      updated_at: string
}

export interface Order {
      id: number
      user_id: number
      event_id: number
      order_number: string
      status: 'pending' | 'completed' | 'cancelled' | 'refunded'
      subtotal: number
      tax_amount: number
      discount_amount: number
      total_amount: number
      currency: string
      payment_method?: string
      payment_id?: string
      coupon_code?: string
      referral_code?: string
      billing_info?: Record<string, any>
      notes?: string
      user?: User
      event?: Event
      items?: OrderItem[]
      created_at: string
      updated_at: string
}

export interface OrderItem {
      id: number
      order_id: number
      ticket_type_id: number
      quantity: number
      unit_price: number
      total_price: number
      ticket_type?: TicketType
      created_at: string
      updated_at: string
}

export interface Session {
      id: number
      event_id: number
      title: string
      description?: string
      speaker_name?: string
      speaker_bio?: string
      speaker_image?: string
      start_time: string
      end_time: string
      sort_order: number
      created_at: string
      updated_at: string
}

export interface Coupon {
      id: number
      code: string
      type: 'fixed' | 'percentage'
      value: number
      minimum_amount?: number
      usage_limit?: number
      used_count: number
      is_active: boolean
      valid_from: string
      valid_until: string
      applicable_events?: number[]
      created_at: string
      updated_at: string
}

export interface Referral {
      id: number
      user_id: number
      event_id: number
      referral_code: string
      clicks: number
      conversions: number
      commission_rate: number
      total_commission: number
      is_active: boolean
      created_at: string
      updated_at: string
}

export interface ApiResponse<T = any> {
      message: string
      data?: T
      errors?: Record<string, string[]>
      meta?: {
            current_page: number
            last_page: number
            per_page: number
            total: number
      }
}

export interface PaginatedResponse<T> {
      data: T[]
      current_page: number
      last_page: number
      per_page: number
      total: number
      from: number
      to: number
      links: {
            first: string
            last: string
            prev: string | null
            next: string | null
      }
}

export interface DashboardStats {
      total_events: number
      total_tickets_sold: number
      total_revenue: number
      upcoming_events: number
      recent_orders: Order[]
      top_events: Array<{
            event: Event
            tickets_sold: number
            revenue: number
      }>
}

export interface CheckinData {
      ticket: Ticket
      event: Event
      user: User
      is_valid: boolean
      message: string
}

// Request/Response types for API
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

