<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center min-h-[400px]">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- Assessment Type Badge -->
          <div class="flex items-center gap-2 mb-6">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider">ASSESSMENTS</span>
          </div>

          <!-- Exam Title and Description -->
          <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ exam.title }}</h1>
          <p class="text-lg text-gray-600 mb-8">{{ exam.description }}</p>

          <!-- Assessment Overview -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Assessment Overview</h2>
            <p class="text-gray-600">This skill assessment covers the concepts of {{ exam.title }} and will help you
              identify gaps in your understanding of major concepts. It will further help you gauge your current level
              of understanding for leveling up your skills.</p>
          </div>

          <!-- Topics Covered -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Topics Covered</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="(topic, index) in exam.topics" :key="index" class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700">{{ topic }}</span>
              </div>
            </div>
          </div>

          <!-- Exam Details -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Duration: {{ exam.duration }} minutes</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Passing Score: {{ exam.passing_score }}%</span>
                </div>
                <!-- <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                  <span class="ml-2">Attempts Allowed: {{ exam.attempts_allowed }}</span>
                </div> -->
              </div>

              <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Total Questions: {{ exam.total_questions }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <span class="ml-2">Your Attempts: {{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Previous Attempts -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Attempts History</h2>
            <div class="overflow-x-auto">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">DATE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SCORE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SKILL LEVEL</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">STATUS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">ACTIONS</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="!formattedAttempts.length" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-sm text-gray-500 text-center">
                      No attempts yet
                    </td>
                  </tr>
                  <tr v-for="attempt in formattedAttempts" :key="attempt.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                      {{ attempt.attempt_number }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ attempt.formattedDate }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ attempt.score }}%
                    </td>
                    <td class="px-6 py-4">
                      <span :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        attempt.skillLevel.class
                      ]">
                        {{ attempt.skillLevel.label }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        attempt.score >= exam.passing_score
                          ? 'bg-green-100 text-green-800'
                          : 'bg-red-100 text-red-800'
                      ]">
                        {{ attempt.score >= exam.passing_score ? 'PASSED' : 'FAILED' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                      <button 
                        @click="router.push(`/exams/attempts/${attempt.id}/review`)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Review
                      </button>
                      <button 
                        v-if="attempt.score >= exam.passing_score" 
                        @click="downloadCertificate(attempt.id)"
                        class="ml-4 text-indigo-600 hover:text-indigo-900"
                      >
                        Certificate
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-8 space-y-6">
            <!-- Results Section -->
            <div v-if="examResults" class="bg-white rounded-xl shadow-sm overflow-hidden">
              <div class="p-6 bg-gradient-to-br from-indigo-500 to-purple-600">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-medium text-white">Exam Results</h3>
                  <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white bg-opacity-20">
                    <svg v-if="examResults.passed" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="p-6">
                <div class="space-y-4">
                  <!-- Score -->
                  <div class="text-center">
                    <div class="text-3xl font-bold" :class="examResults.passed ? 'text-green-600' : 'text-red-600'">
                      {{ examResults.score }}%
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Your Score</div>
                  </div>

                  <div class="border-t border-gray-200 pt-4">
                    <!-- Status Badge -->
                    <div class="flex justify-center">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                        :class="examResults.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                        {{ examResults.passed ? 'PASSED' : 'FAILED' }}
                      </span>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 space-y-2">
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Passing Score:</span>
                        <span class="font-medium text-gray-900">{{ examResults.passing_score }}%</span>
                      </div>
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Duration:</span>
                        <span class="font-medium text-gray-900">{{ examResults.duration }} mins</span>
                      </div>
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Completed:</span>
                        <span class="font-medium text-gray-900">{{ new
                          Date(examResults.completed_at).toLocaleDateString() }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="mt-6 space-y-3">
                    <button v-if="examResults.passed" @click="downloadCertificate"
                      class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                      Download Certificate
                    </button>
                    <button @click="router.push('/exams')"
                      class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                      Back to Exams
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Take Assessment Card -->
            <div v-if="!examResults" class="bg-white rounded-xl shadow-sm p-8">
              <div class="flex flex-col items-center text-center">
                <!-- Take Assessment Button -->
                <button v-if="canAttempt" @click="startExam"
                  class="w-full px-6 py-3 text-lg font-medium text-white bg-indigo-600 hover:bg-indigo- rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-8">
                  {{ hasAttempted ? 'Retake Assessment' : 'Take Assessment' }}
                </button>
                <div v-else
                  class="w-full px-6 py-3 text-lg font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed mb-8">
                  No Attempts Remaining
                </div>

                <!-- Time and Attempts Info -->
                <div class="flex items-center gap-8 mb-8">
                  <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none">
                      <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                      <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <div class="flex flex-col items-start">
                      <span class="text-2xl font-bold text-gray-900">{{ exam.duration }}</span>
                      <span class="text-sm text-gray-500">minutes</span>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none">
                      <path d="M4 4V9H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path d="M20 4V9H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path d="M4 20V15H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path d="M20 20V15H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                    <div class="flex flex-col items-start">
                      <span class="text-2xl font-bold text-gray-900">{{ exam.attempts_allowed }}</span>
                      <span class="text-sm text-gray-500">attempts</span>
                    </div>
                  </div>
                </div>

                <!-- Locked Badge -->
                <div class="relative flex flex-col items-center justify-center p-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                  <!-- Badge Design -->
                  <div class="relative w-32 h-32 mb-6">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full opacity-10 animate-pulse"></div>
                    <div class="absolute inset-2 bg-white rounded-full shadow-inner"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                      <svg class="w-16 h-16 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                      </svg>
                    </div>
                  </div>

                  <!-- Badge Title -->
                  <h3 class="text-lg font-bold text-gray-900 mb-2">Professional Assessment</h3>
                  
                  <!-- Badge Description -->
                  <p class="text-sm text-gray-600 text-center mb-6">
                    Complete this assessment to earn your professional certification badge
                  </p>

                  <!-- Requirements List -->
                  <div class="w-full space-y-3 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      <span>Pass with {{ exam.passing_score }}% or higher</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      <span>Complete within {{ exam.duration }} minutes</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      <span>{{ exam.total_questions }} questions to answer</span>
                    </div>
                  </div>

                  <!-- Progress Indicator -->
                  <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                    <div 
                      class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" 
                      :style="`width: ${(userAttempts.value?.length || 0) / exam.attempts_allowed * 100}%`"
                    ></div>
                  </div>
                  <p class="text-sm text-gray-500 mb-6">
                    {{ userAttempts.value?.length || 0 }}/{{ exam.attempts_allowed }} attempts used
                  </p>

                  <!-- Start Button (already exists in your template, styling updated) -->
                  <button 
                    v-if="canAttempt" 
                    @click="startExam"
                    class="w-full px-6 py-3 text-lg font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ hasAttempted ? 'Retake Assessment' : 'Start Assessment' }}
                  </button>
                  <div 
                    v-else
                    class="w-full px-6 py-3 text-lg font-medium text-center text-gray-500 bg-gray-100 rounded-lg cursor-not-allowed"
                  >
                    No Attempts Remaining
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const loading = ref(true)
const exam = ref({
  title: '',
  description: '',
  duration: 0,
  passing_score: 0,
  attempts_allowed: 0,
  total_questions: 0,
  topics: [
    'Basics of Classes',
    'Inheritance',
    'Composition',
    'Polymorphism',
    'Aggregation',
    'Association'
  ]
})
const userAttempts = ref([])
const attemptHistory = ref([])
const examResults = ref(null)

const canAttempt = computed(() => {
  return true;
  if (!exam.value || !userAttempts.value) return false
  return userAttempts.value.length < exam.value.attempts_allowed
})

const hasAttempted = computed(() => {
  return true;
  return userAttempts.value?.length > 0
})

const hasPassed = computed(() => {
  if (!userAttempts.value?.length) return false
  return userAttempts.value.some(attempt => attempt.score >= exam.value.passing_score)
})

const formattedAttempts = computed(() => {
  return attemptHistory.value.map(attempt => ({
    ...attempt,
    skillLevel: getSkillLevel(attempt.score),
    formattedDate: new Date(attempt.created_at).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }))
})

function getSkillLevel(score) {
  if (score >= 90) return { label: 'Expert', class: 'bg-green-100 text-green-800' }
  if (score >= 75) return { label: 'Advanced', class: 'bg-blue-100 text-blue-800' }
  if (score >= 60) return { label: 'Intermediate', class: 'bg-yellow-100 text-yellow-800' }
  return { label: 'Beginner', class: 'bg-orange-100 text-orange-800' }
}

/**
 * Load exam
 * 
 * @returns {void}
 */
const loadExam = async () => {
  try {
    console.log('Load exam');
    loading.value = true
    const [examResponse, historyResponse] = await Promise.all([
      axios.get(`/exams/${route.params.id}`),
      axios.get(`/exams/${route.params.id}/attempts`)
    ])

    exam.value = examResponse.data.data
    attemptHistory.value = historyResponse.data.data
  } catch (error) {
    console.error('Error loading exam:', error)
    toast.error('Failed to load exam data')
  } finally {
    loading.value = false
  }
}

/**
 * Start exam
 * 
 * @returns {void}
 */
const startExam = async () => {
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
    console.error('Error starting exam:', error)
  }
}

const downloadCertificate = async (attemptId) => {
  try {
    const response = await axios.get(`/exams/${route.params.id}/certificate`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `${exam.value.title}_Certificate.pdf`)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}

/**
 * Fetch exam results
 * 
 * @returns {void}
 */
const fetchExamResults = async () => {
  try {
    if (route.query.attemptId) {
      const response = await axios.get(`/exams/attempts/${route.query.attemptId}/results`)
      examResults.value = response.data
    }
  } catch (error) {
    console.error('Error fetching results:', error)
  }
}

onMounted(() => {
  loadExam()
  fetchExamResults()
})
</script>