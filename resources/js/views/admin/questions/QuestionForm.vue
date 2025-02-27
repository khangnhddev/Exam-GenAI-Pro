<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Add Back Button -->
      <div class="mb-6">
        <button
          @click="$router.back()"
          class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900"
        >
          <svg 
            class="h-5 w-5 mr-1" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M15 19l-7-7 7-7"
            />
          </svg>
          Back
        </button>
      </div>

      <div class="md:flex md:items-center md:justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ isEditing ? 'Edit Question' : 'Add Question' }}
        </h1>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Question Text -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Question Text</label>
            <textarea
              v-model="form.question_text"
              rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            ></textarea>
          </div>

          <!-- Question Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Type</label>
            <select
              v-model="form.type"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            >
              <option value="single">Single Choice</option>
              <option value="multiple">Multiple Choice</option>
            </select>
          </div>

          <!-- Points -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Points</label>
            <input
              v-model.number="form.points"
              type="number"
              min="1"
              max="10"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              required
            >
          </div>

          <!-- Options -->
          <div>
            <div class="flex items-center justify-between">
              <label class="block text-sm font-medium text-gray-700">Options</label>
              <button
                type="button"
                @click="addOption"
                class="text-sm text-indigo-600 hover:text-indigo-900"
                :disabled="form.options.length >= 6"
              >
                Add Option
              </button>
            </div>
            <div class="mt-2 space-y-3">
              <div v-for="(option, index) in form.options" :key="index" class="flex items-start space-x-3">
                <div class="flex-grow">
                  <input
                    v-model="option.text"
                    type="text"
                    required
                    placeholder="Option text"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>
                <div class="flex items-center space-x-2">
                  <input
                    v-model="option.is_correct"
                    type="checkbox"
                    :disabled="form.type === 'single' && hasCorrectOption && !option.is_correct"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                  <button
                    type="button"
                    @click="removeOption(index)"
                    class="text-red-600 hover:text-red-900"
                    :disabled="form.options.length <= 2"
                  >
                    Remove
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Explanation -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Explanation (Optional)</label>
            <textarea
              v-model="form.explanation"
              rows="2"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$router.back()"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading || !isFormValid"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {{ isEditing ? 'Update Question' : 'Create Question' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const examId = route.params.examId
const questionId = route.params.questionId
const isEditing = computed(() => !!questionId)
const loading = ref(false)

const form = ref({
  question_text: '',
  type: 'single',
  points: 1,
  options: [
    { text: '', is_correct: false },
    { text: '', is_correct: false }
  ],
  explanation: ''
})

const hasCorrectOption = computed(() => {
  return form.value.options.some(option => option.is_correct)
})

const isFormValid = computed(() => {
  return form.value.question_text &&
         form.value.options.length >= 2 &&
         form.value.options.every(opt => opt.text) &&
         form.value.options.some(opt => opt.is_correct)
})

function addOption() {
  if (form.value.options.length < 6) {
    form.value.options.push({ text: '', is_correct: false })
  }
}

function removeOption(index) {
  if (form.value.options.length > 2) {
    form.value.options.splice(index, 1)
  }
}

async function handleSubmit() {
  if (!isFormValid.value) return

  loading.value = true
  try {
    const url = isEditing.value
      ? `/api/v1/admin/exams/${examId}/questions/${questionId}`
      : `/api/v1/admin/exams/${examId}/questions`
    
    const method = isEditing.value ? 'put' : 'post'
    
    await axios[method](url, form.value)
    router.push({ name: 'admin.exams.questions', params: { examId } })
  } catch (error) {
    console.error('Error saving question:', error)
  } finally {
    loading.value = false
  }
}

// Load question data if editing
onMounted(async () => {
  if (isEditing.value) {
    loading.value = true
    try {
      const { data } = await axios.get(`/api/v1/admin/exams/${examId}/questions/${questionId}`)
      form.value = {
        question_text: data.question_text,
        type: data.type,
        points: data.points,
        options: data.options.map(opt => ({
          text: opt.text,
          is_correct: opt.is_correct
        })),
        explanation: data.explanation || ''
      }
    } catch (error) {
      console.error('Error loading question:', error)
    } finally {
      loading.value = false
    }
  }
})
</script>