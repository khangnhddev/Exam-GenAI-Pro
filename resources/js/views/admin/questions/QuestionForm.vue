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
          <!-- Question Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Question Type</label>
            <div class="mt-1 flex space-x-4">
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  v-model="form.type"
                  value="single"
                  class="form-radio text-indigo-600"
                />
                <span class="ml-2 text-sm text-gray-700">Single Choice</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  v-model="form.type"
                  value="multiple"
                  class="form-radio text-indigo-600"
                />
                <span class="ml-2 text-sm text-gray-700">Multiple Choice</span>
              </label>
            </div>
          </div>

          <!-- Question Text -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Question Text</label>
            <div class="mt-1">
              <textarea
                v-model="form.question_text"
                rows="3"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                :placeholder="'Enter your question here...'"
              ></textarea>
            </div>
          </div>

          <!-- Points -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Points</label>
            <div class="mt-1">
              <input
                type="number"
                v-model="form.points"
                min="1"
                max="10"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              />
            </div>
          </div>

          <!-- Options -->
          <div>
  <div class="flex justify-between items-center mb-4">
    <label class="block text-sm font-medium text-gray-700">Answer Options</label>
    <button
      type="button"
      @click="addOption"
      :disabled="form.options.length >= 6"
      class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
    >
      <PlusIcon class="h-4 w-4 mr-1.5" />
      Add Option
    </button>
  </div>
  
  <div class="space-y-3">
    <div
      v-for="(option, index) in form.options"
      :key="index"
      class="group relative flex items-start space-x-3 p-4 rounded-lg border"
      :class="[
        option.is_correct 
          ? 'border-green-200 bg-green-50/50' 
          : 'border-gray-200 hover:border-gray-300 bg-white'
      ]"
    >
      <!-- Option Number -->
      <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-500">
        {{ index + 1 }}
      </div>

      <!-- Option Text Input -->
      <div class="flex-grow">
        <input
          v-model="option.option_text"
          type="text"
          class="block w-full border-0 border-b border-transparent bg-transparent px-0 py-1.5 text-gray-900 placeholder:text-gray-400 focus:border-indigo-600 focus:ring-0 sm:text-sm sm:leading-6"
          :placeholder="`Enter option ${index + 1}`"
        />
      </div>

      <!-- Actions -->
      <div class="flex items-center space-x-3">
        <!-- Correct Answer Selection -->
        <label 
          class="relative flex items-center justify-center"
          :class="{ 
            'cursor-pointer': true,
            'cursor-not-allowed opacity-50': form.options.length <= 2 && option.is_correct 
          }"
        >
          <input
            :type="form.type === 'single' ? 'radio' : 'checkbox'"
            v-model="option.is_correct"
            :name="form.type === 'single' ? 'correct_option' : undefined"
            :disabled="form.options.length <= 2 && option.is_correct"
            @change="handleCorrectOptionChange(index)"
            class="peer sr-only"
          />
          <div 
            class="h-6 w-6 rounded border flex items-center justify-center transition-colors duration-150"
            :class="[
              option.is_correct
                ? 'bg-green-500 border-transparent text-white'
                : 'border-gray-300 bg-white text-transparent hover:border-gray-400'
            ]"
          >
            <CheckIcon class="h-4 w-4" />
          </div>
          <span class="sr-only">Mark as correct answer</span>
        </label>

        <!-- Delete Option -->
        <button
          type="button"
          @click="removeOption(index)"
          :disabled="form.options.length <= 2"
          class="flex items-center justify-center h-6 w-6 rounded-full text-gray-400 hover:text-red-500 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <TrashIcon class="h-4 w-4" />
          <span class="sr-only">Delete option</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Helper text -->
  <p class="mt-2 text-sm text-gray-500">
    {{ form.type === 'single' ? 'Select one correct answer' : 'Select one or more correct answers' }}
  </p>
</div>

          <!-- Explanation -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Explanation (Optional)
            </label>
            <div class="mt-1">
              <textarea
                v-model="form.explanation"
                rows="2"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Explain why the correct answer is correct..."
              ></textarea>
            </div>
          </div>

          <!-- Form Actions -->
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
import { PlusIcon, TrashIcon, CheckIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'

const route = useRoute()
const router = useRouter()
const toast = useToast()

// Make examId optional using optional chaining
const examId = computed(() => route.params?.examId)
const questionId = computed(() => route.params?.id)
const isEditing = computed(() => !!questionId.value)

const loading = ref(false)

const form = ref({
  question_text: '',
  type: 'single',
  points: 1,
  exam_id: examId.value || null, // Make exam_id optional
  options: [
    { option_text: '', is_correct: false },
    { option_text: '', is_correct: false }
  ],
  explanation: ''
})

const hasCorrectOption = computed(() => {
  return form.value.options.some(option => option.is_correct)
})

const isFormValid = computed(() => {
  return form.value.question_text &&
         form.value.options.length >= 2 &&
         form.value.options.every(opt => opt.option_text) &&
         form.value.options.some(opt => opt.is_correct)
})

function addOption() {
  if (form.value.options.length < 6) {
    form.value.options.push({ option_text: '', is_correct: false })
  }
}

function removeOption(index) {
  if (form.value.options.length > 2) {
    form.value.options.splice(index, 1)
  }
}

function handleCorrectOptionChange(index) {
  if (form.value.type === 'single') {
    form.value.options.forEach((opt, idx) => {
      opt.is_correct = idx === index
    })
  }
}

// Update save function to handle both cases
async function handleSubmit() {
  if (!isFormValid.value) return;

  loading.value = true
  try {
    const endpoint = isEditing.value 
      ? `/api/v1/admin/questions/${questionId.value}`
      : '/api/v1/admin/questions'
    
    const method = isEditing.value ? 'put' : 'post'
    const response = await axios[method](endpoint, form.value)
    
    toast.success(`Question ${isEditing.value ? 'updated' : 'created'} successfully`)
    
    // Redirect based on context
    if (examId.value) {
      router.push({ 
        name: 'admin.exams.questions',
        params: { examId: examId.value }
      })
    } else {
      router.push({ name: 'admin.questions.index' })
    }
  } catch (error) {
    console.error('Error saving question:', error)
    toast.error(error.response?.data?.message || 'Failed to save question')
  } finally {
    loading.value = false
  }
}

// Update loadQuestion function
async function loadQuestion() {
  if (!questionId.value) return
  
  try {
    loading.value = true
    const { data } = await axios.get(`/api/admin/questions/${questionId.value}`)
    form.value = {
      ...data.data,
      exam_id: data.data.exam_id || null // Handle null exam_id
    }
  } catch (error) {
    console.error('Error loading question:', error)
    toast.error('Failed to load question')
  } finally {
    loading.value = false
  }
}

// Load question data if editing
onMounted(async () => {
  console.log('Exam ID:', examId.value);
  if (isEditing.value) {
    loading.value = true
    try {
      const { data } = await axios.get(`/admin/exams/${examId.value}/questions/${questionId.value}`)
      form.value = {
        question_text: data.question_text,
        type: data.type,
        points: data.points,
        options: data.options.map(opt => ({
          option_text: opt.option_text,
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