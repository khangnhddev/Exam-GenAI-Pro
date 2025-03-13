<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Manage Exams
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Create and manage certification exams and their questions
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
          <button
            @click="showAIModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Generate with AI
          </button>
          
          <router-link
            :to="{ name: 'admin.exams.create' }"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Exam
          </router-link>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <SearchFilterPanel
        :search-query="searchQuery"
        @update:search="(value) => { 
          searchQuery = value
          fetchExams(1)
        }"
        :selected-status="selectedStatus"
        @update:filter="(value) => {
          selectedStatus = value
          fetchExams(1)
        }"
        :selected-date-range="selectedDateRange"
        @update:date-range="(value) => {
          selectedDateRange = value
          fetchExams(1)
        }"
        search-label="Search Exams"
        search-placeholder="Search by title or description..."
        filter-label="Status"
        :filters="statusFilters"
      />

      <!-- Exams Table -->
      <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Exam Details
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Questions
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Source
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Created
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="exam in filteredExams" :key="exam.id">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full overflow-hidden">
                      <img 
                        v-if="exam.image_url"
                        :src="exam.image_url" 
                        :alt="exam.title"
                        class="h-10 w-10 object-cover"
                        @error="$event.target.style.display='none'"
                      />
                      <DefaultExamIcon v-else />
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                      <div class="text-sm font-medium text-gray-900 break-words">
                        {{ exam.title }}
                      </div>
                      <div class="text-sm text-gray-500 truncate">
                        {{ exam.description }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': exam.status === 'published',
                      'bg-yellow-100 text-yellow-800': exam.status === 'draft',
                      'bg-gray-100 text-gray-800': exam.status === 'archived'
                    }"
                  >
                    {{ exam.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ exam.questions_count || 0 }} questions
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      exam.source === 'ai_generated' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'
                    ]">
                      {{ exam.source === 'ai_generated' ? 'AI Generated' : 'Manual' }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="text-sm text-gray-900">
                      {{ new Date(exam.created_at).toLocaleDateString() }}
                    </span>
                    <span class="text-xs text-gray-500">
                      {{ new Date(exam.created_at).toLocaleTimeString() }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <router-link
                    :to="{ name: 'admin.exams.questions', params: { examId: exam.id } }"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Questions
                  </router-link>

                  <!-- Add Publish/Unpublish Button -->
                  <button
                    v-if="exam.status !== 'archived'"
                    @click="togglePublishStatus(exam)"
                    :class="[
                      'inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium transition-colors',
                      exam.status === 'draft' 
                        ? 'text-white bg-green-600 hover:bg-green-700' 
                        : 'text-white bg-yellow-600 hover:bg-yellow-700'
                    ]"
                    class="mr-4"
                  >
                    <template v-if="exam.status === 'draft'">
                      <!-- <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 15l7-7 7 7" />
                      </svg> -->
                      Publish
                    </template>
                    <template v-else>
                      <!-- <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 9l-7 7-7-7" />
                      </svg> -->
                      Unpublish
                    </template>
                  </button>

                  <router-link
                    :to="{ name: 'admin.exams.edit', params: { id: exam.id } }"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Edit
                  </router-link>
                  
                  <button
                    @click="deleteExam(exam)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="px-6 py-4 bg-white border-t">
            <div class="flex items-center justify-between">
              <!-- Items per page info -->
              <div class="text-sm text-gray-500">
                Showing {{ exams.meta.from || 0 }} to {{ exams.meta.to || 0 }} of {{ exams.meta.total || 0 }} exams
              </div>

              <!-- Pagination buttons -->
              <div class="flex space-x-2">
                <button 
                  @click="changePage(exams.meta.current_page - 1)"
                  :disabled="!exams.links.prev"
                  class="px-3 py-1 text-sm rounded-md transition-colors"
                  :class="[
                    exams.links.prev 
                      ? 'text-gray-700 hover:bg-gray-100' 
                      : 'text-gray-400 cursor-not-allowed'
                  ]"
                >
                  Previous
                </button>

                <!-- Page numbers -->
                <div class="flex space-x-1">
                  <button
                    v-for="page in pageNumbers"
                    :key="page"
                    @click="changePage(page)"
                    class="px-3 py-1 text-sm rounded-md transition-colors"
                    :class="[
                      page === exams.meta.current_page
                        ? 'bg-indigo-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100'
                    ]"
                  >
                    {{ page }}
                  </button>
                </div>

                <button 
                  @click="changePage(exams.meta.current_page + 1)"
                  :disabled="!exams.links.next"
                  class="px-3 py-1 text-sm rounded-md transition-colors"
                  :class="[
                    exams.links.next 
                      ? 'text-gray-700 hover:bg-gray-100' 
                      : 'text-gray-400 cursor-not-allowed'
                  ]"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add AI Generation Modal -->
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
        <div class="flex min-h-full items-center justify-center p-4">
          <DialogPanel class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
              <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                Generate Exam with AI
              </DialogTitle>
              <button @click="showAIModal = false" class="text-gray-400 hover:text-gray-500">
                <XMarkIcon class="h-6 w-6" />
              </button>
            </div>

            <form @submit.prevent="generateExam" class="mt-4 space-y-6">
              <div class="grid grid-cols-1 gap-6">
                <!-- Title Input -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Exam Title</label>
                  <input
                    v-model="aiForm.title"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Enter exam title"
                    required
                  />
                </div>

                <!-- Topic Input -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Topic</label>
                  <input
                    v-model="aiForm.topic"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g., JavaScript Basics, Python OOP"
                    required
                  />
                </div>

                <!-- Two columns layout -->
                <div class="grid grid-cols-2 gap-4">
                  <!-- Difficulty Selection -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Difficulty</label>
                    <select
                      v-model="aiForm.difficulty"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option value="easy">Easy</option>
                      <option value="medium">Medium</option>
                      <option value="hard">Hard</option>
                    </select>
                  </div>

                  <!-- Question Count -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Number of Questions</label>
                    <input
                      v-model.number="aiForm.questionCount"
                      type="number"
                      min="5"
                      max="20"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="mt-6 border-t border-gray-200 pt-4">
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="showAIModal = false"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="generating"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
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
                    {{ generating ? 'Generating...' : 'Generate Questions' }}
                  </button>
                </div>
              </div>
            </form>
          </DialogPanel>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Preview Modal -->
  <TransitionRoot appear :show="showPreviewModal" as="template">
    <Dialog as="div" @close="showPreviewModal = false" class="relative z-10">
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
              <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                Review Generated Exam
              </DialogTitle>
              <button 
                @click="showPreviewModal = false"
                class="text-gray-400 hover:text-gray-500"
              >
                <XMarkIcon class="h-6 w-6" />
              </button>
            </div>

            <!-- Exam Preview -->
            <div class="mt-4">
              <h4 class="text-xl font-medium text-gray-900">{{ generatedExam.title }}</h4>
              <p class="mt-1 text-sm text-gray-500">{{ generatedExam.topic }} • {{ generatedExam.difficulty }}</p>
            </div>

            <!-- Questions Preview -->
            <div class="mt-6 space-y-6 max-h-[60vh] overflow-y-auto">
              <div v-for="(question, index) in generatedExam.questions" :key="index" 
                   class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-start justify-between">
                  <h4 class="text-base font-medium text-gray-900">
                    Question {{ index + 1 }}
                  </h4>
                  <button 
                    @click="removeQuestion(index)"
                    class="text-red-500 hover:text-red-700"
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </div>
                
                <p class="mt-2 text-gray-700">{{ question.question }}</p>
                
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

                <div class="mt-3 text-sm text-gray-500">
                  <span class="font-medium">Explanation:</span>
                  {{ question.explanation }}
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 border-t border-gray-200 pt-4 flex justify-between items-center">
              <p class="text-sm text-gray-500">
                {{ generatedExam.questions.length }} questions
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
                  :disabled="saving || generatedExam.questions.length === 0"
                  @click="saveExam"
                  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg
                    v-if="saving"
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
                  {{ saving ? 'Saving...' : 'Save Exam' }}
                </button>
              </div>
            </div>
          </DialogPanel>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import SearchFilterPanel from '@/Components/admin/SearchFilterPanel.vue'
