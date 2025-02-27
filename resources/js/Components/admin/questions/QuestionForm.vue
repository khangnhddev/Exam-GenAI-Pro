<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <!-- Question Text -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Question Text</label>
      <textarea
        v-model="form.question_text"
        rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        required
      ></textarea>
    </div>

    <!-- Question Type -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Question Type</label>
      <select
        v-model="form.type"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      >
        <option value="single">Single Choice</option>
        <option value="multiple">Multiple Choice</option>
      </select>
    </div>

    <!-- Points -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Points</label>
      <input
        type="number"
        v-model.number="form.points"
        min="1"
        max="10"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      />
    </div>

    <!-- Options -->
    <div class="space-y-4">
      <div class="flex justify-between items-center">
        <label class="block text-sm font-medium text-gray-700">Options</label>
        <button
          type="button"
          @click="addOption"
          class="text-sm text-indigo-600 hover:text-indigo-800"
        >
          Add Option
        </button>
      </div>

      <div 
        v-for="(option, index) in form.options" 
        :key="index"
        class="flex items-center space-x-4"
      >
        <div class="flex-1">
          <input
            v-model="option.text"
            type="text"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Option text"
            required
          />
        </div>
        <div class="flex items-center space-x-2">
          <input
            :type="form.type === 'single' ? 'radio' : 'checkbox'"
            v-model="option.is_correct"
            :name="form.type === 'single' ? 'correct_answer' : undefined"
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
          />
          <button 
            type="button"
            @click="removeOption(index)"
            class="text-red-600 hover:text-red-900"
          >
            ×
          </button>
        </div>
      </div>
    </div>

    <!-- Explanation -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Explanation</label>
      <textarea
        v-model="form.explanation"
        rows="2"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      ></textarea>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-3">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
      >
        Cancel
      </button>
      <button
        type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700"
        :disabled="!isValid"
      >
        {{ props.question ? 'Update' : 'Create' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
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

const emit = defineEmits(['saved', 'cancel'])

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

const isValid = computed(() => {
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
  try {
    const url = props.question 
      ? `/api/admin/questions/${props.question.id}`
      : `/api/admin/exams/${props.examId}/questions`
    
    const method = props.question ? 'put' : 'post'
    const { data } = await axios[method](url, form.value)
    
    emit('saved', data.data)
  } catch (error) {
    console.error('Failed to save question:', error)
  }
}

onMounted(() => {
  if (props.question) {
    form.value = {
      question_text: props.question.question_text,
      type: props.question.type,
      points: props.question.points,
      options: props.question.options.map(opt => ({
        text: opt.text,
        is_correct: opt.is_correct
      })),
      explanation: props.question.explanation
    }
  }
})
</script>