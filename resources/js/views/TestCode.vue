<template>
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Code Submission Test</h1>

    <!-- Language Selector -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Select Language
      </label>
      <select 
        v-model="selectedLanguage"
        class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      >
        <option value="javascript">JavaScript</option>
        <option value="python">Python</option>
        <option value="php">PHP</option>
      </select>
    </div>

    <!-- Code Editor -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Code Editor
      </label>
      <div class="h-[400px] border rounded-lg overflow-hidden">
        <MonacoEditor
          v-model="code"
          :language="selectedLanguage"
          theme="vs-dark"
          :options="{
            minimap: { enabled: false },
            scrollBeyondLastLine: false,
            fontSize: 14,
            lineNumbers: 'on',
            automaticLayout: true
          }"
        />
      </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
      <button
        @click="submitCode"
        :disabled="isSubmitting"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
      >
        <svg
          v-if="isSubmitting"
          class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          />
        </svg>
        {{ isSubmitting ? 'Submitting...' : 'Submit Code' }}
      </button>
    </div>

    <!-- Results Panel -->
    <div v-if="results" class="mt-8 space-y-4">
      <h2 class="text-xl font-semibold">Results:</h2>
      <pre class="bg-gray-50 p-4 rounded-lg overflow-x-auto">{{ JSON.stringify(results, null, 2) }}</pre>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import * as monaco from 'monaco-editor-vue3'
import axios from 'axios'

const selectedLanguage = ref('javascript')
const code = ref('// Write your code here\n')
const isSubmitting = ref(false)
const results = ref(null)

// Component registration
const MonacoEditor = monaco.default

async function submitCode() {
  isSubmitting.value = true
  results.value = null

  try {
    const response = await axios.post('/api/code/test-submit', {
      code: code.value,
      language: selectedLanguage.value
    })
    results.value = response.data
  } catch (error) {
    console.error('Error submitting code:', error)
    results.value = {
      error: error.response?.data?.message || 'Failed to submit code'
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>