import DefaultExamIcon from '@/Components/DefaultExamIcon.vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { XMarkIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'

// Update exams ref to match API response structure
const exams = ref({
  data: [],
  meta: {
    current_page: 1,
    from: 1,
    last_page: 1,
    per_page: 10,
    to: 0,
    total: 0
  },
  links: {
    first: null,
    last: null,
    prev: null,
    next: null
  }
})
const searchQuery = ref('')
const selectedStatus = ref('')
const loading = ref(true)
const error = ref(null)
const showAIModal = ref(false)
const showPreviewModal = ref(false)
const generating = ref(false)
const saving = ref(false)
const aiForm = ref({
  title: '',
  topic: '',
  difficulty: 'medium',
  questionCount: 10
})
const generatedExam = ref({
  title: '',
  topic: '',
  difficulty: '',
  questions: []
})

const statusFilters = [
  { value: 'draft', label: 'Draft' },
  { value: 'published', label: 'Published' },
  { value: 'archived', label: 'Archived' }
]

// Add new refs for date filtering
const selectedDateRange = ref('')
const customStartDate = ref('')
const customEndDate = ref('')

// Add filtered exams computed property
const filteredExams = computed(() => {
  if (!exams.value?.data) return []
  
  return exams.value.data.filter(exam => {
    // Search in title and description
    const searchTerm = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !searchTerm || 
      exam.title.toLowerCase().includes(searchTerm) ||
      (exam.description && exam.description.toLowerCase().includes(searchTerm))
    
    // Status filter
    const matchesStatus = !selectedStatus.value || exam.status === selectedStatus.value
    
    // Date filter
    const matchesDate = getDateFilterCondition(exam.created_at)
    
    return matchesSearch && matchesStatus && matchesDate
  })
})

// Calculate page numbers to show
const pageNumbers = computed(() => {
  const pages = []
  const total = exams.value.meta.last_page
  const current = exams.value.meta.current_page

  // Show maximum 5 page numbers
  for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
    pages.push(i)
  }
  return pages
})

