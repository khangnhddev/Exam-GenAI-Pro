<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header with Back and Add Question -->
      <div class="flex items-center mb-4">
        <button @click="router.push({ name: 'admin.exams.index' })" 
          class="mr-4 text-sm text-gray-700 hover:text-gray-900 flex items-center">
          <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Exams
        </button>
      </div>
      <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ exam?.title }}</h1>
          <p class="mt-1 text-sm text-gray-500">Total Questions: {{ questions.length }}</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <button @click="router.push({ name: 'admin.questions.create', params: { examId } })"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Question
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="questions.length === 0" class="bg-white shadow rounded-lg p-8">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No questions</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by creating a new question.</p>
        </div>
      </div>

      <!-- Questions List -->
      <div v-else class="bg-white shadow rounded-lg divide-y divide-gray-200">
        <div v-for="(question, index) in paginatedQuestions" :key="question.id" class="p-6">
          <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-medium">
                {{ index + 1 }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">{{ question.text }}</p>
                <div class="mt-2 space-y-2">
                  <div v-for="option in question.options" :key="option.id"
                    class="flex items-center space-x-3 text-sm">
                    <span class="flex-shrink-0 w-4 h-4">
                      <svg v-if="option.is_correct" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </span>
                    <span :class="{'text-green-600 font-medium': option.is_correct, 'text-gray-500': !option.is_correct}">
                      {{ option.option_text }}
                    </span>
                  </div>
                </div>
                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                  <span>{{ question.type === 'single' ? 'Single Choice' : 'Multiple Choice' }}</span>
                  <span>{{ question.points }} points</span>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <button @click="editQuestion(question)"
                class="text-gray-400 hover:text-gray-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button @click="deleteQuestion(question)"
                class="text-red-400 hover:text-red-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination Controls -->
      <div class="flex justify-between items-center mt-4">
        <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-200 rounded-md">
          Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 bg-gray-200 rounded-md">
          Next
        </button>
      </div>

      <!-- Question Form Modal -->
      <TransitionRoot appear :show="showQuestionForm" as="template">
        <Dialog as="div" @close="showQuestionForm = false" class="relative z-10">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
            leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
              <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 shadow-xl transition-all">
                  <div class="flex items-center justify-between mb-4">
                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                      {{ editingQuestion ? 'Edit Question' : 'Add New Question' }}
                    </DialogTitle>
                    <button @click="closeQuestionForm" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Close</span>
                      <XMarkIcon class="h-6 w-6" />
                    </button>
                  </div>

                  <!-- Enhanced Form Design -->
                  <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                    <QuestionForm 
                      :exam-id="examId" 
                      :question-data="editingQuestion"
                      @question-saved="onQuestionSaved" 
                      @cancel="closeQuestionForm" 
                    />
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
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import QuestionForm from '../questions/QuestionForm.vue'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const examId = route.params.examId
const loading = ref(true)
const exam = ref(null)
const questions = ref([])
const showQuestionForm = ref(false)
const editingQuestion = ref(null)
const currentPage = ref(1)
const questionsPerPage = 3

const paginatedQuestions = computed(() => {
  const start = (currentPage.value - 1) * questionsPerPage
  const end = start + questionsPerPage
  return questions.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(questions.value.length / questionsPerPage)
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

// Fetch exam and questions
const fetchData = async () => {
  try {
    loading.value = true
    const [examResponse, questionsResponse] = await Promise.all([
      axios.get(`/admin/exams/${examId}`),
      axios.get(`/admin/exams/${examId}/questions`)
    ])
    exam.value = examResponse.data.data
    questions.value = questionsResponse.data.data
  } catch (error) {
    console.error('Error fetching data:', error)
    toast.error('Failed to load exam data')
  } finally {
    loading.value = false
  }
}

// Edit question
const editQuestion = (question) => {
  editingQuestion.value = question
  showQuestionForm.value = true
}

// Delete question
const deleteQuestion = async (question) => {
  if (!confirm('Are you sure you want to delete this question?')) return

  try {
    await axios.delete(`/admin/exams/${examId}/questions/${question.id}`)
    questions.value = questions.value.filter(q => q.id !== question.id)
    toast.success('Question deleted successfully')
  } catch (error) {
    console.error('Error deleting question:', error)
    toast.error('Failed to delete question')
  }
}

// Handle question saved
const onQuestionSaved = () => {
  closeQuestionForm()
  fetchData()
  toast.success(editingQuestion.value ? 'Question updated successfully' : 'Question added successfully')
}

// Close question form
const closeQuestionForm = () => {
  showQuestionForm.value = false
  editingQuestion.value = null
}

// Load data when component mounts
onMounted(() => {
  fetchData()
})
</script>