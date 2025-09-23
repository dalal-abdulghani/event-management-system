<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <div class="hidden md:flex md:w-64 md:flex-col">
      <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto shadow-lg">
        <div class="flex items-center flex-shrink-0 px-4 mb-6">
          <h1 class="text-xl font-bold text-indigo-600">EventHub Admin</h1>
        </div>
        <div class="mt-5 flex-grow flex flex-col">
          <nav class="flex-1 px-2 pb-4 space-y-1">
            <!-- Dashboard -->
            <router-link
              to="/admin"
              class="nav-item group"
              active-class="nav-item-active"
              exact-active-class="nav-item-active"
            >
              <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
              </svg>
              Dashboard
            </router-link>

            <!-- Event Management Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Event Management</h3>
              <router-link to="/admin/events" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                All Events
              </router-link>
              <router-link to="/admin/events/create" class="nav-item nav-item-sub">
                Create Event
              </router-link>
              <router-link to="/admin/venues" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Venues
              </router-link>
            </div>

            <!-- Ticketing Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Ticketing</h3>
              <router-link to="/admin/ticketing" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
                </svg>
                Ticket Management
              </router-link>
              <router-link to="/admin/ticketing/templates" class="nav-item nav-item-sub">
                Templates
              </router-link>
              <router-link to="/admin/ticketing/check-in" class="nav-item nav-item-sub">
                Check-in System
              </router-link>
            </div>

            <!-- Sales & Finance Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Sales & Finance</h3>
              <router-link to="/admin/sales" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Sales Overview
              </router-link>
              <router-link to="/admin/sales/reports" class="nav-item nav-item-sub">
                Sales Reports
              </router-link>
              <router-link to="/admin/sales/commissions" class="nav-item nav-item-sub">
                Commissions
              </router-link>
            </div>

            <!-- Customers Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Customers</h3>
              <router-link to="/admin/customers" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Customer Database
              </router-link>
              <router-link to="/admin/support" class="nav-item nav-item-sub">
                Support Requests
              </router-link>
            </div>

            <!-- Marketing Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Marketing</h3>
              <router-link to="/admin/marketing" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                Campaigns
              </router-link>
              <router-link to="/admin/marketing/discounts" class="nav-item nav-item-sub">
                Discount Codes
              </router-link>
              <router-link to="/admin/marketing/referrals" class="nav-item nav-item-sub">
                Referral System
              </router-link>
            </div>

            <!-- Analytics Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">Analytics</h3>
              <router-link to="/admin/analytics" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analytics Dashboard
              </router-link>
              <router-link to="/admin/reports" class="nav-item nav-item-sub">
                Reports
              </router-link>
            </div>

            <!-- System Section -->
            <div class="nav-section">
              <h3 class="nav-section-title">System</h3>
              <router-link to="/admin/users" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                User Management
              </router-link>
              <router-link to="/admin/security" class="nav-item nav-item-sub">
                Security & Permissions
              </router-link>
              <router-link to="/admin/activity-logs" class="nav-item nav-item-sub">
                Activity Logs
              </router-link>
              <router-link to="/admin/integrations" class="nav-item nav-item-sub">
                Integrations
              </router-link>
              <router-link to="/admin/settings" class="nav-item group">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
              </router-link>
            </div>
          </nav>
        </div>
        
        <!-- User Profile & Logout -->
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
          <div class="flex items-center w-full">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                <span class="text-sm font-medium text-white">A</span>
              </div>
            </div>
            <div class="ml-3 flex-1">
              <p class="text-sm font-medium text-gray-700">Admin User</p>
              <button 
                @click="logout"
                class="text-xs text-gray-500 hover:text-gray-700"
              >
                Sign out
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile sidebar -->
    <div v-if="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden">
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
      <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
        <!-- Mobile sidebar content (same as desktop but with close button) -->
        <div class="absolute top-0 right-0 -mr-12 pt-2">
          <button
            @click="sidebarOpen = false"
            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
          >
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <!-- Mobile nav content goes here -->
      </div>
    </div>

    <!-- Main content area -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <!-- Top bar for mobile -->
      <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-white shadow-sm">
        <button
          @click="sidebarOpen = true"
          class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>

      <!-- Page content -->
      <main class="flex-1 relative overflow-y-auto focus:outline-none">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <router-view />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const sidebarOpen = ref(false)

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.nav-item {
  @apply text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out;
}

.nav-item-active {
  @apply bg-indigo-50 border-r-2 border-indigo-500 text-indigo-700;
}

.nav-item-sub {
  @apply ml-6 text-xs;
}

.nav-icon {
  @apply text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-5 w-5;
}

.nav-section {
  @apply mt-6 first:mt-0;
}

.nav-section-title {
  @apply px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2;
}

.router-link-active .nav-icon {
  @apply text-indigo-500;
}
</style>