// Fetch exams with pagination
const fetchExams = async (page = 1) => {
  try {
    loading.value = true
    const params = new URLSearchParams({
      page,
      search: searchQuery.value,
      status: selectedStatus.value,
      date_range: selectedDateRange.value,
      start_date: customStartDate.value,
      end_date: customEndDate.value
    })

    const response = await axios.get(`/admin/exams?${params}`)
    exams.value = response.data
  } catch (error) {
    console.error('Error fetching exams:', error)
    toast.error('Failed to fetch exams')
  } finally {
    loading.value = false
  }
}

// Handle page change
const changePage = (page) => {
  if (page < 1 || page > exams.value.meta.last_page) return
  fetchExams(page)
}

onMounted(() => {
  fetchExams()
})

async function deleteExam(exam) {
  if (!confirm(`Are you sure you want to delete ${exam.title}?`)) return
  
  try {
    await axios.delete(`/admin/exams/${exam.id}`)
    exams.value.data = exams.value.data.filter(e => e.id !== exam.id)
  } catch (error) {
    console.error('Error deleting exam:', error)
  }
}

const toast = useToast()

async function generateExam() {
  generating.value = true
  try {
    const { data } = await axios.post('/admin/exams/generate', aiForm.value)
    
    generatedExam.value = {
      title: aiForm.value.title,
      topic: aiForm.value.topic,
      difficulty: aiForm.value.difficulty,
      questions: data.questions
    }
    
    showAIModal.value = false
    showPreviewModal.value = true
    toast.success('Exam generated successfully!')
  } catch (error) {
    console.error('Error generating exam:', error)
    toast.error(error.response?.data?.message || 'Failed to generate exam')
  } finally {
    generating.value = false
  }
}

async function saveExam() {
  saving.value = true
  try {
    const { data } = await axios.post('/admin/exams/save-generated', {
      title: generatedExam.value.title,
      topic: generatedExam.value.topic,
      difficulty: generatedExam.value.difficulty,
      questions: generatedExam.value.questions
    })
    
    exams.value.data.unshift(data.exam)
    showPreviewModal.value = false
    toast.success('Exam saved successfully!')
    
    // Reset forms
    generatedExam.value = { title: '', topic: '', difficulty: '', questions: [] }
    aiForm.value = { title: '', topic: '', difficulty: 'medium', questionCount: 10 }
  } catch (error) {
    console.error('Error saving exam:', error)
    toast.error(error.response?.data?.message || 'Failed to save exam')
  } finally {
    saving.value = false
  }
}

// Preview question removal function
function removeQuestion(index) {
  generatedExam.value.questions.splice(index, 1)
  
  if (generatedExam.value.questions.length === 0) {
    toast.warning('All questions removed. Generate new questions or cancel.')
  }
}

/**
 * togglePublishStatus
 * @param exam 
 */
async function togglePublishStatus(exam) {
  try {
    const newStatus = exam.status === 'draft' ? 'published' : 'draft'
    const { data } = await axios.put(`/admin/exams/${exam.id}/publish`, {
      status: newStatus
    })
    
    // Update exam status in the list
    const index = exams.value.data.findIndex(e => e.id === exam.id)
    if (index !== -1) {
      exams.value.data[index].status = newStatus
    }
    
    toast.success(
      newStatus === 'published' 
        ? 'Exam published successfully!' 
        : 'Exam unpublished successfully!'
    )
  } catch (error) {
    console.error('Error updating exam status:', error)
    toast.error(
      error.response?.data?.message || 
      'Failed to update exam status'
    )
  }
}

// Add helper function for date filtering
function getDateFilterCondition(dateStr) {
  if (!selectedDateRange.value) return true
  
  const date = new Date(dateStr)
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  
  switch (selectedDateRange.value) {
    case 'today':
      return date >= today
    case 'yesterday':
      const yesterday = new Date(today)
      yesterday.setDate(yesterday.getDate() - 1)
      return date >= yesterday && date < today
    case 'last7days':
      const last7Days = new Date(today)
      last7Days.setDate(last7Days.getDate() - 7)
      return date >= last7Days
    case 'last30days':
      const last30Days = new Date(today)
      last30Days.setDate(last30Days.getDate() - 30)
      return date >= last30Days
    case 'thisMonth':
      return date.getMonth() === today.getMonth() && 
             date.getFullYear() === today.getFullYear()
    case 'lastMonth':
      const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1)
      return date.getMonth() === lastMonth.getMonth() && 
             date.getFullYear() === lastMonth.getFullYear()
    case 'custom':
      const start = customStartDate.value ? new Date(customStartDate.value) : null
      const end = customEndDate.value ? new Date(customEndDate.value) : null
      if (!start && !end) return true
      if (start && !end) return date >= start
      if (!start && end) return date <= end
      return date >= start && date <= end
    default:
      return true
  }
}

// Add watchers for search inputs
watch([searchQuery, selectedStatus, selectedDateRange], () => {
  fetchExams(1) // Reset to first page when filters change
}, { debounce: 300 }) // Add debounce to prevent too many requests
</script>