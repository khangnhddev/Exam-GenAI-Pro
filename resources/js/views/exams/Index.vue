<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="w-full px-8 pb-16 pt-12">
      <div class="flex items-start justify-between max-w-7xl mx-auto">
        <div class="space-y-6 max-w-2xl">
          <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm">
            <svg class="w-5 h-5 text-[#6C2BD9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="text-sm font-medium text-[#6C2BD9] uppercase tracking-wider">AI Pro Assessments</span>
          </div>
          
          <h1 class="text-4xl font-bold text-gray-900 leading-tight">
            Evaluate Your Skills with AI-Powered Assessments
          </h1>
          
          <div class="space-y-4">
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#6C2BD9]/10 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-[#6C2BD9]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <p class="text-gray-600">Take AI Pro assessments to benchmark your coding skills and get detailed feedback</p>
            </div>
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#6C2BD9]/10 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-[#6C2BD9]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <p class="text-gray-600">Identify knowledge gaps and get personalized recommendations for improvement</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
      <div class="flex flex-col sm:flex-row gap-4 bg-white shadow-lg rounded-2xl p-4">
        <div class="flex-1">
          <div class="relative">
            <input 
              type="text"
              v-model="searchQuery"
              class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
              placeholder="Search assessments..."
            >
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
        
        <div class="flex gap-4">
          <select 
            v-model="selectedCategory"
            class="pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
          
          <select 
            v-model="selectedDifficulty"
            class="pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
          >
            <option value="">All Levels</option>
            <option v-for="level in difficulties" :key="level" :value="level">
              {{ level }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Exams Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="exam in filteredExams" 
          :key="exam.id"
          class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300"
        >
          <div class="p-5">
            <!-- Header with Badge -->
            <div class="flex justify-between items-center mb-3">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-800': exam.difficulty === 'Beginner',
                  'bg-yellow-100 text-yellow-800': exam.difficulty === 'Intermediate',
                  'bg-red-100 text-red-800': exam.difficulty === 'Advanced'
                }"
              >
                {{ exam.difficulty }}
              </span>
              <span class="text-xs text-gray-500">{{ exam.category }}</span>
            </div>

            <!-- Title and Description -->
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ exam.title }}</h3>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ exam.description }}</p>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-3 mb-4">
              <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                <div class="text-xs text-gray-500 mb-0.5">Duration</div>
                <div class="text-sm font-medium text-gray-900">{{ exam.duration }}m</div>
              </div>
              <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                <div class="text-xs text-gray-500 mb-0.5">Questions</div>
                <div class="text-sm font-medium text-gray-900">{{ exam.total_questions }}</div>
              </div>
              <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                <div class="text-xs text-gray-500 mb-0.5">Pass Score</div>
                <div class="text-sm font-medium text-gray-900">{{ exam.passing_score }}%</div>
              </div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-end">
              <button 
                @click="startExam(exam)"
                class="py-2 px-6 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] hover:from-[#5B21B6] hover:to-[#7C3ED9] transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2"
              >
                <span>View Detail</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
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