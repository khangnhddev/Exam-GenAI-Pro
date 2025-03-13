<template>
  <div class="space-y-6">
    <!-- Question Header -->
    <div class="space-y-2">
      <h3 class="text-lg font-medium text-gray-900">
        {{ question.title }}
      </h3>
      <p class="text-gray-600">{{ question.description }}</p>
    </div>

    <!-- Language Selector -->
    <div class="flex items-center space-x-4">
      <label class="text-sm font-medium text-gray-700">Programming Language:</label>
      <select 
        v-model="selectedLanguage"
        class="mt-1 block rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
      >
        <option value="javascript">JavaScript</option>
        <option value="python">Python</option>
        <option value="php">PHP</option>
      </select>
    </div>

    <!-- Code Editor -->
    <div class="h-[400px] border rounded-lg overflow-hidden">
      <MonacoEditor
        v-model="code"
        :language="selectedLanguage"
        :theme="'vs-dark'"
        :options="{
          minimap: { enabled: false },
          scrollBeyondLastLine: false,
          fontSize: 14,
          lineNumbers: 'on',
          automaticLayout: true
        }"
      />
    </div>

    <!-- Test Cases Panel -->
    <div v-if="testResults" class="space-y-4">
      <h4 class="font-medium text-gray-900">Test Results:</h4>
      <div class="space-y-2">
        <div 
          v-for="(result, index) in testResults" 
          :key="index"
          class="p-4 rounded-lg"
          :class="result.passed ? 'bg-green-50' : 'bg-red-50'"
        >
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <CheckCircleIcon 
                v-if="result.passed" 
                class="h-5 w-5 text-green-400" 
              />
              <XCircleIcon 
                v-else 
                class="h-5 w-5 text-red-400" 
              />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium" 
                :class="result.passed ? 'text-green-800' : 'text-red-800'"
              >
                Test Case {{ index + 1 }}
              </h3>
              <div class="mt-2 text-sm" 
                :class="result.passed ? 'text-green-700' : 'text-red-700'"
              >
                <p>{{ result.message }}</p>
                <pre v-if="result.error" class="mt-1 text-xs bg-white/50 p-2 rounded">{{ result.error }}</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
      <button
        @click="runTests"
        :disabled="running"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <PlayIcon v-if="!running" class="w-5 h-5 mr-2" />
        <ArrowPathIcon v-else class="w-5 h-5 mr-2 animate-spin" />
        {{ running ? 'Running Tests...' : 'Run Tests' }}
      </button>
      <button
        @click="submitAnswer"
        :disabled="!allTestsPassed || submitting"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
      >
        <CheckIcon class="w-5 h-5 mr-2" />
        Submit Answer
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { MonacoEditor } from 'monaco-editor-vue3'
import { 
  CheckCircleIcon, 
  XCircleIcon,
  PlayIcon,
  ArrowPathIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  question: {
    type: Object,
    required: true
  },
  attemptId: {
    type: [String, Number],
    required: true
  }
})

const emit = defineEmits(['answer-submitted'])

const code = ref(props.question.initial_code || '')
const selectedLanguage = ref(props.question.programming_language || 'javascript')
const running = ref(false)
const submitting = ref(false)
const testResults = ref(null)

const allTestsPassed = computed(() => {
  if (!testResults.value) return false
  return testResults.value.every(result => result.passed)
})

async function runTests() {
  running.value = true
  testResults.value = null
  
  try {
    const { data } = await axios.post('/api/code/evaluate', {
      code: code.value,
      language: selectedLanguage.value,
      question_id: props.question.id
    })
    
    testResults.value = data.results
  } catch (error) {
    console.error('Error running tests:', error)
  } finally {
    running.value = false
  }
}

async function submitAnswer() {
  if (!allTestsPassed.value) return
  
  submitting.value = true
  try {
    await axios.post(`/api/exam-attempts/${props.attemptId}/answers`, {
      question_id: props.question.id,
      code_answer: code.value,
      language: selectedLanguage.value,
      passed: true
    })
    
    emit('answer-submitted', true)
  } catch (error) {
    console.error('Error submitting answer:', error)
  } finally {
    submitting.value = false
  }
}
</script>