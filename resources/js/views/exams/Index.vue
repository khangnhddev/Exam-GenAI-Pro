<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
          <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div class="flex items-center gap-1">
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider">AI</span>
            <span class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent uppercase tracking-wider">Pro</span>
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider ml-1">Certification</span>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Professional Certifications</h1>
        <p class="mt-2 text-lg text-gray-600">Validate your expertise with AI Pro's industry-recognized certifications</p>
      </div>

      <!-- Search and Filter Section -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <input type="text" 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Search exams..."
                v-model="searchQuery">
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
              class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
            <select v-model="selectedDifficulty" 
              class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">All Difficulties</option>
              <option v-for="level in difficulties" :key="level" :value="level">
                {{ level }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ error }}</p>
          </div>
        </div>
      </div>

      <!-- Exams Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="exam in filteredExams" :key="exam.id" 
          class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
          <!-- Exam Header -->
          <div class="relative h-48 bg-gradient-to-r from-indigo-500 to-purple-600 p-6 flex items-center justify-center">
            <div class="text-center">
              <h3 class="text-2xl font-bold text-white mb-2">{{ exam.title }}</h3>
              <div class="flex items-center justify-center gap-4">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-1 text-white">{{ exam.duration }} mins</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-1 text-white">{{ exam.questions }} Questions</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Exam Content -->
          <div class="p-6">
            <div class="flex items-center gap-2 mb-3">
              <span :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                exam.difficulty === 'Beginner' ? 'bg-green-100 text-green-800' :
                exam.difficulty === 'Intermediate' ? 'bg-yellow-100 text-yellow-800' :
                'bg-red-100 text-red-800'
              ]">
                {{ exam.difficulty }}
              </span>
              <span class="text-sm text-gray-500">{{ exam.category }}</span>
            </div>
            
            <p class="text-gray-600 mb-4 line-clamp-2">{{ exam.description }}</p>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-500">Pass Score: {{ exam.passing_score }}%</span>
              </div>
              <button @click="startExam(exam)" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Start Exam
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredExams.length === 0" class="text-center py-12">
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
    const response = await examService.getAllExams();
    console.log(response);
    exams.value = response.data.data
  } catch (err) {
    error.value = 'Failed to load exams. Please try again later.'
    toast.error(error.value)
  } finally {
    loading.value = false
  }
}

// Start an exam
const startExam = async (exam) => {
  try {
    router.push({ name: 'exams.show', params: { id: exam.id }})
    // const response = await examService.startExam(examId);
    // toast.success('Exam started successfully!')
    // router.push(`/exams/${examId}/take`)
  } catch (err) {
    toast.error('Failed to start exam. Please try again.')
  }
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