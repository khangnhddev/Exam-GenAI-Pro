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
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <router-link
            :to="{ name: 'admin.exams.create' }"
            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
        v-model:search="searchQuery"
        v-model:filter="selectedStatus"
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
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="exam in filteredExams" :key="exam.id">
                <td class="px-6 py-4 whitespace-nowrap">
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
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ exam.title }}</div>
                      <div class="text-sm text-gray-500">{{ exam.description }}</div>
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
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <router-link
                    :to="{ name: 'admin.exams.questions', params: { examId: exam.id } }"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Questions
                  </router-link>
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
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SearchFilterPanel from '@/Components/admin/SearchFilterPanel.vue'
import DefaultExamIcon from '@/components/DefaultExamIcon.vue'

const exams = ref([])
const searchQuery = ref('')
const selectedStatus = ref('')
const loading = ref(true)
const error = ref(null)

const statusFilters = [
  { value: 'draft', label: 'Draft' },
  { value: 'published', label: 'Published' },
  { value: 'archived', label: 'Archived' }
]

// Add filtered exams computed property
const filteredExams = computed(() => {
  if (!exams.value) return []
  
  return exams.value.filter(exam => {
    const matchesSearch = !searchQuery.value || 
      exam.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      exam.description?.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesStatus = !selectedStatus.value || exam.status === selectedStatus.value
    
    return matchesSearch && matchesStatus
  })
})

onMounted(async () => {
  try {
    loading.value = true
    error.value = null
    const { data } = await axios.get('/admin/exams')
    exams.value = data.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error loading exams'
    console.error('Error fetching exams:', err)
  } finally {
    loading.value = false
  }
})

async function deleteExam(exam) {
  if (!confirm(`Are you sure you want to delete ${exam.title}?`)) return
  
  try {
    await axios.delete(`/api/v1/admin/exams/${exam.id}`)
    exams.value = exams.value.filter(e => e.id !== exam.id)
  } catch (error) {
    console.error('Error deleting exam:', error)
  }
}
</script>