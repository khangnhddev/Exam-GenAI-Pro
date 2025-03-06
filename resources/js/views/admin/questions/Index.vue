<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Question Bank
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage and organize exam questions
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
          <!-- AI Generate Button -->
          <button
            @click="showAIModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            AI Generate
          </button>
          
          <!-- Existing Add Question button -->
          <router-link
            :to="{ name: 'admin.questions.create' }"
            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Question
          </router-link>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <SearchFilterPanel
        v-model:search="searchQuery"
        v-model:filter="selectedType"
        search-label="Search Questions"
        search-placeholder="Search by question content..."
        filter-label="Question Type"
        :filters="typeFilters"
      />

      <!-- Questions Table -->
      <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Question Content
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Type
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Exam
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Points
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="question in filteredQuestions" :key="question.id">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ truncateText(question.text || '', 100) }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-blue-100 text-blue-800': question.type === 'multiple',
                      'bg-green-100 text-green-800': question.type === 'single'
                    }"
                  >
                    {{ question.type === 'multiple' ? 'Multiple Choice' : 'Single Choice' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ question.exam?.title || 'No Exam' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ question.points }} points
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <router-link
                    :to="{ name: 'admin.questions.edit', params: { id: question.id } }"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Edit
                  </router-link>
                  <button
                    @click="deleteQuestion(question)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- AI Generation Modal -->
      <TransitionRoot appear :show="showAIModal" as="template">
        <Dialog as="div" @close="showAIModal = false" class="relative z-10">
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
              <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="ease-in duration-200"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
              >
                <DialogPanel class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                  <!-- Header -->
                  <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                      AI Question Generator
                    </DialogTitle>
                    <button 
                      @click="showAIModal = false"
                      class="text-gray-400 hover:text-gray-500"
                    >
                      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>

                  <!-- Description -->
                  <p class="mt-4 text-sm text-gray-500">
                    Generate multiple-choice questions using AI. Specify your topic, difficulty level, and the number of questions you need.
                  </p>

                  <div class="mt-6 space-y-6">
                    <!-- Add this before the Topic Input in the modal form -->
                    <div class="mb-6">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Question Source</label>
                      <div class="mt-2 grid grid-cols-2 gap-3">
                        <!-- OpenAI Option -->
                        <div 
                          @click="aiForm.source = 'openai'"
                          :class="[
                            'cursor-pointer rounded-lg p-4 border flex items-center',
                            aiForm.source === 'openai' 
                              ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500' 
                              : 'border-gray-200 hover:border-gray-300'
                          ]"
                        >
                          <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 10V3L4 14h7v7l9-11h-7z" />
                          </svg>
                          <span class="ml-3 font-medium text-gray-900">OpenAI</span>
                        </div>

                        <!-- Knowledge Base Option -->
                        <div 
                          @click="aiForm.source = 'knowledge'"
                          :class="[
                            'cursor-pointer rounded-lg p-4 border flex items-center',
                            aiForm.source === 'knowledge' 
                              ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500' 
                              : 'border-gray-200 hover:border-gray-300'
                          ]"
                        >
                          <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                          </svg>
                          <span class="ml-3 font-medium text-gray-900">Knowledge Base</span>
                        </div>
                      </div>
                    </div>

                    <!-- Topic Input (show only for OpenAI) -->
                    <div v-if="aiForm.source === 'openai'" class="mb-6">
                      <label class="block text-sm font-medium text-gray-700">Topic</label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                          v-model="aiForm.topic"
                          type="text"
                          class="block w-full pr-10 pl-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Enter topic for question generation"
                        />
                      </div>
                    </div>

                    <!-- Difficulty and Count in 2 columns -->
                    <div class="grid grid-cols-2 gap-4">
                      <!-- Difficulty Select -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Difficulty Level</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                          <select
                            v-model="aiForm.difficulty"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                          >
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                          </select>
                        </div>
                      </div>

                      <!-- Count Input -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Number of Questions</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                          <input
                            v-model.number="aiForm.count"
                            type="number"
                            min="1"
                            max="10"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                          />
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Maximum 10 questions per generation</p>
                      </div>
                    </div>
                  </div>

                  <!-- Footer -->
                  <div class="mt-8 border-t border-gray-200 pt-4">
                    <div class="flex justify-end space-x-3">
                      <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        @click="showAIModal = false"
                      >
                        Cancel
                      </button>
                      <button
                        type="button"
                        :disabled="generating || (aiForm.source === 'openai' && !aiForm.topic)"
                        @click="generateQuestions"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        <svg
                          v-if="generating"
                          class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                        <svg 
                          v-else 
                          class="-ml-1 mr-2 h-5 w-5" 
                          fill="none" 
                          viewBox="0 0 24 24" 
                          stroke="currentColor"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ generating ? 'Generating Questions...' : 'Generate Questions' }}
                      </button>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>

      <!-- Preview Modal -->
      <TransitionRoot appear :show="showPreviewModal" as="template">
        <Dialog as="div" @close="showPreviewModal = false" class="relative z-10">
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
              <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="ease-in duration-200"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
              >
                <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                  <!-- Header -->
                  <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                      Review Generated Questions
                    </DialogTitle>
                    <button 
                      @click="showPreviewModal = false"
                      class="text-gray-400 hover:text-gray-500"
                    >
                      <XMarkIcon class="h-6 w-6" />
                    </button>
                  </div>

                  <!-- Question Preview List -->
                  <div class="mt-4 space-y-6 max-h-[60vh] overflow-y-auto">
                    <div v-for="(question, index) in generatedQuestions" :key="index" 
                         class="p-4 border border-gray-200 rounded-lg">
                      <div class="flex items-start justify-between">
                        <h4 class="text-base font-medium text-gray-900">
                          Question {{ index + 1 }}
                        </h4>
                        <button 
                          @click="generatedQuestions.splice(index, 1)"
                          class="text-red-500 hover:text-red-700"
                        >
                          <TrashIcon class="h-5 w-5" />
                        </button>
                      </div>
                      
                      <!-- Question Text -->
                      <p class="mt-2 text-gray-700">{{ question.question }}</p>
                      
                      <!-- Question Type and Points -->
                      <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                          <span class="font-medium mr-1">Type:</span>
                          <span class="px-2 py-1 rounded-full text-xs"
                            :class="{
                              'bg-blue-100 text-blue-800': question.type === 'multiple',
                              'bg-green-100 text-green-800': question.type === 'single'
                            }"
                          >
                            {{ question.type === 'multiple' ? 'Multiple Choice' : 'Single Choice' }}
                          </span>
                        </span>
                        <span class="flex items-center">
                          <span class="font-medium mr-1">Points:</span>
                          {{ question.points }}
                        </span>
                      </div>
                      
                      <!-- Options -->
                      <div class="mt-3 space-y-2">
                        <div v-for="(option, optIndex) in question.options" :key="optIndex"
                             class="flex items-center space-x-2">
                          <span :class="[
                            'flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs',
                            option.is_correct ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'
                          ]">
                            {{ String.fromCharCode(65 + optIndex) }}
                          </span>
                          <span :class="[
                            'text-sm',
                            option.is_correct ? 'text-green-800 font-medium' : 'text-gray-600'
                          ]">
                            {{ option.text }}
                          </span>
                        </div>
                      </div>

                      <!-- Explanation -->
                      <div class="mt-3 text-sm text-gray-500">
                        <span class="font-medium">Explanation:</span>
                        {{ question.explanation }}
                      </div>
                    </div>
                  </div>

                  <!-- Footer -->
                  <div class="mt-6 border-t border-gray-200 pt-4 flex justify-between items-center">
                    <p class="text-sm text-gray-500">
                      {{ generatedQuestions.length }} questions ready to be saved
                    </p>
                    <div class="flex space-x-3">
                      <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        @click="showPreviewModal = false"
                      >
                        Cancel
                      </button>
                      <button
                        type="button"
                        :disabled="savingQuestions || !generatedQuestions.length"
                        @click="saveGeneratedQuestions"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        <svg
                          v-if="savingQuestions"
                          class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                        {{ savingQuestions ? 'Saving...' : 'Save Questions' }}
                      </button>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { XMarkIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import SearchFilterPanel from '@/Components/admin/SearchFilterPanel.vue'

const toast = useToast()
const showAIModal = ref(false)
const showPreviewModal = ref(false)
const generating = ref(false)
const savingQuestions = ref(false)
const generatedQuestions = ref([])

const aiForm = ref({
  source: 'openai',
  topic: '',
  difficulty: 'medium',
  count: 5
})

// Function to generate questions
async function generateQuestions() {
  generating.value = true
  try {
    const { data } = await axios.post('/admin/questions/generate', aiForm.value)
    
    // Map the response to match the expected structure
    generatedQuestions.value = data.questions.map(q => ({
      ...q,
      question_text: q.question, // For backend compatibility
      options: q.options.map(opt => ({
        ...opt,
        option_text: opt.text // For backend compatibility
      }))
    }))
    
    showAIModal.value = false
    showPreviewModal.value = true
    toast.success('Questions generated successfully!')
  } catch (error) {
    console.error('Error generating questions:', error)
    toast.error(error.response?.data?.message || 'Failed to generate questions')
  } finally {
    generating.value = false
  }
}

// Function to save approved questions
async function saveGeneratedQuestions() {
  savingQuestions.value = true
  try {
    await axios.post('/admin/questions/save-generated', {
      questions: generatedQuestions.value
    })
    
    await loadQuestions() // Refresh question list
    showPreviewModal.value = false
    toast.success('Questions saved successfully!')
    
    // Reset states
    generatedQuestions.value = []
    aiForm.value = {
      source: 'openai',
      topic: '',
      difficulty: 'medium',
      count: 5
    }
  } catch (error) {
    console.error('Error saving questions:', error)
    toast.error('Failed to save questions')
  } finally {
    savingQuestions.value = false
  }
}

const questions = ref([])
const searchQuery = ref('')
const selectedType = ref('')
const loading = ref(true)
const error = ref(null)

const typeFilters = [
  { value: 'multiple', label: 'Multiple Choice' },
  { value: 'single', label: 'Single Choice' }
]

const filteredQuestions = computed(() => {
  if (!questions.value) return []
  
  return questions.value.filter(question => {
    const matchesSearch = !searchQuery.value || 
      (question.text?.toLowerCase() || '').includes(searchQuery.value.toLowerCase())
    
    const matchesType = !selectedType.value || question.type === selectedType.value
    
    return matchesSearch && matchesType
  })
})

onMounted(async () => {
  try {
    console.log('Fetching questions...');
    loading.value = true
    error.value = null
    console.log('Fetching questions...');
    
    const { data } = await axios.get('/admin/questions');
    questions.value = data.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error loading questions'
    console.error('Error fetching questions:', err)
  } finally {
    loading.value = false
  }
})

function truncateText(text, length) {
  if (!text) return '' // Handle null/undefined cases
  const str = String(text) // Convert to string in case of numbers
  if (str.length <= length) return str
  return str.substring(0, length) + '...'
}

async function deleteQuestion(question) {
  if (!confirm(`Are you sure you want to delete this question?`)) return
  
  try {
    await axios.delete(`/admin/questions/${question.id}`)
    questions.value = questions.value.filter(q => q.id !== question.id)
  } catch (error) {
    console.error('Error deleting question:', error)
  }
}

async function loadQuestions() {
  try {
    const { data } = await axios.get('/admin/questions')
    questions.value = data.data
  } catch (error) {
    console.error('Error loading questions:', error)
  }
}
</script>