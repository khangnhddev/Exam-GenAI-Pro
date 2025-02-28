<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <div class="bg-white shadow">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">{{ exam?.title }}</h1>
          </div>
          <div class="flex items-center space-x-8">
            <div class="text-center">
              <div class="text-sm font-medium text-gray-500">Questions</div>
              <div class="text-lg font-bold text-gray-900">{{ currentIndex + 1 }}/{{ questions.length }}</div>
            </div>
            <div class="text-center">
              <div class="text-sm font-medium text-gray-500">Time Remaining</div>
              <div class="text-lg font-bold text-indigo-600">{{ formatTime(timeRemaining) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex h-[calc(100vh-4rem)]">
      <!-- Question Content - Left Side -->
      <div class="flex-1 overflow-y-auto p-6">
        <div v-if="currentQuestion" class="max-w-3xl mx-auto">
          <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
              <h4 class="text-lg font-medium text-gray-900 mb-6">
                {{ currentQuestion.question_text }}
              </h4>
              
              <!-- Options -->
              <div class="space-y-4">
                <div 
                  v-for="option in currentQuestion.options" 
                  :key="option.id"
                  class="relative"
                >
                  <label 
                    class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-150"
                    :class="{
                      'border-indigo-500 bg-indigo-50': isSelected(option.id),
                      'border-gray-200': !isSelected(option.id)
                    }"
                  >
                    <div class="flex items-center h-5">
                      <input
                        :type="currentQuestion.type === 'multiple' ? 'checkbox' : 'radio'"
                        :name="'question-' + currentQuestion.id"
                        :value="option.id"
                        v-model="selectedOptions"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                      />
                    </div>
                    <div class="ml-3 flex-1">
                      <span class="text-sm text-gray-700">{{ option.text }}</span>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-between">
              <button
                @click="previousQuestion"
                :disabled="currentIndex === 0"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                Previous
              </button>
              <button
                v-if="currentIndex < questions.length - 1"
                @click="nextQuestion"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Next
              </button>
              <button
                v-else
                @click="submitExam"
                :disabled="submitting"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
              >
                {{ submitting ? 'Submitting...' : 'Submit Exam' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Question Navigation - Right Side -->
      <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Question Navigator</h3>
          <div class="grid grid-cols-5 gap-2">
            <button
              v-for="(question, index) in questions"
              :key="question.id"
              @click="currentIndex = index"
              class="h-10 w-10 flex items-center justify-center rounded-lg text-sm font-medium"
              :class="{
                'bg-indigo-600 text-white': currentIndex === index,
                'bg-gray-100 text-gray-700 hover:bg-gray-200': currentIndex !== index,
                'ring-2 ring-offset-2 ring-indigo-500': hasAnswer(question.id)
              }"
            >
              {{ index + 1 }}
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
const currentIndex = ref(0)
const timeRemaining = ref(0)
const submitting = ref(false)
const timer = ref(null)

const selectedOptions = ref([]) // Keep this as empty array
const answers = ref({})

const currentQuestion = computed(() => questions.value[currentIndex.value])

onMounted(async () => {
  try {
    const { data } = await axios.get(`/exams/${route.params.id}/attempt/${route.params.attemptId}`)
    exam.value = data.exam
    questions.value = data.questions
    timeRemaining.value = parseInt(data.time_remaining, 10)
    startTimer()
  } catch (error) {
    console.error('Error loading exam:', error)
  }
})

onUnmounted(() => {
  if (timer.value) clearInterval(timer.value)
})

function startTimer() {
  timer.value = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--
    } else {
      clearInterval(timer.value)
      submitExam()
    }
  }, 1000)
}

function formatTime(seconds) {
  if (!seconds && seconds !== 0) return '00:00'
  
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  
  // Format hours if duration is more than 60 minutes
  if (minutes >= 60) {
    const hours = Math.floor(minutes / 60)
    const remainingMinutes = minutes % 60
    return `${hours.toString().padStart(2, '0')}:${remainingMinutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`
  }
  
  return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`
}

// Update the isSelected function to handle both single and multiple choice
function isSelected(optionId) {
  if (!selectedOptions.value) {
    selectedOptions.value = []
  }
  return Array.isArray(selectedOptions.value) 
    ? selectedOptions.value.includes(optionId)
    : selectedOptions.value === optionId
}

// Add this function with the other functions
function hasAnswer(questionId) {
  return !!answers.value[questionId]?.length
}

// Update the saveCurrentAnswers function
function saveCurrentAnswers() {
  if (currentQuestion.value) {
    // Handle both single and multiple choice questions
    const value = currentQuestion.value.type === 'multiple'
      ? selectedOptions.value
      : [selectedOptions.value]
    answers.value[currentQuestion.value.id] = value
  }
}

// Update the loadSavedAnswers function
function loadSavedAnswers() {
  if (currentQuestion.value) {
    const savedAnswer = answers.value[currentQuestion.value.id] || []
    selectedOptions.value = currentQuestion.value.type === 'multiple'
      ? savedAnswer
      : savedAnswer[0] || null
  }
}

function nextQuestion() {
  saveCurrentAnswers()
  if (currentIndex.value < questions.value.length - 1) {
    currentIndex.value++
    loadSavedAnswers()
  }
}

function previousQuestion() {
  saveCurrentAnswers()
  if (currentIndex.value > 0) {
    currentIndex.value--
    loadSavedAnswers()
  }
}

async function submitExam() {
  saveCurrentAnswers()
  submitting.value = true
  
  try {
    await axios.post(`/exams/attempts/${route.params.attemptId}/submit`, {
      answers: answers.value
    })
    console.log('Submit exam successful')
    // router.push({
    //   name: 'exams.review',
    //   params: { id: route.params.id, attemptId: route.params.attemptId }
    // })
  } catch (error) {
    console.error('Error submitting exam:', error)
  } finally {
    submitting.value = false
  }
}
</script>