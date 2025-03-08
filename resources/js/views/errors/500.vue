<!-- 500 Error Page -->
<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-lg w-full text-center px-4">
      <div class="mb-8">
        <svg class="mx-auto h-24 w-24 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <h1 class="text-4xl font-bold text-gray-900 mb-4">Server Error</h1>
      <p class="text-lg text-gray-600 mb-8">
        Sorry, something went wrong on our servers. We are working to fix the problem.
      </p>
      <button 
        @click="goBack"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
      >
        Go Back
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
  router.back()
}
</script> 