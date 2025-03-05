<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <div class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-10">
      <div class="px-4 sm:px-6 lg:px-8 mx-auto flex items-center justify-between h-14">
        <h1 class="text-lg font-medium text-gray-900 truncate">{{ exam?.title }}</h1>

        <div class="flex items-center space-x-4">
          <!-- Timer -->
          <div class="flex items-center bg-indigo-50 rounded-md px-3 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500 mr-1" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-indigo-600">{{ formatTime(timeLeft) }}</span>
          </div>

          <!-- Exit Button -->
          <button @click="confirmExit" class="text-sm font-medium text-gray-600 hover:text-gray-900">
            Exit
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pt-14 pb-20">
      <div class="max-w-3xl mx-auto px-4 py-8">
        <div v-if="currentQuestion" class="space-y-8">
          <!-- Question Text -->
          <div class="prose max-w-none">
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              Question {{ currentQuestionIndex + 1 }}
              <span class="text-sm text-gray-500 ml-2">
                ({{ currentQuestion.type === 'multiple' ? 'Multiple Choice' : 'Single Choice' }})
              </span>
            </h3>
            <div class="text-gray-700" v-html="currentQuestion.question_text"></div>
          </div>

          <!-- Options -->
          <div class="space-y-3">
            <template v-if="currentQuestion.type === 'single'">
              <label v-for="(option, index) in currentQuestion.options" :key="index"
                class="flex items-start p-4 rounded-lg border transition-colors duration-150 cursor-pointer" :class="[
                  isOptionSelected(index)
                    ? 'border-indigo-500 bg-indigo-50'
                    : 'border-gray-200 hover:bg-gray-50'
                ]">
                <input type="radio" :name="'question-' + currentQuestion.id" :value="index"
                  v-model="selectedAnswers[currentQuestion.id]"
                  class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" />
                <span class="ml-3 text-gray-700" v-html="option.option_text"></span>
              </label>
            </template>

            <template v-else>
              <label v-for="(option, index) in currentQuestion.options" :key="index"
                class="flex items-start p-4 rounded-lg border transition-colors duration-150 cursor-pointer" :class="[
                  isOptionSelected(index)
                    ? 'border-indigo-500 bg-indigo-50'
                    : 'border-gray-200 hover:bg-gray-50'
                ]">
                <input 
                  type="checkbox" 
                  :value="index"
                  :checked="isOptionSelected(index)"
                  @change="handleMultipleChoice(index)"
                  class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                />
                <span class="ml-3 text-gray-700" v-html="option.option_text"></span>
              </label>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
      <div class="max-w-3xl mx-auto flex justify-between items-center">
        <button 
          @click="previousQuestion" 
          :disabled="currentQuestionIndex === 0"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium rounded-md border border-gray-300 shadow-sm" 
          :class="[
            currentQuestionIndex === 0
              ? 'bg-gray-50 text-gray-400 cursor-not-allowed'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          ]"
        >
          <ArrowLeftIcon class="h-5 w-5 mr-2" />
          Previous
        </button>

        <button 
          v-if="currentQuestionIndex < questions.length - 1" 
          @click="nextQuestion"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm"
        >
          Next
          <ArrowRightIcon class="h-5 w-5 ml-2" />
        </button>
        <button 
          v-else 
          @click="submitExam"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm"
        >
          Submit
        </button>
      </div>
    </div>

    <!-- Add this modal component right before closing the main template div -->
    <div v-if="showExitModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Exit Exam
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to exit? Your progress will be saved, but the exam timer will continue
                    running.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="exit"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
              Exit
            </button>
            <button @click="showExitModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <AssessmentResultModal 
      :is-open="showResults" 
      :results="examResults"
      @close="handleModalClose"
      @download-certificate="handleDownloadCertificate"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import AssessmentResultModal from '../../Components/AssessmentResultModal.vue'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const exam = ref(null)
const questions = ref([])
const currentQuestionIndex = ref(0)
const selectedAnswers = ref({})
const timeLeft = ref(0)
const showExitModal = ref(false)
const showResults = ref(false)
const examResults = ref(null)
let timer = null

const currentQuestion = computed(() => questions.value[currentQuestionIndex.value])

const isOptionSelected = (optionIndex) => {
  const answer = selectedAnswers.value[currentQuestion.value.id]
  if (currentQuestion.value.type === 'multiple') {
    return Array.isArray(answer) && answer.includes(optionIndex)
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
    
    // Initialize answers for all questions
    questions.value.forEach(question => {
      initializeAnswer(question)
    })
    
    timeLeft.value = exam.value.duration * 60
    startTimer()
  } catch (error) {
    console.error('Error loading exam:', error)
  }
}

const initializeAnswer = (question) => {
  if (!selectedAnswers.value[question.id]) {
    selectedAnswers.value[question.id] = question.type === 'multiple' ? [] : null
  }
}

/**
 * Next question
 *  
 * @returns {void}
 * 
 */
const nextQuestion = () => {
  console.log('Next question');
  if (currentQuestionIndex.value < questions.value.length - 1) {
    currentQuestionIndex.value++
  }
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--
  }
}

/**
 * Submit exam
 *  
 * @returns {void}
 */
const submitExam = async () => {
  try {
    // Format answers to match backend requirements
    const formattedAnswers = Object.keys(selectedAnswers.value).reduce((acc, questionId) => {
      const question = questions.value.find(q => q.id === parseInt(questionId))
      const answer = selectedAnswers.value[questionId]

      // Get the actual option IDs based on selected indices
      if (question.type === 'single') {
        // For single choice, get the option ID at the selected index
        if (answer !== undefined && question.options[answer]) {
          acc[questionId] = question.options[answer].id
        }
      } else {
        // For multiple choice, map selected indices to option IDs
        if (Array.isArray(answer) && answer.length > 0) {
          acc[questionId] = answer.map(index => question.options[index].id)
        }
      }

      return acc
    }, {})

    const response = await axios.post(`/exams/attempts/${route.params.attemptId}/submit`, {
      answers: formattedAnswers,
      attempt_id: route.params.attemptId
    })

    if (timer) {
      clearInterval(timer)
    }

    handleExamSubmission(response.data);

  } catch (error) {
    console.error('Error submitting exam:', error)
    toast.error('Failed to submit exam. Please try again.')
  }
}

const confirmExit = () => {
  showExitModal.value = true
}

const exit = () => {
  router.push({ name: 'exams.index' })
}

const handleExamSubmission = (results) => {
  examResults.value = results
  showResults.value = true
}

const handleDownloadCertificate = async () => {
  // Your download logic here
}

const handleModalClose = () => {
  showResults.value = false
  // Navigate back to exam details page
  router.push({
    name: 'exams.show',
    params: { id: exam.value.id }
  })
}

const handleMultipleChoice = (optionIndex) => {
  const questionId = currentQuestion.value.id
  if (!selectedAnswers.value[questionId]) {
    selectedAnswers.value[questionId] = []
  }
  
  const index = selectedAnswers.value[questionId].indexOf(optionIndex)
  if (index === -1) {
    selectedAnswers.value[questionId].push(optionIndex)
  } else {
    selectedAnswers.value[questionId].splice(index, 1)
  }
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