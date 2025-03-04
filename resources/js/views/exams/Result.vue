<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Modern Fixed Header with Gradient -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 fixed top-0 left-0 right-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <div class="flex-1 flex items-center space-x-8">
            <h1 class="text-xl font-semibold text-white">{{ exam?.title }}</h1>
            <div class="hidden md:flex items-center space-x-6">
              <!-- Score Badge -->
              <div class="bg-white/10 rounded-lg px-4 py-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                  <span class="text-sm text-white/80">Score</span>
                  <span class="text-lg font-bold text-white">{{ attempt?.score }}%</span>
                </div>
              </div>
              
              <!-- Time Badge -->
              <div class="bg-white/10 rounded-lg px-4 py-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-sm text-white/80">Time</span>
                  <span class="text-lg font-bold text-white">{{ formatTime(attempt?.duration || 0) }}</span>
                </div>
              </div>

              <!-- Status Badge -->
              <div :class="[
                'rounded-lg px-4 py-2',
                isPassing ? 'bg-green-500' : 'bg-red-500'
              ]">
                <div class="flex items-center space-x-2">
                  <svg v-if="isPassing" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <svg v-else class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-lg font-bold text-white">{{ isPassing ? 'PASSED' : 'FAILED' }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <button 
              v-if="isPassing"
              @click="downloadCertificate"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-white/20 rounded-lg hover:bg-white/10 transition-colors duration-200"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              Certificate
            </button>
            <button 
              @click="router.push({ name: 'exams.show', params: { id: exam?.id }})"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-white/20 rounded-lg hover:bg-white/10 transition-colors duration-200"
            >
              Exit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pt-28 pb-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Result Summary Card -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8">
          <div class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                <div class="text-4xl font-bold text-indigo-600 mb-1">{{ attempt?.score }}%</div>
                <div class="text-sm text-gray-500">Final Score</div>
              </div>
              <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                <div class="text-4xl font-bold text-indigo-600 mb-1">{{ questions.length }}</div>
                <div class="text-sm text-gray-500">Questions</div>
              </div>
              <div class="text-center p-4 bg-white rounded-xl shadow-sm">
                <div class="text-4xl font-bold text-indigo-600 mb-1">
                  {{ questions.filter(q => isAnswerCorrect(q)).length }}
                </div>
                <div class="text-sm text-gray-500">Correct Answers</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Questions Review -->
        <div class="space-y-6">
          <div v-for="(question, index) in questions" 
               :key="question.id" 
               class="bg-white rounded-xl shadow-sm overflow-hidden"
          >
            <div class="p-6 border-b border-gray-100">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <div :class="[
                    'flex items-center justify-center w-10 h-10 rounded-lg text-lg font-semibold',
                    isAnswerCorrect(question) 
                      ? 'bg-green-100 text-green-700' 
                      : 'bg-red-100 text-red-700'
                  ]">
                    {{ index + 1 }}
                  </div>
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Question {{ index + 1 }}</h3>
                    <span :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
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

            <div class="p-6 space-y-4">
              <!-- Question Content -->
              <div class="prose prose-indigo prose-lg max-w-none">
                <div class="text-gray-900" v-html="question.question_text"></div>
              </div>

              <!-- Options -->
              <div class="space-y-3">
                <div v-for="option in question.options" 
                     :key="option.id"
                     :class="[
                       'flex items-start p-4 rounded-lg transition-all duration-200',
                       getOptionClass(question, option)
                     ]"
                >
                  <div class="flex-shrink-0 mt-0.5">
                    <svg v-if="option.is_correct" 
                         class="h-5 w-5 text-green-500" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor"
                    >
                      <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M5 13l4 4L19 7" 
                      />
                    </svg>
                    <svg v-else-if="isOptionSelected(question, option) && !option.is_correct"
                         class="h-5 w-5 text-red-500"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                    >
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </div>
                  <span class="ml-3 text-gray-900">{{ option.option_text }}</span>
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