<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section with Gradient -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 fixed top-0 left-0 right-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Title -->
          <div class="flex-1 flex items-center space-x-8">
            <h1 class="text-xl font-bold text-white">{{ exam?.title }}</h1>
            
            <!-- Stats Badges -->
            <div class="hidden md:flex items-center space-x-4">
              <!-- Score -->
              <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl px-4 py-2 flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" 
                    />
                  </svg>
                </div>
                <div>
                  <div class="text-xs text-white text-opacity-80">Score</div>
                  <div class="text-xl font-bold text-white">{{ attempt?.score }}%</div>
                </div>
              </div>

              <!-- Time -->
              <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl px-4 py-2 flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" 
                    />
                  </svg>
                </div>
                <div>
                  <div class="text-xs text-white text-opacity-80">Time</div>
                  <div class="text-xl font-bold text-white">{{ formatTime(attempt?.duration || 0) }}</div>
                </div>
              </div>

              <!-- Status -->
              <div :class="[
                'rounded-xl px-4 py-2 flex items-center',
                isPassing ? 'bg-green-500' : 'bg-red-500'
              ]">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                  <svg v-if="isPassing" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" 
                    />
                  </svg>
                  <svg v-else class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" 
                    />
                  </svg>
                </div>
                <div>
                  <div class="text-xs text-white text-opacity-80">Status</div>
                  <div class="text-xl font-bold text-white">{{ isPassing ? 'PASSED' : 'FAILED' }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center space-x-4">
            <button 
              v-if="isPassing"
              @click="downloadCertificate"
              class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white border border-white border-opacity-20 rounded-xl hover:bg-white hover:bg-opacity-10 transition-all duration-200"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" 
                />
              </svg>
              Download Certificate
            </button>
            <button 
              @click="router.push({ name: 'exams.show', params: { id: exam?.id }})"
              class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white border border-white border-opacity-20 rounded-xl hover:bg-white hover:bg-opacity-10 transition-all duration-200"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Exit Review
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pt-28 pb-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Result Summary Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-200">
          <div class="p-8 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <!-- Score Card -->
              <div class="bg-white rounded-xl shadow-sm p-6 text-center transform hover:scale-[1.02] transition-all duration-200">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-xl mb-4">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" 
                    />
                  </svg>
                </div>
                <div class="text-4xl font-bold text-indigo-600 mb-1">{{ attempt?.score }}%</div>
                <div class="text-sm font-medium text-gray-500">Final Score</div>
              </div>

              <!-- Questions Count -->
              <div class="bg-white rounded-xl shadow-sm p-6 text-center transform hover:scale-[1.02] transition-all duration-200">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl mb-4">
                  <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" 
                    />
                  </svg>
                </div>
                <div class="text-4xl font-bold text-purple-600 mb-1">{{ questions.length }}</div>
                <div class="text-sm font-medium text-gray-500">Total Questions</div>
              </div>

              <!-- Correct Answers -->
              <div class="bg-white rounded-xl shadow-sm p-6 text-center transform hover:scale-[1.02] transition-all duration-200">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl mb-4">
                  <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" 
                    />
                  </svg>
                </div>
                <div class="text-4xl font-bold text-green-600 mb-1">
                  {{ questions.filter(q => isAnswerCorrect(q)).length }}
                </div>
                <div class="text-sm font-medium text-gray-500">Correct Answers</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Questions Review -->
        <div class="space-y-6">
          <div v-for="(question, index) in questions" 
               :key="question.id" 
               class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md"
          >
            <!-- Question Header -->
            <div class="p-6 border-b border-gray-100">
              <div class="flex items-center gap-4">
                <!-- Question Number with Status -->
                <div :class="[
                  'flex items-center justify-center w-12 h-12 rounded-xl text-lg font-bold',
                  isAnswerCorrect(question) 
                    ? 'bg-green-100 text-green-700' 
                    : 'bg-red-100 text-red-700'
                ]">
                  {{ index + 1 }}
                </div>

                <div class="flex-1">
                  <!-- Question Text -->
                  <div class="prose prose-lg max-w-none mb-4">
                    <div class="text-gray-900 font-medium" v-html="question.question_text"></div>
                  </div>

                  <!-- Status Badge -->
                  <div class="flex items-center gap-2">
                    <span :class="[
                      'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                      isAnswerCorrect(question) 
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'
                    ]">
                      {{ isAnswerCorrect(question) ? 'Correct' : 'Incorrect' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Answer Options -->
            <div class="p-6 bg-gray-50">
              <div class="space-y-3">
                <div v-for="option in question.options" 
                     :key="option.id"
                     :class="[
                       'relative flex items-start p-4 rounded-xl transition-all duration-200',
                       getOptionClass(question, option)
                     ]"
                >
                  <!-- Option Icon -->
                  <div class="absolute right-4 top-1/2 -translate-y-1/2">
                    <svg v-if="option.is_correct" 
                         class="h-6 w-6 text-green-500" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor"
                    >
                      <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" 
                      />
                    </svg>
                    <svg v-else-if="isOptionSelected(question, option) && !option.is_correct"
                         class="h-6 w-6 text-red-500"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                    >
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                  </div>

                  <!-- Option Text -->
                  <div class="flex-1 pr-8">
                    <p class="text-gray-900">{{ option.option_text }}</p>
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
import { useToast } from 'vue-toastification'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const exam = ref(null)
const attempt = ref(null)
const questions = ref([])

const isPassing = computed(() => {
  if (!attempt.value || !exam.value) return false
  return attempt.value.score >= exam.value.passing_score
})

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60)
  const hours = Math.floor(minutes / 60)
  const remainingMinutes = minutes % 60
  
  if (hours > 0) {
    return `${hours}h ${remainingMinutes}m`
  }
  return `${minutes}m`
}

function isAnswerCorrect(question) {
  const userAnswer = attempt.value.answers[question.id]
  const correctAnswers = question.options
    .filter(opt => opt.is_correct)
    .map(opt => opt.id)

  if (Array.isArray(userAnswer)) {
    return JSON.stringify(userAnswer.sort()) === JSON.stringify(correctAnswers.sort())
  }
  return userAnswer === correctAnswers[0]
}

function getOptionClass(question, option) {
  const userAnswers = attempt.value.answers[question.id]
  const isSelected = Array.isArray(userAnswers)
    ? userAnswers.includes(option.id)
    : userAnswers === option.id

  if (option.is_correct) {
    return 'bg-green-50 text-green-700'
  }
  if (isSelected && !option.is_correct) {
    return 'bg-red-50 text-red-700'
  }
  return 'bg-gray-50 text-gray-700'
}

async function downloadCertificate() {
  try {
    const response = await axios.get(
      `/api/v1/exams/attempts/${route.params.attemptId}/certificate`,
      { responseType: 'blob' }
    )
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `certificate-${route.params.attemptId}.pdf`)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    toast.error('Failed to download certificate')
  }
}

onMounted(async () => {
  try {
    const response = await axios.get(`/api/v1/exams/attempts/${route.params.attemptId}/result`)
    exam.value = response.data.exam
    attempt.value = response.data.attempt
    questions.value = response.data.questions
  } catch (error) {
    toast.error('Failed to load exam results')
  }
})
</script>