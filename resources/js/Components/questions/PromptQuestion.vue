<template>
  <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
    <!-- Question Header -->
    <div class="space-y-4">
      <h3 class="text-xl font-semibold text-gray-900">
        {{ question.question_text }}
      </h3>
      
      <div class="prose max-w-none">
        <p class="text-gray-600">
          {{ question.challenge_description }}
        </p>
      </div>
    </div>

    <!-- Requirements Section -->
    <div class="space-y-3">
      <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wide">
        Requirements
      </h4>
      <ul class="list-disc pl-5 space-y-2">
        <li 
          v-for="(req, index) in question.requirements" 
          :key="index"
          class="text-gray-600"
        >
          {{ req }}
        </li>
      </ul>
    </div>

    <!-- Evaluation Criteria -->
    <div class="space-y-4">
      <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wide">
        Evaluation Criteria
      </h4>
      <div class="grid gap-4">
        <div 
          v-for="(criterion, key) in question.evaluation_criteria" 
          :key="key"
          class="bg-gray-50 p-4 rounded-lg"
        >
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </span>
            </div>
            <div class="ml-4">
              <h5 class="text-sm font-medium text-gray-900 capitalize">
                {{ key }}
              </h5>
              <p class="mt-1 text-sm text-gray-500">
                {{ criterion }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Answer Section -->
    <div class="space-y-4">
      <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wide">
        Your Response
      </h4>
      <textarea
        v-model="response"
        rows="6"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Write your prompt here..."
      ></textarea>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3">
      <button
        @click="resetResponse"
        type="button"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Reset
      </button>
      <button
        @click="submitResponse"
        type="button"
        :disabled="!response.trim()"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
      >
        Submit Response
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

const props = defineProps({
  question: {
    type: Object,
    required: true
  }
})

const toast = useToast()
const response = ref('')
const emit = defineEmits(['submit'])

function resetResponse() {
  response.value = ''
}

function submitResponse() {
  if (!response.value.trim()) {
    toast.error('Please provide a response')
    return
  }

  emit('submit', {
    questionId: props.question.id,
    response: response.value
  })
}
</script>