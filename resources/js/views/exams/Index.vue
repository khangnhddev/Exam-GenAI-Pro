<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Professional Certifications
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Take the next step in your career with our industry-recognized certifications
          </p>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="mb-8 bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-gray-700">Search Certifications</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  type="search"
                  id="search"
                  v-model="searchQuery"
                  class="focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                  placeholder="Search by title or topic..."
                >
              </div>
            </div>

            <!-- Topic Filter -->
            <div class="sm:w-48">
              <label for="topic" class="block text-sm font-medium text-gray-700">Topic</label>
              <select
                id="topic"
                v-model="selectedTopic"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md"
              >
                <option value="">All Topics</option>
                <option v-for="topic in uniqueTopics" :key="topic" :value="topic">
                  {{ topic }}
                </option>
              </select>
            </div>

            <!-- Duration Filter -->
            <div class="sm:w-48">
              <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
              <select
                id="duration"
                v-model="selectedDuration"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md"
              >
                <option value="">Any Duration</option>
                <option value="30">&lt; 30 mins</option>
                <option value="60">&lt; 1 hour</option>
                <option value="120">&lt; 2 hours</option>
                <option value="121">&gt; 2 hours</option>
              </select>
            </div>
          </div>

          <!-- Active Filters -->
          <div v-if="hasActiveFilters" class="mt-4 flex items-center space-x-2">
            <span class="text-sm text-gray-700">Active filters:</span>
            <button
              v-if="selectedTopic"
              @click="selectedTopic = ''"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200"
            >
              {{ selectedTopic }}
              <svg class="ml-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Exams Grid -->
      <div v-if="!loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="exam in filteredExams" 
          :key="exam.id" 
          class="group bg-white overflow-hidden rounded-lg border border-gray-200 transition-all duration-200 hover:shadow-lg hover:border-gray-300"
        >
          <!-- Exam Image -->
          <div class="relative h-48">
            <img 
              :src="exam.image_url" 
              :alt="exam.title"
              class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4">
              <h3 class="text-lg font-semibold text-white">{{ exam.title }}</h3>
              <div class="flex items-center mt-1">
                <span class="px-2 py-1 text-xs font-medium bg-white/20 text-white rounded-full backdrop-blur-sm">
                  {{ exam.topics?.[0] }}
                </span>
              </div>
            </div>
          </div>

          <!-- Exam Details -->
          <div class="p-6">
            <p class="text-sm text-gray-600 line-clamp-2">{{ exam.description }}</p>
            
            <!-- Stats Grid -->
            <dl class="mt-4 grid grid-cols-2 gap-4">
              <div class="flex items-center">
                <dt class="text-xs font-medium text-gray-500">Duration</dt>
                <dd class="ml-2 text-sm font-medium text-gray-900">{{ exam.duration }} min</dd>
              </div>
              <div class="flex items-center">
                <dt class="text-xs font-medium text-gray-500">Questions</dt>
                <dd class="ml-2 text-sm font-medium text-gray-900">{{ exam.total_questions }}</dd>
              </div>
            </dl>

            <!-- Footer -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
              <div class="flex items-center text-sm text-gray-500">
                Pass Rate: {{ exam.passing_score }}%
              </div>
              <router-link
                :to="{ name: 'exams.show', params: { id: exam.id }}"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150"
              >
                View Details
                <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const exams = ref([])
const loading = ref(true)
const searchQuery = ref('')
const selectedTopic = ref('')
const selectedDuration = ref('')

// Get unique topics from all exams
const uniqueTopics = computed(() => {
  return [...new Set(exams.value.flatMap(exam => exam.topics || []))]
})

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return searchQuery.value || selectedTopic.value || selectedDuration.value
})

// Filter exams based on search and filters
const filteredExams = computed(() => {
  return exams.value.filter(exam => {
    const matchesSearch = !searchQuery.value || 
      exam.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      exam.topics?.some(topic => topic.toLowerCase().includes(searchQuery.value.toLowerCase()))
    
    const matchesTopic = !selectedTopic.value || 
      exam.topics?.includes(selectedTopic.value)
    
    const matchesDuration = !selectedDuration.value ||
      (selectedDuration.value === '121' ? exam.duration >= 120 : exam.duration <= selectedDuration.value)
    
    return matchesSearch && matchesTopic && matchesDuration
  })
})

onMounted(async () => {
  try {
    const { data } = await axios.get('/exams')
    exams.value = data.data;
    console.log(data.data);
  } catch (error) {
    console.error('Error loading exams:', error)
  } finally {
    loading.value = false
  }
})
</script>