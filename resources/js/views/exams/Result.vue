<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Fixed Header -->
    <div class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center space-x-8">
            <h1 class="text-xl font-semibold text-gray-900">{{ exam?.title }}</h1>
            <div class="flex items-center space-x-6">
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Score:</span>
                <span class="text-sm font-medium text-gray-900">{{ attempt?.score }}%</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Time:</span>
                <span class="text-sm font-medium text-gray-900">{{ formatTime(attempt?.duration || 0) }}</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Status:</span>
                <span :class="[
                  'text-sm font-medium px-2 py-1 rounded-full',
                  isPassing ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                ]">
                  {{ isPassing ? 'PASSED' : 'FAILED' }}
                </span>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              v-if="isPassing"
              @click="downloadCertificate"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700"
            >
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              Certificate
            </button>
            <button 
              @click="router.push({ name: 'exams.show', params: { id: exam?.id }})"
              class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900"
            >
              Exit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pt-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
          <div v-for="(question, index) in questions" 
               :key="question.id" 
               class="bg-white rounded-lg shadow-sm"
          >
            <div class="p-6 space-y-4">
              <!-- Question Header -->
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <span :class="[
                    'inline-flex items-center justify-center w-7 h-7 rounded text-sm font-medium',
                    isAnswerCorrect(question) 
                      ? 'bg-green-100 text-green-700' 
                      : 'bg-red-100 text-red-700'
                  ]">
                    {{ index + 1 }}
                  </span>
                  <h3 class="text-base font-medium text-gray-900">Question {{ index + 1 }}</h3>
                </div>
                <span :class="[
                  'text-sm font-medium px-2.5 py-0.5 rounded-full',
                  isAnswerCorrect(question) 
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
                ]">
                  {{ isAnswerCorrect(question) ? 'Correct' : 'Incorrect' }}
                </span>
              </div>

              <!-- Question Content -->
              <div class="prose prose-sm max-w-none">
                <div class="text-gray-900" v-html="question.question_text"></div>
              </div>

              <!-- Options -->
              <div class="space-y-2">
                <div v-for="option in question.options" 
                     :key="option.id"
                     :class="[
                       'p-3 rounded border transition-colors duration-150',
                       getOptionClass(question, option)
                     ]"
                >
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
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
                    </div>
                    <span class="ml-2">{{ option.option_text }}</span>
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