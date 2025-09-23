<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { HomeIcon } from '@heroicons/vue/24/outline'
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="text-center">
        <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-primary-100">
          <span class="text-4xl font-bold text-primary-600">404</span>
        </div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Page not found
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Sorry, we couldn't find the page you're looking for.
        </p>
        <div class="mt-6">
          <RouterLink
            to="/"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700"
          >
            <HomeIcon class="mr-2 h-4 w-4" />
            Go back home
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

