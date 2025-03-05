<template>
  <!-- Hero Section - Full Width -->
  <div class="bg-gradient-to-br from-indigo-600 to-purple-600 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <!-- Left Content -->
      <div class="text-white p-12 lg:pl-24">
        <h1 class="text-4xl font-bold mb-4">{{ exam.title }}</h1>
        <p class="text-lg text-indigo-100 mb-8">{{ exam.description }}</p>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
          <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-2xl font-bold">{{ exam.total_questions }}</div>
            <div class="text-sm text-indigo-200">Questions</div>
          </div>
          <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-2xl font-bold">{{ exam.duration }}m</div>
            <div class="text-sm text-indigo-200">Duration</div>
          </div>
          <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-2xl font-bold">{{ exam.passing_score }}%</div>
            <div class="text-sm text-indigo-200">Pass Score</div>
          </div>
          <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
            <div class="text-2xl font-bold">{{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</div>
            <div class="text-sm text-indigo-200">Attempts</div>
          </div>
        </div>

        <!-- Action Button -->
        <button v-if="canAttempt" @click="startExam"
          class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white shadow-sm transition-all duration-200">
          Start Assessment
          <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>
      </div>

      <!-- Right Content - Certificate Preview -->
      <div class="hidden lg:flex items-center justify-center p-12 lg:pr-24">
        <div class="relative w-full max-w-md">
          <!-- Certificate Design -->
          <div class="bg-white rounded-lg shadow-xl p-8 transform rotate-3">
            <div class="border-4 border-indigo-200 border-dashed rounded-lg p-6">
              <div class="text-center">
                <div class="text-xl text-indigo-600 font-semibold mb-2">Certificate of Completion</div>
                <div class="text-sm text-gray-600">Professional Assessment</div>
                <div class="mt-6">
                  <svg class="w-24 h-24 mx-auto text-indigo-600 opacity-25" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
          <!-- Decorative Elements -->
          <div
            class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-purple-400 to-indigo-400 rounded-full opacity-50 blur-xl">
          </div>
          <div
            class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full opacity-50 blur-xl">
          </div>
        </div>
      </div>
    </div>
  </div>

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

          <!-- Add this section right after the title and description, before Assessment Overview -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Assessment Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Assessment</h3>
                  <p class="text-sm text-gray-500">Test your knowledge</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Questions:</span>
                  <span class="font-medium">{{ exam.total_questions }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Time Limit:</span>
                  <span class="font-medium">{{ exam.duration }} mins</span>
                </div>
              </div>
            </div>

            <!-- Certificate Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Certificate</h3>
                  <p class="text-sm text-gray-500">Upon completion</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Passing Score:</span>
                  <span class="font-medium">{{ exam.passing_score }}%</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Attempts:</span>
                  <span class="font-medium">{{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</span>
                </div>
              </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Your Status</h3>
                  <p class="text-sm text-gray-500">Current progress</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Best Score:</span>
                  <span class="font-medium">{{ bestScore }}%</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span :class="[
                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                    hasPassed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]">
                    {{ hasPassed ? 'PASSED' : 'NOT COMPLETED' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

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
                      <button @click="router.push(`/exams/attempts/${attempt.id}/review`)"
                        class="text-indigo-600 hover:text-indigo-900">
                        Review
                      </button>
                      <button v-if="attempt.score >= exam.passing_score" @click="downloadCertificate(attempt.id)"
                        class="ml-4 text-indigo-600 hover:text-indigo-900">
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
            <!-- Take Assessment Card -->
            <div v-if="!examResults" class="bg-white rounded-xl shadow-sm p-8">
              <div class="flex flex-col items-center text-center">
                <div
                  class="relative flex flex-col items-center justify-center p-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                  <!-- Certificate Design -->
                  <div class="relative w-48 h-48 mb-6">
                    <!-- Certificate Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-md">
                      <!-- Certificate Content (Blurred when locked) -->
                      <div :class="[
                        'absolute inset-0 flex flex-col items-center justify-center p-4 transition-all duration-300',
                        !hasPassed ? 'filter blur-sm' : ''
                      ]">
                        <svg class="w-16 h-16 text-indigo-600 mb-2" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11">
                          </path>
                        </svg>
                        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Certificate of</div>
                        <div class="text-sm font-bold text-gray-900 mt-1">Completion</div>
                      </div>

                      <!-- Lock Overlay -->
                      <div v-if="!hasPassed"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-lg">
                        <svg class="w-12 h-12 text-white opacity-75" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z">
                          </path>
                        </svg>
                      </div>
                    </div>
                  </div>

                  <!-- Badge Title -->
                  <h3 class="text-lg font-bold text-gray-900 mb-2">
                    {{ hasPassed ? 'Certificate Unlocked!' : 'Locked Certificate' }}
                  </h3>

                  <!-- Badge Description -->
                  <p class="text-sm text-gray-600 text-center mb-6">
                    {{ hasPassed
                      ? 'Congratulations! You can now claim your professional certificate.'
                      : 'Complete this assessment to unlock your professional certificate.'
                    }}
                  </p>

                  <!-- Requirements List -->
                  <div class="w-full space-y-3 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span>Pass with {{ exam.passing_score }}% or higher</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span>Complete within {{ exam.duration }} minutes</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span>{{ exam.total_questions }} questions to answer</span>
                    </div>
                  </div>

                  <!-- Progress Indicator -->
                  <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full"
                      :style="`width: ${(userAttempts.value?.length || 0) / exam.attempts_allowed * 100}%`"></div>
                  </div>
                  <p class="text-sm text-gray-500 mb-6">
                    {{ userAttempts.value?.length || 0 }}/{{ exam.attempts_allowed }} attempts used
                  </p>

                  <!-- Start Button (already exists in your template, styling updated) -->
                  <button v-if="canAttempt" @click="hasPassed ? downloadCertificate() : startExam()"
                    class="w-full px-6 py-3 text-lg font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ hasPassed ? 'Claim Certificate' : (hasAttempted ? 'Retake Assessment' : 'Start Assessment') }}
                  </button>
                  <div v-else
                    class="w-full px-6 py-3 text-lg font-medium text-center text-gray-500 bg-gray-100 rounded-lg cursor-not-allowed">
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

  <ExamResultModal :is-open="!!examResults" :results="examResults" @close="closeResults"
    @download-certificate="downloadCertificate" />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import ExamResultModal from '@/Components/ExamResultModal.vue'

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
  if (!exam.value || !userAttempts.value) return false
  return userAttempts.value.length < exam.value.attempts_allowed
})

const hasAttempted = computed(() => {
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
    loading.value = true
    const [examResponse, historyResponse] = await Promise.all([
      axios.get(`/exams/${route.params.id}`),
      axios.get(`/exams/${route.params.id}/attempts`)
    ])

    exam.value = examResponse.data.data;
    attemptHistory.value = historyResponse.data.data;
    userAttempts.value = examResponse.data.data.attempts; // Add this line
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

const averageScore = computed(() => {
  if (!userAttempts.value.length) return 0
  const totalScore = userAttempts.value.reduce((acc, attempt) => acc + attempt.score, 0)
  return (totalScore / userAttempts.value.length).toFixed(2)
})

const bestScore = computed(() => {
  if (!userAttempts.value.length) return 0
  return Math.max(...userAttempts.value.map(attempt => attempt.score))
})

const currentSkillLevel = computed(() => {
  if (!userAttempts.value.length) return { label: 'N/A', class: '' }
  const latestAttempt = userAttempts.value[userAttempts.value.length - 1]
  return getSkillLevel(latestAttempt.score)
})

const progressPercentage = computed(() => {
  if (!userAttempts.value.length) return 0
  const latestAttempt = userAttempts.value[userAttempts.value.length - 1]
  return (latestAttempt.score / 100) * 100
})

const closeResults = () => {
  examResults.value = null
  // Remove attemptId from URL
  router.replace({ query: {} })
}

onMounted(() => {
  loadExam()
  fetchExamResults()
})
</script>