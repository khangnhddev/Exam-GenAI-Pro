<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
      <div class="mb-8">
        <!-- 404 Icon with gradient background -->
        <div class="mx-auto h-24 w-24 p-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600">
          <svg class="h-full w-full text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
            />
          </svg>
        </div>
        <h1 class="mt-6 text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
          404
        </h1>
        <h2 class="mt-2 text-2xl font-semibold text-gray-900">Page not found</h2>
        <p class="mt-2 text-base text-gray-600">Sorry, we couldn't find the page you're looking for.</p>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4 justify-center">
        <button 
          @click="$router.back()" 
          class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors"
        >
          <ChevronLeftIcon class="h-5 w-5 mr-1.5" />
          Go Back
        </button>
        <router-link 
          to="/" 
          class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-colors"
        >
          <HomeIcon class="h-5 w-5 mr-1.5" />
          Go Home
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ChevronLeftIcon, HomeIcon } from '@heroicons/vue/24/outline'
</script>