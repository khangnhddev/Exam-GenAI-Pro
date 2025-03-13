<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold text-gray-900">Exam Review</h1>
          <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">
              Score: <span class="font-medium">{{ attempt.score }}%</span>
            </div>
            <div class="text-sm text-gray-500">
              Time Taken: <span class="font-medium">{{ formatDuration(attempt.duration_taken) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Questions Review -->
      <div class="space-y-8">
        <div v-for="(question, index) in attempt.questions" :key="question.id" 
          class="bg-white shadow rounded-lg overflow-hidden">
          <!-- Question Header -->
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-gray-900">
                Question {{ index + 1 }}
                <span class="ml-2 text-sm text-gray-500">
                  ({{ formatQuestionType(question.type) }})
                </span>
              </h3>
              <QuestionStatusBadge :is-correct="question.is_correct" />
            </div>
          </div>

          <!-- Question Content -->
          <div class="px-6 py-4">
            <div class="prose max-w-none">
              <div v-html="question.question_text"></div>
            </div>

            <!-- Different answer displays based on question type -->
            <div class="mt-4">
              <!-- Prompt Type Answer -->
              <template v-if="question.type === 'prompt'">
                <div class="space-y-4">
                  <!-- User's Answer -->
                  <div class="bg-gray-50 rounded-md p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Your Answer:</h4>
                    <div class="prose prose-sm max-w-none">
                      <div v-html="formatMarkdown(question.user_answer)"></div>
                    </div>
                  </div>

                  <!-- AI Evaluation -->
                  <div class="border rounded-md p-4">
                    <div class="flex items-center justify-between mb-2">
                      <h4 class="text-sm font-medium text-gray-700">AI Evaluation</h4>
                      <span 
                        :class="[
                          'px-2 py-1 text-sm rounded-full',
                          question.ai_score >= 70 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-red-100 text-red-800'
                        ]"
                      >
                        Score: {{ question.ai_score }}/100
                      </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ question.ai_feedback }}</p>
                  </div>
                </div>
              </template>

              <!-- Single/Multiple Choice Answer -->
              <template v-else>
                <div class="space-y-3">
                  <div 
                    v-for="option in question.options" 
                    :key="option.id"
                    class="flex items-start p-3 rounded-md"
                    :class="getOptionClass(option, question)"
                  >
                    <div class="flex-shrink-0">
                      <component 
                        :is="question.type === 'single_choice' ? 'RadioIcon' : 'CheckIcon'"
                        class="h-5 w-5"
                        :class="getIconClass(option, question)"
                      />
                    </div>
                    <span class="ml-3" v-html="option.option_text"></span>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="mt-8 flex justify-between">
        <button
          @click="router.push({ name: 'exams.show', params: { id: attempt.exam_id }})"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          <ArrowLeftIcon class="h-5 w-5 mr-2" />
          Back to Exam
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { ArrowLeftIcon, RadioIcon, CheckIcon } from '@heroicons/vue/24/outline'
import { marked } from 'marked'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const attempt = ref(null)

const formatQuestionType = (type) => {
  const types = {
    'single_choice': 'Single Choice',
    'multiple_choice': 'Multiple Choice',
    'prompt': 'Written Response'
  }
  return types[type] || type
}

const formatDuration = (seconds) => {
  const minutes = Math.floor(seconds / 60)
  return `${minutes} minutes`
}

const formatMarkdown = (text) => {
  return marked(text || '')
}

const getOptionClass = (option, question) => {
  const isSelected = question.user_answers?.includes(option.id)
  const isCorrect = option.is_correct

  if (isSelected && isCorrect) {
    return 'bg-green-50 border border-green-200'
  }
  if (isSelected && !isCorrect) {
    return 'bg-red-50 border border-red-200'
  }
  if (!isSelected && isCorrect) {
    return 'bg-green-50 border border-green-200'
  }
  return 'bg-gray-50 border border-gray-200'
}

const getIconClass = (option, question) => {
  const isSelected = question.user_answers?.includes(option.id)
  const isCorrect = option.is_correct

  if (isSelected && isCorrect) return 'text-green-600'
  if (isSelected && !isCorrect) return 'text-red-600'
  if (!isSelected && isCorrect) return 'text-green-600'
  return 'text-gray-400'
}

const loadAttempt = async () => {
  try {
    const response = await axios.get(
      `/api/exams/${route.params.id}/attempts/${route.params.attemptId}/review`
    )
    attempt.value = response.data
  } catch (error) {
    console.error('Error loading attempt review:', error)
  }
}

onMounted(() => {
  loadAttempt()
})
</script>