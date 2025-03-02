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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider">ASSESSMENTS</span>
          </div>

          <!-- Exam Title and Description -->
          <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ exam.title }}</h1>
          <p class="text-lg text-gray-600 mb-8">{{ exam.description }}</p>

          <!-- Assessment Overview -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Assessment Overview</h2>
            <p class="text-gray-600">This skill assessment covers the concepts of {{ exam.title }} and will help you identify gaps in your understanding of major concepts. It will further help you gauge your current level of understanding for leveling up your skills.</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Duration: {{ exam.duration }} minutes</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Passing Score: {{ exam.passing_score }}%</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                  <span class="ml-2">Attempts Allowed: {{ exam.attempts_allowed }}</span>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Total Questions: {{ exam.total_questions }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <span class="ml-2">Your Attempts: {{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Previous Attempts -->
          <div  class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Attempts History</h2>
            <div class="overflow-x-auto">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">DATE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SCORE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SKILLSCORE</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="attempt in userAttempts" :key="attempt.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                      {{ attempt.attempt_number }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ new Date(attempt.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                      }) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ attempt.score }}%
                    </td>
                    <td class="px-6 py-4">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        Beginner
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-8">
            <!-- Take Assessment Card -->
            <div class="bg-white rounded-xl shadow-sm p-8">
              <div class="flex flex-col items-center text-center">
                <!-- Take Assessment Button -->
                <button v-if="canAttempt" @click="startExam"
                  class="w-full px-6 py-3 text-lg font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-8">
                  Take Assessment
                </button>
                <div v-else
                  class="w-full px-6 py-3 text-lg font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed mb-8">
                  No Attempts Remaining
                </div>

                <!-- Time and Attempts Info -->
                <div class="flex items-center gap-8 mb-8">
                  <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none">
                      <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <div class="flex flex-col items-start">
                      <span class="text-2xl font-bold text-gray-900">{{ exam.duration }}</span>
                      <span class="text-sm text-gray-500">minutes</span>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none">
                      <path d="M4 4V9H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M20 4V9H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M4 20V15H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M20 20V15H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex flex-col items-start">
                      <span class="text-2xl font-bold text-gray-900">{{ exam.attempts_allowed }}</span>
                      <span class="text-sm text-gray-500">attempts</span>
                    </div>
                  </div>
                </div>

                <!-- Locked Badge -->
                <div class="relative w-48 h-48">
                  <!-- Circle Background -->
                  <svg class="w-full h-full" viewBox="0 0 192 192">
                    <!-- Outer Circle -->
                    <circle cx="96" cy="96" r="88" fill="none" stroke="#E5E7EB" stroke-width="2"/>
                    <!-- Inner Circle -->
                    <circle cx="96" cy="96" r="84" fill="none" stroke="#D1D5DB" stroke-width="1"/>
                    <!-- Top Text -->
                    <path id="topCircle" d="M96,30 A50,50 0 0,1 96,30" fill="none"/>
                    <text>
                      <textPath href="#topCircle" startOffset="50%" text-anchor="middle">
                        <tspan class="text-xs font-medium uppercase tracking-wider" fill="#6B7280">AI Pro Badge</tspan>
                      </textPath>
                    </text>
                    <!-- Bottom Text -->
                    <path id="bottomCircle" d="M96,162 A50,50 0 0,0 96,162" fill="none"/>
                    <text>
                      <textPath href="#bottomCircle" startOffset="50%" text-anchor="middle">
                        <tspan class="text-xs font-medium uppercase tracking-wider" fill="#6B7280">Assessment</tspan>
                      </textPath>
                    </text>
                  </svg>
                  
                  <!-- Center Content -->
                  <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <!-- Lock Icon -->
                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="2" 
                        d="M7 11V8a5 5 0 0110 0v3m-5 4v2"/>
                      <rect width="14" height="10" x="5" y="11" stroke="currentColor" 
                        stroke-width="2" rx="2"/>
                    </svg>
                    <!-- LOCKED text -->
                    <span class="mt-2 text-xl font-semibold text-gray-400 tracking-[0.2em]">LOCKED</span>
                    <!-- Browser Icon -->
                    <svg class="w-8 h-8 mt-2 text-gray-400" viewBox="0 0 24 24" fill="none">
                      <rect x="3" y="6" width="18" height="12" stroke="currentColor" stroke-width="2" rx="2"/>
                      <path d="M3 10H21" stroke="currentColor" stroke-width="2"/>
                    </svg>
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
import axios from 'axios'

const route = useRoute()
const router = useRouter()
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

const canAttempt = computed(() => {
  return true;
  if (!exam.value || !userAttempts.value) return false
  return userAttempts.value.length < exam.value.attempts_allowed
})

const hasPassed = computed(() => {
  if (!userAttempts.value?.length) return false
  return userAttempts.value.some(attempt => attempt.score >= exam.value.passing_score)
})

const loadExam = async () => {
  try {
    console.log('Load exam');
    loading.value = true
    const response = await axios.get(`/exams/${route.params.id}`);
    console.log('Response', response.data.data);
    exam.value = response.data.data;
    userAttempts.value = response.data.attempts
  } catch (error) {
    console.error('Error loading exam:', error)
  } finally {
    loading.value = false
  }
}

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

const downloadCertificate = async () => {
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

onMounted(() => {
  loadExam()
})
</script>