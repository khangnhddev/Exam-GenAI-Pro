<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-900">AI Exam Generator</h1>
      </div>

      <div class="mt-8 bg-white shadow rounded-lg p-6">
        <form @submit.prevent="generateQuestions" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Topic</label>
            <input 
              v-model="form.topic" 
              type="text" 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="e.g. ChatGPT Advanced Usage"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Difficulty</label>
            <select 
              v-model="form.difficulty"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="easy">Easy</option>
              <option value="medium">Medium</option>
              <option value="hard">Hard</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              Number of Questions
            </label>
            <input 
              v-model.number="form.count" 
              type="number" 
              min="1" 
              max="50"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
          </div>

          <div class="flex justify-end">
            <button 
              type="submit" 
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              <svg 
                v-if="loading" 
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
              </svg>
              {{ loading ? 'Generating...' : 'Generate Questions' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
  topic: '',
  difficulty: 'medium',
  count: 10
})

const loading = ref(false)

const generateQuestions = async () => {
  loading.value = true
  try {
    const response = await axios.post(
      `/api/v1/admin/exams/${examId}/questions/generate`, 
      form.value
    )
    // Handle success
  } catch (error) {
    console.error('Error generating questions:', error)
  } finally {
    loading.value = false
  }
}
</script>