<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="w-full bg-gradient-to-br from-indigo-600 to-purple-600 px-8 pb-16 pt-12 rounded-xl">
      <div class="flex items-start justify-between">
        <div class="space-y-4 max-w-2xl">
          <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="text-sm font-medium text-white uppercase tracking-wider">EXAMS</span>
          </div>
          <h1 class="text-3xl font-bold text-white leading-tight">Identify your skill gaps</h1>
          <div class="space-y-3">
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-base text-white">Take an AI Pro Assessment to benchmark your current coding skills</p>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-base text-white">Understand your strengths and weaknesses, to improve your skills</p>
            </div>
          </div>
        </div>

        <!-- Custom Target SVG -->
        <div class="w-80">
          <svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d)">
              <rect x="50" y="30" width="300" height="200" rx="16" fill="white" fill-opacity="0.1"/>
              <circle cx="200" cy="130" r="80" stroke="white" stroke-opacity="0.2" stroke-width="2"/>
              <circle cx="200" cy="130" r="60" stroke="white" stroke-opacity="0.4" stroke-width="2"/>
              <circle cx="200" cy="130" r="40" stroke="white" stroke-opacity="0.6" stroke-width="2"/>
              <circle cx="200" cy="130" r="20" fill="white" fill-opacity="0.8"/>
              <path d="M280 130L200 130" stroke="white" stroke-opacity="0.4" stroke-width="2"/>
              <path d="M200 50L200 130" stroke="white" stroke-opacity="0.4" stroke-width="2"/>
              <circle cx="200" cy="130" r="5" fill="white"/>
            </g>
            <defs>
              <filter id="filter0_d" x="0" y="0" width="400" height="300" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                <feOffset dy="4"/>
                <feGaussianBlur stdDeviation="8"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
              </filter>
            </defs>
          </svg>
        </div>
      </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
      <div class="flex flex-col sm:flex-row gap-4 bg-white shadow-lg p-3 rounded-xl">
        <div class="flex-1">
          <div class="relative">
            <input type="text"
              class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Search exams..." v-model="searchQuery">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
        <div class="flex gap-4">
          <select v-model="selectedCategory"
            class="block w-full pl-3 pr-10 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
          <select v-model="selectedDifficulty"
            class="block w-full pl-3 pr-10 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
            <option value="">All Difficulties</option>
            <option v-for="level in difficulties" :key="level" :value="level">
              {{ level }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="min-h-[400px] flex items-center justify-center">
        <div class="text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Oops! Something went wrong</h3>
          <p class="text-gray-500 mb-6">{{ error }}</p>
          <button @click="retryFetch" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Try Again
          </button>
        </div>
      </div>

      <!-- Exams Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="exam in filteredExams" :key="exam.id"
          class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
          <!-- Card Header -->
          <div class="p-6 pb-4">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ exam.title }}</h3>
            <p class="text-gray-600 mb-6">{{ exam.description }}</p>
            
            <!-- Exam Info -->
            <div class="flex items-center gap-6 mb-6">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-600">{{ exam.duration }} mins</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-600">{{ exam.total_questions }} Questions</span>
              </div>
            </div>

            <!-- Pass Score -->
            <div class="flex items-center gap-2 mb-6">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-gray-600">Pass Score: {{ exam.passing_score }}%</span>
            </div>

            <!-- Start Button -->
            <button @click="startExam(exam)"
              class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
              View Detail
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredExams.length === 0" class="text-center py-12 bg-white rounded-xl border border-gray-200">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No exams found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import examService from '@/services/examService'
import { useToast } from 'vue-toastification'

const router = useRouter()
const toast = useToast()
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedDifficulty = ref('')

const categories = ['Programming', 'Machine Learning', 'Data Science', 'DevOps', 'Cloud Computing']
const difficulties = ['Beginner', 'Intermediate', 'Advanced']

const exams = ref([])
const loading = ref(true)
const error = ref(null)

// Fetch all exams
const fetchExams = async () => {
  try {
    loading.value = true
    error.value = null
    const response = await examService.getAllExams();
    
    if (!response.data || !response.data.data) {
      throw new Error('Invalid response format from server');
    }
    
    exams.value = response.data.data;
  } catch (err) {
    console.error('Error fetching exams:', err);
    error.value = err.response?.data?.message || 
                  'Unable to load exams. Please check your connection and try again.';
    toast.error(error.value);
    exams.value = [];
  } finally {
    loading.value = false;
  }
}

// Start an exam
const startExam = async (exam) => {
  try {
    router.push({ name: 'exams.show', params: { id: exam.id }})
  } catch (err) {
    toast.error('Failed to start exam. Please try again.')
  }
}

const retryFetch = () => {
  fetchExams();
}

// Fetch exams when component mounts
onMounted(() => {
  fetchExams()
})

const filteredExams = computed(() => {
  return exams.value.filter(exam => {
    const matchesSearch = exam.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      exam.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || exam.category === selectedCategory.value
    const matchesDifficulty = !selectedDifficulty.value || exam.difficulty === selectedDifficulty.value

    return matchesSearch && matchesCategory && matchesDifficulty
  })
})
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>