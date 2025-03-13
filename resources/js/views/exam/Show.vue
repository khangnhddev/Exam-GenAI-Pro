<template>
  <div>
    <LoadingScreen v-if="loading" />
    
    <div v-else-if="exam" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Exam Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ exam.title }}</h1>
        <p class="mt-2 text-gray-600">{{ exam.description }}</p>
      </div>

      <!-- Exam Content -->
      <div v-if="!started" class="bg-white rounded-lg shadow p-6">
        <!-- Exam Info -->
        <div class="space-y-4">
          <div class="flex items-center text-sm text-gray-500">
            <span class="flex items-center">
              <ClockIcon class="w-5 h-5 mr-2" />
              {{ exam.duration }} minutes
            </span>
            <span class="ml-6 flex items-center">
              <QuestionMarkCircleIcon class="w-5 h-5 mr-2" />
              {{ exam.total_questions }} questions
            </span>
          </div>
        </div>

        <!-- Start Button -->
        <button
          @click="startExam"
          class="mt-6 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
        >
          Start Exam
        </button>
      </div>

      <!-- Exam Questions -->
      <div v-else>
        <ExamQuestion
          v-if="currentQuestion"
          :question="currentQuestion"
          :questionNumber="currentQuestionIndex + 1"
          v-model:answer="currentAnswer"
          :showValidation="showValidation"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { ClockIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/outline'
import LoadingScreen from '@/components/LoadingScreen.vue'
import ExamQuestion from '@/components/exam/ExamQuestion.vue'

const props = defineProps({
  slug: {
    type: String,
    required: true
  }
})

const router = useRouter()
const exam = ref(null)
const loading = ref(true)
const started = ref(false)
const currentQuestionIndex = ref(0)
const currentAnswer = ref(null)
const showValidation = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/exams/${props.slug}`)
    exam.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch exam:', error)
    router.push({ name: 'not-found' })
  } finally {
    loading.value = false
  }
})

const currentQuestion = computed(() => {
  if (!exam.value?.questions) return null
  return exam.value.questions[currentQuestionIndex.value]
})

function startExam() {
  started.value = true
}

// ...rest of your exam logic (navigation, submission, etc.)
</script>