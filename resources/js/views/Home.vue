<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { 
  CalendarDaysIcon, 
  MapPinIcon, 
  TicketIcon, 
  UserGroupIcon,
  StarIcon
} from '@heroicons/vue/24/outline'
import { StarIcon as StarIconSolid } from '@heroicons/vue/24/solid'

const authStore = useAuthStore()

const featuredEvents = ref([
  {
    id: 1,
    title: 'Tech Conference 2025',
    short_description: 'Join us for the biggest tech conference of the year with industry leaders and innovators.',
    start_date: '2025-03-15T09:00:00Z',
    min_price: 99,
    is_featured: true,
    venue: { city: 'San Francisco', state: 'CA' }
  },
  {
    id: 2,
    title: 'Music Festival Summer',
    short_description: 'Experience the best music acts in a beautiful outdoor setting.',
    start_date: '2025-06-20T18:00:00Z',
    min_price: 75,
    is_featured: true,
    venue: { city: 'Los Angeles', state: 'CA' }
  },
  {
    id: 3,
    title: 'Business Summit',
    short_description: 'Network with business leaders and learn about the latest industry trends.',
    start_date: '2025-04-10T08:00:00Z',
    min_price: 149,
    is_featured: true,
    venue: { city: 'New York', state: 'NY' }
  }
])

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price)
}
</script>

<template>
  <div class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <RouterLink to="/" class="flex-shrink-0">
              <h1 class="text-2xl font-bold text-primary-600">EventHub</h1>
            </RouterLink>
            <div class="hidden md:ml-6 md:flex md:space-x-8">
              <RouterLink to="/events" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                Events
              </RouterLink>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <template v-if="!authStore.isAuthenticated">
              <RouterLink to="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                Sign in
              </RouterLink>
              <RouterLink to="/register" class="btn-primary">
                Sign up
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink to="/dashboard" class="btn-primary">
                Dashboard
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
            Discover Amazing Events
          </h1>
          <p class="mt-3 max-w-md mx-auto text-base text-primary-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            Find and book tickets for concerts, conferences, workshops, and more. Create unforgettable experiences.
          </p>
          <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
            <div class="rounded-md shadow">
              <RouterLink to="/events" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                Browse Events
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Events -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Featured Events</h2>
        <p class="mt-4 text-lg text-gray-500">Don't miss out on these amazing upcoming events</p>
      </div>

      <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="event in featuredEvents" :key="event.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <div class="h-48 bg-gradient-to-r from-primary-400 to-primary-600 flex items-center justify-center">
            <CalendarDaysIcon class="h-16 w-16 text-white" />
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                Featured
              </span>
              <div class="flex items-center">
                <StarIconSolid class="h-4 w-4 text-yellow-400" />
                <StarIconSolid class="h-4 w-4 text-yellow-400" />
                <StarIconSolid class="h-4 w-4 text-yellow-400" />
                <StarIconSolid class="h-4 w-4 text-yellow-400" />
                <StarIcon class="h-4 w-4 text-gray-300" />
              </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ event.title }}</h3>
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ event.short_description }}</p>
            <div class="space-y-2 text-sm text-gray-500">
              <div class="flex items-center">
                <CalendarDaysIcon class="h-4 w-4 mr-2" />
                {{ formatDate(event.start_date) }}
              </div>
              <div class="flex items-center">
                <MapPinIcon class="h-4 w-4 mr-2" />
                {{ event.venue?.city }}, {{ event.venue?.state }}
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <TicketIcon class="h-4 w-4 mr-2" />
                  From {{ formatPrice(event.min_price || 0) }}
                </div>
                <RouterLink 
                  :to="`/events/${event.id}`" 
                  class="text-primary-600 hover:text-primary-700 font-medium"
                >
                  View Details
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-primary-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
          <h2 class="text-3xl font-extrabold text-gray-900">Join Thousands of Event Enthusiasts</h2>
        </div>
        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-3">
          <div class="text-center">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-primary-600 mx-auto">
              <CalendarDaysIcon class="h-8 w-8 text-white" />
            </div>
            <div class="mt-4">
              <div class="text-3xl font-bold text-gray-900">500+</div>
              <div class="text-sm text-gray-500">Events Hosted</div>
            </div>
          </div>
          <div class="text-center">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-primary-600 mx-auto">
              <UserGroupIcon class="h-8 w-8 text-white" />
            </div>
            <div class="mt-4">
              <div class="text-3xl font-bold text-gray-900">50K+</div>
              <div class="text-sm text-gray-500">Happy Attendees</div>
            </div>
          </div>
          <div class="text-center">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-primary-600 mx-auto">
              <TicketIcon class="h-8 w-8 text-white" />
            </div>
            <div class="mt-4">
              <div class="text-3xl font-bold text-gray-900">100K+</div>
              <div class="text-sm text-gray-500">Tickets Sold</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <h3 class="text-2xl font-bold text-white mb-4">EventHub</h3>
          <p class="text-gray-300 mb-8">Making events accessible to everyone</p>
          <div class="flex justify-center space-x-6">
            <RouterLink to="/events" class="text-gray-300 hover:text-white">Events</RouterLink>
            <RouterLink to="/login" class="text-gray-300 hover:text-white">Sign In</RouterLink>
            <RouterLink to="/register" class="text-gray-300 hover:text-white">Sign Up</RouterLink>
          </div>
          <div class="mt-8 text-gray-400 text-sm">
            © 2025 EventHub. All rights reserved.
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

