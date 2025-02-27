<template>
  <div class="space-y-6">
    <!-- Enhanced Header with Search and Sort -->
    <div class="border-b pb-5">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">My Learning</h1>
          <p class="mt-2 text-sm text-gray-600">Track your progress and continue learning</p>
        </div>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search your courses..."
              class="w-64 pl-10 pr-4 py-2 border rounded-lg"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
          <select v-model="sortBy" class="border rounded-lg px-4 py-2">
            <option value="recent">Recently Accessed</option>
            <option value="progress">Progress</option>
            <option value="name">Course Name</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Enhanced Progress Overview with Icons -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-indigo-100">
            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">In Progress</h3>
            <p class="mt-2 text-3xl font-bold text-indigo-600">{{ stats.inProgress }}</p>
            <p class="mt-1 text-sm text-gray-500">Courses started</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">Completed</h3>
            <p class="mt-2 text-3xl font-bold text-green-600">{{ stats.completed }}</p>
            <p class="mt-1 text-sm text-gray-500">Courses finished</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">Total Time</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ stats.totalHours }}h</p>
            <p class="mt-1 text-sm text-gray-500">Hours of learning</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-purple-100">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">Streak</h3>
            <p class="mt-2 text-3xl font-bold text-purple-600">{{ stats.streak || 0 }}</p>
            <p class="mt-1 text-sm text-gray-500">Days in a row</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Course List with Loading State -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-6">
        <div class="sm:flex sm:items-center justify-between">
          <div class="sm:flex-auto">
            <h2 class="text-xl font-semibold text-gray-900">Enrolled Courses</h2>
            <p class="mt-2 text-sm text-gray-500">
              {{ filteredCourses.length }} courses found
            </p>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-500">View:</span>
              <button 
                @click="viewType = 'grid'"
                :class="{ 'text-indigo-600': viewType === 'grid' }"
                class="p-2 hover:text-indigo-600"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </button>
              <button 
                @click="viewType = 'list'"
                :class="{ 'text-indigo-600': viewType === 'list' }"
                class="p-2 hover:text-indigo-600"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
            <select v-model="filter" class="rounded-md border-gray-300 text-sm">
              <option value="all">All Courses</option>
              <option value="in-progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="mt-6 flex justify-center">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredCourses.length === 0" class="mt-6 text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ filter === 'all' ? 'Start learning by enrolling in a course.' : 'Try changing your filters.' }}
          </p>
        </div>

        <!-- Course Grid/List -->
        <component 
          :is="viewType === 'grid' ? 'div' : 'ul'"
          :class="[
            'mt-6',
            viewType === 'grid' ? 'grid gap-6 sm:grid-cols-2 lg:grid-cols-3' : 'divide-y divide-gray-200'
          ]"
        >
          <component
            :is="viewType === 'grid' ? 'div' : 'li'"
            v-for="course in filteredCourses" 
            :key="course.id" 
            :class="[
              'relative',
              viewType === 'grid' ? 'bg-white border rounded-lg shadow-sm overflow-hidden' : 'py-4'
            ]"
          >
            <!-- Course Content -->
            <div :class="{ 'p-6': viewType === 'grid' }">
              <h3 class="text-lg font-medium text-gray-900">{{ course.title }}</h3>
              <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ course.description }}</p>
              
              <div class="mt-4">
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="h-2 rounded-full transition-all duration-500"
                    :class="course.progress === 100 ? 'bg-green-600' : 'bg-indigo-600'"
                    :style="{ width: `${course.progress}%` }"
                  ></div>
                </div>
                
                <div class="mt-4 flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-900">
                      {{ course.progress }}% complete
                    </span>
                    <span class="text-sm text-gray-500">
                      {{ course.completed_lessons }}/{{ course.total_lessons }} lessons
                    </span>
                  </div>
                  <router-link
                    :to="{ name: 'courses.show', params: { id: course.id }}"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
                  >
                    Continue
                  </router-link>
                </div>
              </div>
            </div>
          </component>
        </component>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const enrolledCourses = ref([])
const filter = ref('all')
const searchQuery = ref('')
const sortBy = ref('recent')
const viewType = ref('grid')
const loading = ref(true)
const stats = ref({
  inProgress: 0,
  completed: 0,
  totalHours: 0,
  streak: 0
})

const filteredCourses = computed(() => {
  let courses = enrolledCourses.value

  // Filter by status
  if (filter.value === 'completed') {
    courses = courses.filter(course => course.progress === 100)
  } else if (filter.value === 'in-progress') {
    courses = courses.filter(course => course.progress < 100)
  }

  // Search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    courses = courses.filter(course => 
      course.title.toLowerCase().includes(query) ||
      course.description.toLowerCase().includes(query)
    )
  }

  // Sort
  return courses.sort((a, b) => {
    switch (sortBy.value) {
      case 'progress':
        return b.progress - a.progress
      case 'name':
        return a.title.localeCompare(b.title)
      case 'recent':
      default:
        return new Date(b.last_accessed) - new Date(a.last_accessed)
    }
  })
})

const fetchEnrolledCourses = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/v1/my-learning')
    enrolledCourses.value = response.data.courses
    stats.value = response.data.stats
  } catch (error) {
    console.error('Error fetching enrolled courses:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchEnrolledCourses()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>