<template>
  <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              {{ isEditing ? 'Edit Question' : 'Add New Question' }}
            </h3>

            <form @submit.prevent="handleSubmit" class="mt-4 space-y-4">
              <!-- Question Text -->
              <div>
                <label for="text" class="block text-sm font-medium text-gray-700">Question Text</label>
                <textarea
                  id="text"
                  v-model="form.text"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.text }"
                  placeholder="Enter your question"
                ></textarea>
                <p v-if="errors.text" class="mt-1 text-sm text-red-600">{{ errors.text[0] }}</p>
              </div>

              <!-- Question Type -->
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Question Type</label>
                <select
                  id="type"
                  v-model="form.type"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.type }"
                >
                  <option value="multiple_choice">Multiple Choice</option>
                  <option value="true_false">True/False</option>
                  <option value="short_answer">Short Answer</option>
                </select>
                <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type[0] }}</p>
              </div>

              <!-- Options -->
              <div v-if="form.type !== 'short_answer'">
                <div class="flex justify-between items-center mb-2">
                  <label class="block text-sm font-medium text-gray-700">Options</label>
                  <button
                    v-if="form.type === 'multiple_choice'"
                    type="button"
                    @click="addOption"
                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Add Option
                  </button>
                </div>
                
                <div class="space-y-2">
                  <div v-if="form.type === 'true_false'" class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                      <input type="radio" v-model="form.correct_answer" value="true" class="form-radio h-4 w-4 text-indigo-600">
                      <span class="ml-2">True</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input type="radio" v-model="form.correct_answer" value="false" class="form-radio h-4 w-4 text-indigo-600">
                      <span class="ml-2">False</span>
                    </label>
                  </div>

                  <div v-else v-for="(option, index) in form.options" :key="index" class="flex items-center space-x-2">
                    <input
                      type="radio"
                      v-model="form.correct_answer"
                      :value="index"
                      class="form-radio h-4 w-4 text-indigo-600"
                    >
                    <input
                      type="text"
                      v-model="option.text"
                      class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      :placeholder="`Option ${index + 1}`"
                    >
                    <button
                      v-if="form.options.length > 2"
                      type="button"
                      @click="removeOption(index)"
                      class="text-red-600 hover:text-red-800"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
                <p v-if="errors.options" class="mt-1 text-sm text-red-600">{{ errors.options[0] }}</p>
                <p v-if="errors.correct_answer" class="mt-1 text-sm text-red-600">{{ errors.correct_answer[0] }}</p>
              </div>
            </form>
          </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
          <button
            type="submit"
            @click="handleSubmit"
            :disabled="loading"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
          >
            {{ isEditing ? 'Update' : 'Create' }}
          </button>
          <button
            type="button"
            @click="$emit('close')"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  examId: {
    type: [Number, String],
    required: true
  },
  question: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'question-created', 'question-updated'])

const isEditing = computed(() => !!props.question)
const loading = ref(false)
const errors = ref({})

const form = ref({
  text: '',
  type: 'multiple_choice',
  options: [
    { text: '' },
    { text: '' }
  ],
  correct_answer: null
})

// Initialize form if editing
watch(() => props.question, (newVal) => {
  if (newVal) {
    form.value = {
      text: newVal.text,
      type: newVal.type,
      options: [...newVal.options],
      correct_answer: newVal.correct_answer
    }
  }
}, { immediate: true })

function addOption() {
  form.value.options.push({ text: '' })
}

function removeOption(index) {
  form.value.options.splice(index, 1)
  if (form.value.correct_answer === index) {
    form.value.correct_answer = null
  }
}

async function handleSubmit() {
  loading.value = true
  errors.value = {}

  try {
    const url = isEditing.value
      ? `/admin/exams/${props.examId}/questions/${props.question.id}`
      : `/admin/exams/${props.examId}/questions`
    
    const method = isEditing.value ? 'put' : 'post'
    const { data } = await axios[method](url, form.value)

    emit(isEditing.value ? 'question-updated' : 'question-created', data)
    emit('close')
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error submitting question:', error)
    }
  } finally {
    loading.value = false
  }
}
</script>