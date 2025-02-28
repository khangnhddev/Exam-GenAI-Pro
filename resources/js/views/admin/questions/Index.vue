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
        <div class="mt-4 flex md:mt-0 md:ml-4">
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
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SearchFilterPanel from '@/components/admin/SearchFilterPanel.vue'

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
</script>