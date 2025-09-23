import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
      history: createWebHistory(),
      routes: [
            // Redirect root to login for testing
            {
                  path: '/',
                  redirect: '/login'
            },
            // Authentication routes
            {
                  path: '/login',
                  name: 'Login',
                  component: () => import('@/views/auth/Login.vue'),
                  meta: { requiresAuth: false, redirectIfAuth: true }
            },
            {
                  path: '/forgot-password',
                  name: 'ForgotPassword',
                  component: () => import('@/views/auth/ForgotPassword.vue'),
                  meta: { requiresAuth: false, redirectIfAuth: true }
            },
            {
                  path: '/reset-password',
                  name: 'ResetPassword',
                  component: () => import('@/views/auth/ResetPassword.vue'),
                  meta: { requiresAuth: false, redirectIfAuth: true }
            },
            // Admin Dashboard - Main Application
            {
                  path: '/admin',
                  component: () => import('@/layouts/AdminLayout.vue'),
                  meta: { requiresAuth: true },
                  children: [
                        // Dashboard Overview
                        {
                              path: '',
                              name: 'Dashboard',
                              component: () => import('@/views/admin/Dashboard.vue'),
                              meta: { requiresAuth: true }
                        },
                        // 1️⃣ Event Management
                        {
                              path: 'events',
                              name: 'EventManagement',
                              component: () => import('@/views/admin/Events.vue'),
                              meta: { requiresAuth: true }
                        },
                        {
                              path: 'events/create',
                              name: 'CreateEvent',
                              component: () => import('@/views/admin/CreateEvent.vue')
                        },
                        {
                              path: 'events/:id/edit',
                              name: 'EditEvent',
                              component: () => import('@/views/admin/EditEvent.vue')
                        },
                        {
                              path: 'events/:id/schedule',
                              name: 'EventSchedule',
                              component: () => import('@/views/admin/EventSchedule.vue')
                        },
                        // 2️⃣ Ticketing
                        {
                              path: 'ticketing',
                              name: 'Ticketing',
                              component: () => import('@/views/admin/Ticketing.vue')
                        },
                        {
                              path: 'ticketing/templates',
                              name: 'TicketTemplates',
                              component: () => import('@/views/admin/TicketTemplates.vue')
                        },
                        {
                              path: 'ticketing/check-in',
                              name: 'CheckIn',
                              component: () => import('@/views/admin/CheckIn.vue')
                        },
                        // 3️⃣ Sales & Finance
                        {
                              path: 'sales',
                              name: 'SalesFinance',
                              component: () => import('@/views/admin/SalesFinance.vue')
                        },
                        {
                              path: 'sales/reports',
                              name: 'SalesReports',
                              component: () => import('@/views/admin/SalesReports.vue')
                        },
                        {
                              path: 'sales/commissions',
                              name: 'Commissions',
                              component: () => import('@/views/admin/Commissions.vue')
                        },
                        // 4️⃣ Users & Customers
                        {
                              path: 'customers',
                              name: 'CustomerManagement',
                              component: () => import('@/views/admin/Customers.vue')
                        },
                        {
                              path: 'customers/:id',
                              name: 'CustomerProfile',
                              component: () => import('@/views/admin/CustomerProfile.vue')
                        },
                        {
                              path: 'support',
                              name: 'CustomerSupport',
                              component: () => import('@/views/admin/CustomerSupport.vue')
                        },
                        // 5️⃣ Marketing & Promotions
                        {
                              path: 'marketing',
                              name: 'Marketing',
                              component: () => import('@/views/admin/Marketing.vue')
                        },
                        {
                              path: 'marketing/campaigns',
                              name: 'MarketingCampaigns',
                              component: () => import('@/views/admin/MarketingCampaigns.vue')
                        },
                        {
                              path: 'marketing/discounts',
                              name: 'DiscountCodes',
                              component: () => import('@/views/admin/DiscountCodes.vue')
                        },
                        {
                              path: 'marketing/referrals',
                              name: 'ReferralSystem',
                              component: () => import('@/views/admin/ReferralSystem.vue')
                        },
                        // 6️⃣ Analytics & Reports
                        {
                              path: 'analytics',
                              name: 'Analytics',
                              component: () => import('@/views/admin/Analytics.vue')
                        },
                        {
                              path: 'reports',
                              name: 'Reports',
                              component: () => import('@/views/admin/Reports.vue')
                        },
                        // 7️⃣ Security & Permissions
                        {
                              path: 'users',
                              name: 'UserManagement',
                              component: () => import('@/views/admin/Users.vue')
                        },
                        {
                              path: 'security',
                              name: 'Security',
                              component: () => import('@/views/admin/Security.vue')
                        },
                        {
                              path: 'activity-logs',
                              name: 'ActivityLogs',
                              component: () => import('@/views/admin/ActivityLogs.vue')
                        },
                        // 8️⃣ Integrations & Settings
                        {
                              path: 'integrations',
                              name: 'Integrations',
                              component: () => import('@/views/admin/Integrations.vue')
                        },
                        {
                              path: 'venues',
                              name: 'VenueManagement',
                              component: () => import('@/views/admin/Venues.vue')
                        },
                        {
                              path: 'settings',
                              name: 'Settings',
                              component: () => import('@/views/admin/Settings.vue')
                        }
                  ]
            },
            // Catch all 404
            {
                  path: '/:pathMatch(.*)*',
                  name: 'NotFound',
                  component: () => import('@/views/NotFound.vue')
            }
      ]
})

// Navigation guards
router.beforeEach(async (to, _from, next) => {
      const authStore = useAuthStore()

      console.log('Navigating to:', to.path, 'Auth status:', authStore.isAuthenticated)

      const requiresAuth = to.meta.requiresAuth
      const redirectIfAuth = to.meta.redirectIfAuth

      // Redirect if authenticated and trying to access auth pages
      if (redirectIfAuth && authStore.isAuthenticated) {
            console.log('Redirecting authenticated user from auth page to admin')
            next('/admin')
            return
      }

      // Check if route requires authentication
      if (requiresAuth && !authStore.isAuthenticated) {
            console.log('Route requires auth, redirecting to login')
            next('/login')
            return
      }

      console.log('Navigation allowed to:', to.path)
      next()
})

export default router

