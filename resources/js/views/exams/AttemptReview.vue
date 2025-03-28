<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center min-h-[400px]">
        <div class="w-24 h-24 border-4 border-[#6C2BD9] border-l-transparent rounded-full animate-spin"></div>
        <p class="mt-4 text-sm text-gray-500">Loading review data...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="max-w-md mx-auto">
          <ExclamationCircleIcon class="mx-auto h-12 w-12 text-red-400" />
          <h3 class="mt-2 text-lg font-medium text-gray-900">{{ error }}</h3>
          <p class="mt-1 text-sm text-gray-500">Unable to load the review data. Please try again.</p>
          <div class="mt-6">
            <button 
              @click="fetchReview"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#6C2BD9] hover:bg-[#5B21B6] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6C2BD9]"
            >
              <ArrowPathIcon class="w-4 h-4 mr-2" />
              Try Again
            </button>
          </div>
        </div>
      </div>

      <template v-else-if="reviewData">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <button 
                @click="goBackToExam"
                class="inline-flex items-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors"
              >
                <ArrowLeftIcon class="w-4 h-4 mr-1.5" />
                Back to Exam
              </button>
              <div>
                <h1 class="text-2xl font-bold text-gray-900">
                  Exam Review
                </h1>
                <div class="mt-1 flex items-center gap-3 text-sm text-gray-500">
                  <span>{{ reviewData?.details?.exam_title }}</span>
                  <span>•</span>
                  <span>Attempt #{{ reviewData?.details?.attempt_number }}</span>
                  <span>•</span>
                  <span>{{ new Date(reviewData?.details?.completed_at).toLocaleString() }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Meta Info -->
          <div class="mt-6 bg-white rounded-xl border border-gray-200 p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">Category</dt>
                <dd class="mt-1 text-sm text-gray-900 capitalize">
                  {{ reviewData?.details?.category?.replace(/_/g, ' ') }}
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Difficulty</dt>
                <dd class="mt-1 text-sm text-gray-900 capitalize">
                  {{ reviewData?.details?.difficulty }}
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Time Taken</dt>
                <dd class="mt-1 text-sm text-gray-900">
                  {{ Math.round(reviewData?.details?.time_taken) }} minutes
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Skill Level</dt>
                <dd class="mt-1 text-sm text-gray-900">
                  {{ reviewData?.details?.skill_level }}
                </dd>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <!-- Score Card -->
          <div v-if="reviewData?.score != null" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <ChartBarIcon class="h-6 w-6 text-[#6C2BD9]" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Score</dt>
                    <dd>
                      <div class="text-lg font-semibold text-gray-900">
                        {{ reviewData.score }}%
                      </div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm">
                <span class="font-medium" :class="reviewData?.passed ? 'text-green-700' : 'text-red-700'">
                  {{ reviewData?.passed ? 'PASSED' : 'FAILED' }}
                </span>
                <span class="text-gray-500">
                  (Required: {{ reviewData?.passing_score }}%)
                </span>
              </div>
            </div>
          </div>

          <!-- Similar cards for Questions, Time, and Skill Level -->
          <!-- Questions Card -->
          <div v-if="reviewData?.summary?.correct_answers != null" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <QuestionMarkCircleIcon class="h-6 w-6 text-[#6C2BD9]" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Questions</dt>
                    <dd>
                      <div class="text-lg font-semibold text-gray-900">
                        {{ reviewData.summary.correct_answers }}/{{ reviewData.details.total_questions }}
                      </div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm text-gray-500">
                Correct Answers
              </div>
            </div>
          </div>

          <!-- ... Other stat cards ... -->
        </div>

        <!-- Questions Review -->
        <div v-if="reviewData?.questions?.length" class="mt-3 space-y-6">
          <div 
            v-for="(question, index) in reviewData.questions" 
            :key="question.id"
            class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
          >
            <!-- Question Header -->
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span class="font-medium text-gray-900">Question {{ index + 1 }}</span>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="question.is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ question.is_correct ? 'Correct' : 'Incorrect' }}
                  </span>
                  <span class="text-sm text-gray-500">
                    {{ formatQuestionType(question.type) }}
                  </span>
                </div>
                <span class="text-sm font-medium">
                  Score: {{ question.score_earned }}/{{ question.points }}
                </span>
              </div>
            </div>

            <!-- Question Content -->
            <div class="p-6 space-y-6">
              <!-- Question Text -->
              <div class="prose prose-sm max-w-none">
                <div v-html="question.question_text"></div>
              </div>

              <!-- Your Answer -->
              <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Your Answer:</h4>
                
                <!-- Single/Multiple Choice -->
                <template v-if="['single_choice', 'multiple_choice'].includes(question.type)">
                  <div class="space-y-2">
                    <div 
                      v-for="option in question.options" 
                      :key="option.id"
                      class="flex items-center p-3 rounded-md"
                      :class="getOptionClass(option, question)"
                    >
                      <component 
                        :is="question.type === 'single_choice' ? 'RadioIcon' : 'CheckIcon'"
                        class="h-5 w-5 mr-3"
                        :class="getIconClass(option, question)"
                      />
                      <span v-html="option.text"></span>
                    </div>
                  </div>
                </template>

                <!-- Prompt Answer -->
                <template v-else-if="question.type === 'prompt'">
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="prose prose-sm max-w-none">
                      <div v-html="formatMarkdown(question.user_answer)"></div>
                    </div>
                  </div>

                  <!-- AI Feedback -->
                  <div v-if="question.ai_feedback" class="mt-4 bg-purple-50 rounded-lg p-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">AI Feedback:</h5>
                    <p class="text-sm text-gray-600">{{ question.ai_feedback }}</p>
                  </div>
                </template>
              </div>

              <!-- Correct Answer (if wrong) -->
              <!-- <div v-if="!question.is_correct" class="mt-4 pt-4 border-t">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Correct Answer:</h4>
                <div class="bg-green-50 rounded-lg p-4">
                  <div class="prose prose-sm max-w-none">
                    <div v-html="question.correct_answer"></div>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </template>

      <!-- Add a fallback for when reviewData is null but not loading/error -->
      <template v-else>
        <div class="text-center py-12">
          <div class="max-w-md mx-auto">
            <ExclamationCircleIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-lg font-medium text-gray-900">No review data available</h3>
            <p class="mt-1 text-sm text-gray-500">The review data could not be loaded.</p>
            <div class="mt-6">
              <button 
                @click="fetchReview"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#6C2BD9] hover:bg-[#5B21B6]"
              >
                <ArrowPathIcon class="w-4 h-4 mr-2" />
                Reload
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { ArrowLeftIcon, ExclamationCircleIcon, CheckIcon, RadioIcon, ArrowPathIcon, AcademicCapIcon, ChartBarIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/outline'
import { marked } from 'marked'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const error = ref(null)
const reviewData = ref(null)

const fetchReview = async () => {
  try {
    loading.value = true
    error.value = null
    const { data } = await axios.get(`/exams/${route.params.examId}/attempts/${route.params.attemptId}/review`)
    reviewData.value = data
  } catch (err) {
    console.error('Error fetching review:', err)
    error.value = err.response?.data?.message || 'Failed to load review'
  } finally {
    loading.value = false
  }
}

const getOptionClass = (option, question) => {
  if (option.was_selected && option.is_correct) return 'bg-green-50 border border-green-200'
  if (option.was_selected && !option.is_correct) return 'bg-red-50 border border-red-200'
  if (!option.was_selected && option.is_correct) return 'bg-green-50 border border-green-200'
  return 'bg-gray-50 border border-gray-200'
}

const getIconClass = (option, question) => {
  if (option.was_selected && option.is_correct) return 'text-green-600'
  if (option.was_selected && !option.is_correct) return 'text-red-600'
  if (!option.was_selected && option.is_correct) return 'text-green-600'
  return 'text-gray-400'
}

const formatMarkdown = (text) => {
  return text ? marked(text) : ''
}

const formatQuestionType = (type) => {
  const types = {
    'single_choice': 'Single Choice',
    'multiple_choice': 'Multiple Choice',
    'prompt': 'Written Response'
  }
  return types[type] || type
}

const goBackToExam = () => {
  router.push({
    name: 'exams.show',
    params: { id: route.params.examId }
  })
}

onMounted(() => {
  fetchReview()
})

// Add immediate data fetch when component is created
fetchReview()
</script>

<style scoped>
.prose :deep(pre) {
  background-color: #f8fafc;
  border-radius: 0.5rem;
  padding: 1rem;
  margin: 1rem 0;
  overflow-x: auto;
}

.prose :deep(code) {
  background-color: #f1f5f9;
  padding: 0.2em 0.4em;
  border-radius: 0.25rem;
  font-size: 0.875em;
}

.prose :deep(p) {
  margin: 1rem 0;
  line-height: 1.6;
}
</style>