<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ exam?.title }}</h1>
        <div class="flex items-center space-x-4">
          <!-- Timer -->
          <div class="flex items-center bg-white rounded-lg px-4 py-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-lg font-semibold">{{ formatTime(timeLeft) }}</span>
          </div>
          <!-- Exit Button -->
          <button 
            @click="confirmExit"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            EXIT
          </button>
        </div>
      </div>

      <!-- Question Card -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">
            QUESTION {{ currentQuestionIndex + 1 }}
          </h2>
          <span class="text-sm text-gray-500">Time Left: {{ formatTime(timeLeft) }}</span>
        </div>

        <div v-if="currentQuestion" class="space-y-6">
          <!-- Question Text -->
          <div class="prose max-w-none">
            <p class="text-lg text-gray-700">{{ currentQuestion.question_text }}</p>
          </div>

          <!-- Options -->
          <div class="space-y-4">
            <template v-if="currentQuestion.type === 'single'">
              <label 
                v-for="(option, index) in currentQuestion.options" 
                :key="index"
                class="relative flex items-center p-4 rounded-lg border cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500"
                :class="{
                  'border-gray-200': !isOptionSelected(index),
                  'border-indigo-500 bg-indigo-50': isOptionSelected(index)
                }"
              >
                <input
                  type="radio"
                  :name="'question-' + currentQuestion.id"
                  :value="index"
                  v-model="selectedAnswers[currentQuestion.id]"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                />
                <span class="ml-3 flex flex-col">
                  <span class="text-sm font-medium text-gray-900">{{ option }}</span>
                </span>
              </label>
            </template>

            <template v-else-if="currentQuestion.type === 'multiple'">
              <label 
                v-for="(option, index) in currentQuestion.options" 
                :key="index"
                class="relative flex items-center p-4 rounded-lg border cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500"
                :class="{
                  'border-gray-200': !isOptionSelected(index),
                  'border-indigo-500 bg-indigo-50': isOptionSelected(index)
                }"
              >
                <input
                  type="checkbox"
                  :value="index"
                  v-model="selectedAnswers[currentQuestion.id]"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <span class="ml-3 flex flex-col">
                  <span class="text-sm font-medium text-gray-900">{{ option }}</span>
                </span>
              </label>
            </template>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="flex justify-between items-center">
        <button
          @click="previousQuestion"
          :disabled="currentQuestionIndex === 0"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Previous
        </button>

        <div class="flex space-x-4">
          <button
            v-if="currentQuestionIndex < questions.length - 1"
            @click="nextQuestion"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Next
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>

          <button
            v-else
            @click="submitExam"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Submit Exam
          </button>
        </div>
      </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div v-if="showExitModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Exit Exam
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Are you sure you want to exit? Your progress will be saved, but the exam timer will continue running.
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="exit"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Exit
            </button>
            <button
              type="button"
              @click="showExitModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const exam = ref(null)
const questions = ref([])
const currentQuestionIndex = ref(0)
const selectedAnswers = ref({})
const timeLeft = ref(0)
const showExitModal = ref(false)
let timer = null

const currentQuestion = computed(() => questions.value[currentQuestionIndex.value])

const isOptionSelected = (optionIndex) => {
  const answer = selectedAnswers.value[currentQuestion.value.id]
  if (Array.isArray(answer)) {
    return answer.includes(optionIndex)
  }
  return answer === optionIndex
}

const formatTime = (seconds) => {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const remainingSeconds = seconds % 60
  return `${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(remainingSeconds).padStart(2, '0')}`
}

const startTimer = () => {
  timer = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      clearInterval(timer)
      submitExam()
    }
  }, 1000)
}

const loadExam = async () => {
  try {
    const response = await axios.get(`/exams/${route.params.id}/attempt/${route.params.attemptId || ''}`)
    exam.value = response.data.exam
    questions.value = response.data.questions
    timeLeft.value = exam.value.duration * 60 // Convert minutes to seconds
    startTimer()
  } catch (error) {
    console.error('Error loading exam:', error)
  }
}

const nextQuestion = () => {
  if (currentQuestionIndex.value < questions.value.length - 1) {
    currentQuestionIndex.value++
  }
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--
  }
}

const submitExam = async () => {
  try {
    await axios.post(`/api/exams/${route.params.id}/submit`, {
      answers: selectedAnswers.value,
      attempt_id: route.params.attemptId
    })
    router.push({ name: 'exams.result', params: { id: route.params.id } })
  } catch (error) {
    console.error('Error submitting exam:', error)
  }
}

const confirmExit = () => {
  showExitModal.value = true
}

const exit = () => {
  router.push({ name: 'exams.index' })
}

onMounted(() => {
  loadExam()
})

onUnmounted(() => {
  if (timer) {
    clearInterval(timer)
  }
})
</script>