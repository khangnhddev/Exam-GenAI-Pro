<template>
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Prompt Engineering Assessment</h1>

    <!-- Challenge Description -->
    <div class="mb-6 bg-purple-50 rounded-lg p-4">
      <h2 class="text-lg font-semibold text-purple-900 mb-2">Challenge:</h2>
      <p class="text-purple-800 mb-4">{{ currentChallenge.description }}</p>
      
      <div class="space-y-2">
        <h3 class="font-medium text-purple-900">Requirements:</h3>
        <ul class="list-disc list-inside text-purple-800">
          <li v-for="(req, index) in currentChallenge.requirements" :key="index">
            {{ req }}
          </li>
        </ul>
      </div>
    </div>

    <!-- User's Prompt -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Your Prompt Solution
      </label>
      <div class="h-[200px] border rounded-lg overflow-hidden bg-white">
        <MonacoEditor
          v-model="userPrompt"
          :value="userPrompt"
          @change="handleEditorChange"
          language="markdown"
          :options="editorOptions"
        />
      </div>
    </div>

    <!-- Controls -->
    <div class="flex justify-end mb-6">
      <button
        @click="evaluatePrompt"
        :disabled="isLoading"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
      >
        <svg
          v-if="isLoading"
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
        {{ isLoading ? 'Evaluating...' : 'Submit for Evaluation' }}
      </button>
    </div>

    <!-- Evaluation Results -->
    <div v-if="evaluation" class="mt-6 space-y-4">
      <div class="bg-white rounded-lg border p-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Evaluation Results</h3>
          <div class="text-2xl font-bold text-purple-600">
            Score: {{ evaluation.score }}/100
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="prose max-w-none">
            <h4 class="text-base font-medium text-gray-900">Feedback:</h4>
            <p class="text-gray-700">{{ evaluation.feedback }}</p>
          </div>

          <div class="space-y-2">
            <h4 class="text-base font-medium text-gray-900">Strengths:</h4>
            <ul class="list-disc list-inside text-gray-700">
              <li v-for="(strength, index) in evaluation.details.strengths" :key="index">
                {{ strength }}
              </li>
            </ul>
          </div>

          <div class="space-y-2">
            <h4 class="text-base font-medium text-gray-900">Areas for Improvement:</h4>
            <ul class="list-disc list-inside text-gray-700">
              <li v-for="(improvement, index) in evaluation.details.improvements" :key="index">
                {{ improvement }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import * as monaco from 'monaco-editor-vue3'
import axios from 'axios'

const MonacoEditor = monaco.default

const currentChallenge = {
  title: "AI Teaching Assistant Design",
  description: "Create a system message for an AI teaching assistant that specializes in helping students learn programming. The assistant should be knowledgeable, patient, and guide students towards solutions rather than providing direct answers.",
  requirements: [
    "Define clear role and personality for the AI",
    "Include guidelines for helping students learn",
    "Specify how to handle different skill levels",
    "Include error handling and edge cases",
    "Maintain educational best practices"
  ]
}

const userPrompt = ref('')

function handleEditorChange(value) {
  userPrompt.value = value
}

const isLoading = ref(false)
const evaluation = ref(null)

const editorOptions = {
  minimap: { enabled: false },
  scrollBeyondLastLine: false,
  fontSize: 14,
  lineNumbers: 'on',
  automaticLayout: true,
  wordWrap: 'on',
  theme: 'vs-light', // Change theme to light
  padding: { top: 16, bottom: 16 },
  renderLineHighlight: 'none',
  overviewRulerBorder: false,
  scrollbar: {
    vertical: 'hidden',
    horizontal: 'hidden'
  }
}

async function evaluatePrompt() {
  if (!userPrompt.value.trim()) {
    // Add validation
    console.warn('Prompt is empty')
    return
  }
  
  isLoading.value = true
  evaluation.value = null

  try {
    console.log('Evaluating prompt:', userPrompt.value)
    
    const response = await axios.post('/evaluate-prompt', {
      prompt: userPrompt.value,
      challenge: currentChallenge,
      criteria: {
        role_definition: "How well the prompt defines the AI's role",
        teaching_approach: "Effectiveness of the teaching methodology",
        adaptability: "Ability to handle different skill levels",
        error_handling: "Handling of edge cases and errors",
        best_practices: "Adherence to educational best practices"
      }
    }, {
      baseURL: '/api' // Override baseURL for this specific request
    })

    evaluation.value = response.data
  } catch (error) {
    console.error('Evaluation error:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<style>
.monaco-editor {
  padding: 8px;
}

.monaco-editor .margin {
  background-color: white !important;
}

.monaco-editor .monaco-scrollable-element {
  box-shadow: none !important;
}
</style>