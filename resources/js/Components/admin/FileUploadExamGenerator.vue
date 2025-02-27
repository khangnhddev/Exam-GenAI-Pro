<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-2xl font-bold mb-6">Generate Exam from File</h2>
      
      <form @submit.prevent="generateQuestions" class="space-y-6">
        <!-- File Upload -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Upload Learning Material</label>
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                  <span>Upload a file</span>
                  <input 
                    type="file" 
                    class="sr-only" 
                    @change="handleFileChange"
                    accept=".pdf,.docx,.txt"
                  >
                </label>
              </div>
              <p class="text-xs text-gray-500">PDF, DOCX, TXT up to 10MB</p>
            </div>
          </div>
          <p v-if="selectedFile" class="mt-2 text-sm text-gray-500">
            Selected: {{ selectedFile.name }}
          </p>
        </div>

        <!-- Options -->
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Question Count</label>
            <input 
              v-model="count" 
              type="number" 
              min="1" 
              max="50"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
            >
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Difficulty</label>
            <select 
              v-model="difficulty"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
            >
              <option value="easy">Easy</option>
              <option value="medium">Medium</option>
              <option value="hard">Hard</option>
            </select>
          </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
          <button 
            type="submit"
            :disabled="loading || !selectedFile"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Generate Questions
          </button>
        </div>
      </form>

      <!-- Preview -->
      <div v-if="questions.length" class="mt-8">
        <h3 class="text-lg font-medium mb-4">Generated Questions</h3>
        <div class="space-y-4">
          <div v-for="(question, index) in questions" :key="index" class="border rounded p-4">
            <p class="font-medium">{{ index + 1 }}. {{ question.question_text }}</p>
            <ul class="mt-2 space-y-1">
              <li v-for="(option, optIndex) in question.options" :key="optIndex"
                  :class="{'text-green-600': option.is_correct}"
              >
                {{ option.text }}
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
import axios from 'axios'

const selectedFile = ref(null)
const count = ref(10)
const difficulty = ref('medium')
const loading = ref(false)
const questions = ref([])

function handleFileChange(event) {
  selectedFile.value = event.target.files[0]
}

async function generateQuestions() {
  if (!selectedFile.value) return

  loading.value = true
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('count', count.value)
  formData.append('difficulty', difficulty.value)

  try {
    const { data } = await axios.post('/admin/exams/generate-from-file', formData)
    questions.value = data.questions
  } catch (error) {
    console.error('Error generating questions:', error)
    alert('Failed to generate questions')
  } finally {
    loading.value = false
  }
}
</script>