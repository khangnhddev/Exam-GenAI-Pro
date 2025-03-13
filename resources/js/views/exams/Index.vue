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
            <span class="text-sm font-medium text-[#6C2BD9] uppercase tracking-wider">AI Pro Exams</span>
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
        <!-- Search Input -->
        <div class="flex-1">
          <div class="relative">
            <input 
              type="text"
              v-model="searchQuery"
              class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
              placeholder="Search exams..."
            >
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
        
        <!-- Filters -->
        <div class="flex gap-4">
          <select 
            v-model="selectedCategory"
            class="pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
          >
            <option value="">All Categories</option>
            <option 
              v-for="category in filterOptions.categories" 
              :key="category.value" 
              :value="category.value"
            >
              {{ category.label }} ({{ category.count }})
            </option>
          </select>
          
          <select 
            v-model="selectedDifficulty"
            class="pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6C2BD9] focus:border-[#6C2BD9]"
          >
            <option value="">All Levels</option>
            <option 
              v-for="level in filterOptions.difficulties" 
              :key="level.value" 
              :value="level.value"
            >
              {{ level.label }} ({{ level.count }})
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Exams Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Loading State -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="animate-pulse">
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
            <div class="h-6 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
            <div class="grid grid-cols-3 gap-3 mb-4">
              <div class="h-12 bg-gray-200 rounded"></div>
              <div class="h-12 bg-gray-200 rounded"></div>
              <div class="h-12 bg-gray-200 rounded"></div>
            </div>
            <div class="flex justify-end">
              <div class="h-10 bg-gray-200 rounded w-24"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!loading && exams.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No exams found</h3>
        <p class="mt-1 text-sm text-gray-500">
          Try adjusting your search or filter criteria
        </p>
        <div class="mt-6">
          <button
            @click="() => { selectedCategory = ''; selectedDifficulty = ''; searchQuery = ''; }"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#6C2BD9] hover:bg-[#5B21B6]"
          >
            Clear filters
          </button>
        </div>
      </div>

      <!-- Exams List -->
      <div v-else class="space-y-6">
        <!-- Exams Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="exam in exams" 
            :key="exam.id"
            class="exam-item bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300"
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

        <!-- Load More Button -->
        <div 
          v-if="currentPage < lastPage" 
          class="flex justify-center pt-6"
        >
          <button
            @click="loadMore"
            :disabled="isLoadingMore"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6] hover:from-[#5B21B6] hover:to-[#7C3ED9] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6C2BD9] transition-all duration-300"
          >
            <template v-if="isLoadingMore">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Loading more...
            </template>
            <template v-else>
              <span>Load More</span>
              <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </template>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import examService from '@/services/examService'
import { useToast } from 'vue-toastification'
import { useDebounce } from '@vueuse/core' // Add this import

const router = useRouter()
const toast = useToast()
const searchQuery = ref('')
const debouncedSearch = useDebounce(searchQuery, 500) // 500ms delay
const selectedCategory = ref('')
const selectedDifficulty = ref('')

const exams = ref([])
const filterOptions = ref({
  categories: [],
  difficulties: []
})
const loading = ref(true)
const error = ref(null)
const currentPage = ref(1)
const lastPage = ref(1)
const isLoadingMore = ref(false)

// Fetch all exams with filter options
const fetchExams = async (loadMore = false) => {
  try {
    if (!loadMore) {
      loading.value = true
      currentPage.value = 1
    } else {
      isLoadingMore.value = true
    }
    
    error.value = null
    const response = await examService.getAllExams({
      category: selectedCategory.value,
      difficulty: selectedDifficulty.value,
      search: debouncedSearch.value,
      page: currentPage.value
    })
    
    if (loadMore) {
      exams.value = [...exams.value, ...response.data.exams.data]
    } else {
      exams.value = response.data.exams.data
    }
    
    lastPage.value = response.data.exams.last_page
    filterOptions.value = response.data.filters
  } catch (err) {
    console.error('Error fetching exams:', err)
    error.value = err.response?.data?.message || 
                  'Unable to load exams. Please try again.'
    toast.error(error.value)
    if (!loadMore) exams.value = []
  } finally {
    loading.value = false
    isLoadingMore.value = false
  }
}

// Load more function
const loadMore = async () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++
    await fetchExams(true)
    
    // Scroll to new content
    await nextTick()
    const newItems = document.querySelectorAll('.exam-item')
    const itemToScrollTo = newItems[exams.value.length - 12] // Assuming 12 items per page
    if (itemToScrollTo) {
      itemToScrollTo.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

// Watch for filter changes
watch([debouncedSearch, selectedCategory, selectedDifficulty], () => {
  currentPage.value = 1
  fetchExams()
}, { immediate: true })

// Start an exam
const startExam = (exam) => {
  router.push({ name: 'exams.show', params: { id: exam.id }})
}

onMounted(() => {
  fetchExams()
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