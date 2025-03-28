<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-xl w-full text-center p-8">
      <div class="mb-8">
        <svg class="mx-auto h-24 w-24 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>

      <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-4">
        404 Not Found
      </h1>
      <p class="text-xl text-gray-600 mb-8">
        The page you're looking for doesn't exist.
      </p>

      <div class="flex justify-center gap-4">
        <router-link 
          to="/" 
          class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Back to Home
        </router-link>

        <button 
          @click="$router.go(-1)" 
          class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 rounded-lg text-base font-medium text-indigo-600 bg-white hover:bg-gradient-to-r hover:from-indigo-600 hover:to-purple-600 hover:text-white transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Go Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
// Add any needed composables or logic here
</script>

<style scoped>
</style>