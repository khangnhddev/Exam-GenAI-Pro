<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="exam" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
          <!-- Exam Header -->
          <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="relative h-48">
              <img 
                :src="exam.image_url || '/default-exam-cover.jpg'" 
                :alt="exam.title"
                class="w-full h-full object-cover"
              >
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <h1 class="absolute bottom-4 left-4 text-2xl font-bold text-white">
                {{ exam.title }}
              </h1>
            </div>
            <div class="p-6">
              <p class="text-gray-600">{{ exam.description }}</p>
              
              <!-- Requirements Section -->
              <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Exam Requirements</h3>
                <ul class="space-y-4">
                  <li class="flex items-start">
                    <svg class="h-5 w-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-600">Stable internet connection required</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="h-5 w-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-600">Complete all questions within time limit</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="h-5 w-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-600">Do not refresh or leave the page during exam</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Overview Section -->
          <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Overview</h3>
              
              <div class="prose max-w-none">
                <!-- Key Learning Objectives -->
                <div class="mb-6">
                  <h4 class="text-base font-medium text-gray-900 mb-3">Key Learning Objectives</h4>
                  <ul class="list-disc pl-5 space-y-2">
                    <li class="text-gray-600">Master fundamental concepts and principles in {{ exam.title }}</li>
                    <li class="text-gray-600">Demonstrate practical knowledge application in real-world scenarios</li>
                    <li class="text-gray-600">Gain practical experience through hands-on exercises</li>
                    <li class="text-gray-600">Gain industry-recognized certification upon successful completion</li>
                  </ul>
                </div>

                <!-- Exam Structure -->
                <div class="mb-6">
                  <h4 class="text-base font-medium text-gray-900 mb-3">Exam Structure</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="font-medium text-gray-900 mb-1">Question Format</div>
                      <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Multiple choice questions</li>
                        <li>• Single answer selections</li>
                        <li>• Scenario-based problems</li>
                        <li>• Practical application tasks</li>
                      </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="font-medium text-gray-900 mb-1">Assessment Criteria</div>
                      <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Clear question objectives</li>
                        <li>• Balanced difficulty levels</li>
                        <li>• Comprehensive topic coverage</li>
                        <li>• Fair scoring system</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Preparation Tips -->
                <div class="mb-6">
                  <h4 class="text-base font-medium text-gray-900 mb-3">Preparation Tips</h4>
                  <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                      <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                      <div class="ml-3">
                        <h5 class="text-sm font-medium text-blue-800">Success Guidelines</h5>
                        <div class="mt-2 text-sm text-blue-700">
                          <ul class="list-disc pl-5 space-y-1">
                            <li>Review all topics thoroughly before attempting the exam</li>
                            <li>Practice time management with sample questions</li>
                            <li>Ensure stable internet connection during the exam</li>
                            <li>Read each question carefully before answering</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Resources -->
                <div>
                  <h4 class="text-base font-medium text-gray-900 mb-3">Additional Resources</h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                      <div class="flex items-center">
                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-900">Study Materials</span>
                      </div>
                      <p class="mt-2 text-sm text-gray-600">Access comprehensive study guides and practice materials</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                      <div class="flex items-center">
                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-900">Performance Analytics</span>
                      </div>
                      <p class="mt-2 text-sm text-gray-600">Track your progress and identify areas for improvement</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Attempts History Section -->
          <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Attempts History</h3>
              <div class="overflow-x-auto">
                <table v-if="examAttempts?.length" class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Taken</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="attempt in examAttempts" :key="attempt.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ formatDate(attempt.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          class="text-sm font-medium"
                          :class="attempt.score >= exam.passing_score ? 'text-green-600' : 'text-red-600'"
                        >
                          {{ attempt.score }}%
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="getStatusClass(attempt.status)"
                        >
                          {{ attempt.status }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ formatDuration(attempt.duration) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div v-else class="text-center py-4 text-gray-500">
                  No attempts yet
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Exam Details Card -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Exam Details</h2>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4 mb-8">
              <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-500">Duration</div>
                <div class="mt-1 text-xl font-semibold text-gray-900">
                  {{ exam.duration }} mins
                </div>
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-500">Questions</div>
                <div class="mt-1 text-xl font-semibold text-gray-900">
                  {{ exam.total_questions }}
                </div>
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-500">Passing Score</div>
                <div class="mt-1 text-xl font-semibold text-gray-900">
                  {{ exam.passing_score }}%
                </div>
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-500">Attempts</div>
                <div class="mt-1 text-xl font-semibold text-gray-900">
                  {{ exam.attempts_allowed }}
                </div>
              </div>
            </div>

            <!-- Start Button -->
            <button
              @click="startExam"
              :disabled="loading"
              class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <span v-if="!loading">Start Exam</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Starting...
              </span>
            </button>

            <!-- Additional Info -->
            <div class="mt-6 text-center">
              <p class="text-sm text-gray-500">
                Make sure you're ready before starting the exam
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { format } from 'date-fns'

const route = useRoute()
const router = useRouter()
const exam = ref({
  id: null,
  title: '',
  description: '',
  duration: 0,
  passing_score: 0,
  total_questions: 0,
  attempts_allowed: 0,
  image_url: null
})
const loading = ref(false)
const examAttempts = ref([])

onMounted(async () => {
  try {
    const [examData, attemptsData] = await Promise.all([
      axios.get(`/exams/${route.params.id}`),
      axios.get(`/exams/${route.params.id}/attempts`)
    ])
    exam.value = examData.data.data
    examAttempts.value = attemptsData.data.data
  } catch (error) {
    console.error('Error loading exam data:', error)
  }
})

const startExam = async () => {
  loading.value = true
  try { 
    const { data } = await axios.post(`/exams/${route.params.id}/start`);
    
    router.push({ 
      name: 'exams.attempt', 
      params: { 
        id: route.params.id,
        attemptId: data.attempt_id 
      }
    })
  } catch (error) {
    // Improved error handling
    if (error.response?.status === 403) {
      alert(error.response.data.message || 'Maximum attempts reached')
    } else {
      alert('Error starting exam. Please try again.')
    }
    console.error('Error starting exam:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (date) => {
  return format(new Date(date), 'MMM dd, yyyy HH:mm')
}

const formatDuration = (minutes) => {
  const hours = Math.floor(minutes / 60)
  const remainingMinutes = minutes % 60
  return hours ? `${hours}h ${remainingMinutes}m` : `${remainingMinutes}m`
}

const getStatusClass = (status) => {
  const classes = {
    completed: 'bg-green-100 text-green-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
    expired: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>