<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Exam Header -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">{{ exam?.title }}</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">Question {{ currentIndex + 1 }} of {{ questions.length }}</p>
            </div>
            <div class="text-right">
              <div class="text-sm font-medium text-gray-500">Time Remaining</div>
              <div class="text-2xl font-bold text-gray-900">{{ formatTime(timeRemaining) }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Question Card -->
      <div v-if="currentQuestion" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h4 class="text-lg font-medium text-gray-900 mb-4">
            {{ currentQuestion.question_text }}
          </h4>
          
          <!-- Options -->
          <div class="space-y-3">
            <div 
              v-for="option in currentQuestion.options" 
              :key="option.id"
              class="relative"
            >
              <label 
                class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50"
                :class="{'border-indigo-500 bg-indigo-50': isSelected(option.id)}"
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
                <div class="ml-3">
                  <span class="text-sm text-gray-700">{{ option.text }}</span>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between">
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