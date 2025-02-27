<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            ExamPro
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Choose from our available certification exams
          </p>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Exams Grid -->
      <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="exam in exams" 
          :key="exam.id" 
          class="bg-white overflow-hidden shadow rounded-lg"
        >
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900">{{ exam.title }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ exam.description }}</p>
            <dl class="mt-4 grid grid-cols-2 gap-4">
              <div>
                <dt class="text-xs font-medium text-gray-500">Duration</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ exam.duration }} min</dd>
              </div>
              <div>
                <dt class="text-xs font-medium text-gray-500">Questions</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ exam.total_questions }}</dd>
              </div>
            </dl>
          </div>
          <div class="bg-gray-50 px-4 py-4 sm:px-6">
            <router-link
              :to="{ name: 'exams.show', params: { id: exam.id }}"
              class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
            >
              View Details
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const exams = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get('/exams')
    exams.value = data.data
  } catch (error) {
    console.error('Error loading exams:', error)
  } finally {
    loading.value = false
  }
})
</script>