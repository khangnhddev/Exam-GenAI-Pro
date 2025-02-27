<template>
  <div>
    <!-- Back Button -->
    <div class="mb-4">
      <router-link 
        :to="{ name: 'admin.exams.index' }" 
        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back
      </router-link>
    </div>

    <div class="bg-white shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
          <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ exam.title || 'Loading...' }} - Questions</h1>
            <p class="mt-2 text-sm text-gray-700">
              Manage exam questions and their options
            </p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
            <button
              @click="showCreateModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Add Question
            </button>
            <button
              @click="generateQuestions"
              :disabled="isGenerating"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
            >
              <svg v-if="isGenerating" class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.316 6.246c.008-.09.008-.18 0-.27 0-.022 0-.045-.008-.067l-.01-.06c-.008-.022-.015-.045-.023-.067-.007-.023-.016-.045-.024-.068l-.016-.045c-.023-.045-.046-.09-.077-.134-.007-.008-.015-.022-.023-.03-.03-.037-.061-.075-.1-.112l-.023-.022c-.038-.038-.077-.076-.12-.106l-.06-.045c-.03-.022-.06-.037-.091-.06l-.068-.03c-.037-.015-.075-.03-.112-.038l-.09-.022c-.052-.015-.105-.023-.157-.03-.038 0-.075-.008-.112-.008-.06 0-.12 0-.18.008l-.09.015c-.06.008-.119.023-.18.038l-.075.022c-.015.008-.03.008-.045.015-.067.023-.134.053-.19.083l-.106.06c-.046.03-.083.06-.121.091-.022.015-.045.037-.068.052-.008.008-.015.015-.023.022-.015.016-.03.023-.045.038-.03.03-.06.052-.083.083l-.037.038c-.03.038-.06.075-.091.12-.015.023-.03.045-.045.067-.015.023-.03.053-.046.075-.015.03-.03.06-.038.09-.015.03-.03.067-.038.098-.015.045-.023.09-.03.135-.008.03-.015.067-.015.098-.008.052-.008.105-.008.157V13.17c0 .112.015.217.045.322.03.09.075.181.135.271.06.09.135.165.226.234.045.037.098.068.143.098.075.045.157.083.246.112.068.023.142.038.216.046.083.008.166.015.25.015h.142c.104-.008.208-.03.312-.06l.09-.03c.09-.038.18-.09.254-.151.106-.09.181-.195.234-.32.06-.152.083-.32.068-.49v-.015c0-.022-.008-.037-.008-.06-.008-.037-.015-.082-.03-.119l-.023-.067c-.03-.075-.068-.143-.12-.21-.052-.06-.105-.12-.165-.165-.052-.045-.112-.083-.172-.112l-.082-.038c-.038-.015-.075-.022-.113-.03-.045-.008-.09-.015-.142-.023-.037 0-.075-.008-.112-.008h-.046c-.06 0-.12.008-.18.023-.045.008-.09.023-.134.038zm-7.516 5.256l-.06.03c-.082.045-.157.098-.225.158-.09.083-.157.18-.21.286-.06.12-.09.255-.082.397v.023c0 .03.008.06.016.09.007.037.015.075.03.112l.023.067c.03.076.069.144.12.21.053.06.106.12.166.166.052.045.112.082.172.112l.083.038c.037.015.075.022.112.03.045.008.09.015.142.022.038 0 .075.008.113.008h.037c.068 0 .134-.008.2-.023.053-.007.098-.022.143-.037l.059-.03c.083-.046.158-.099.226-.159.09-.083.157-.18.21-.285.06-.12.09-.255.082-.397v-.023c0-.03-.008-.06-.015-.09-.008-.038-.015-.075-.03-.113l-.023-.067c-.03-.075-.068-.143-.12-.21-.053-.06-.106-.119-.166-.165-.052-.045-.112-.083-.172-.113l-.083-.037c-.037-.015-.075-.023-.112-.03-.045-.008-.09-.016-.142-.023-.038 0-.075-.008-.113-.008h-.037c-.069 0-.134.008-.2.023-.053.008-.098.023-.143.038z"/>
              </svg>
              Generate with AI
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="mt-8 flex justify-center">
          <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm text-indigo-500">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading questions...
          </div>
        </div>

        <!-- Questions List -->
        <div v-else class="mt-8 flex flex-col">
          <div v-if="questions.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No questions</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new question or generate with AI.</p>
          </div>

          <div v-else class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Question</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Options</th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Actions</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="question in questions" :key="question.id">
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ question.text }}dsdfda
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                          :class="{
                            'bg-blue-100 text-blue-800': question.type === 'multiple_choice',
                            'bg-green-100 text-green-800': question.type === 'true_false',
                            'bg-purple-100 text-purple-800': question.type === 'short_answer'
                          }"
                        >
                          {{ formatQuestionType(question.type) }}
                        </span>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ question.options?.length || 0 }}
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button
                          @click="editQuestion(question)"
                          class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-4"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                          Edit
                        </button>
                        <button
                          @click="deleteQuestion(question)"
                          class="inline-flex items-center text-red-600 hover:text-red-900"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                          </svg>
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
      </div>

      <!-- Create Question Modal -->
      <QuestionModal 
        v-if="showCreateModal" 
        :exam-id="route.params.examId"
        @close="showCreateModal = false"
        @question-created="onQuestionCreated"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import QuestionModal from './QuestionModal.vue'

const route = useRoute()
const exam = ref({})
const questions = ref([])
const showCreateModal = ref(false)
const loading = ref(true)
const isGenerating = ref(false)

onMounted(async () => {
  try {
    console.log('Fetch question exam id');
    const [examResponse, questionsResponse] = await Promise.all([
      axios.get(`/admin/exams/${route.params.examId}`),
      axios.get(`/admin/exams/${route.params.examId}/questions`)
    ])
    
    exam.value = examResponse.data.data;
    questions.value = questionsResponse.data.data;
  } catch (error) {
    console.error('Error loading exam data:', error)
  } finally {
    loading.value = false
  }
})

async function generateQuestions() {
  if (isGenerating.value) return
  
  isGenerating.value = true
  try {
    const { data } = await axios.post(`/api/v1/admin/exams/${route.params.examId}/questions/generate`)
    questions.value = [...questions.value, ...data]
  } catch (error) {
    console.error('Error generating questions:', error)
  } finally {
    isGenerating.value = false
  }
}

function editQuestion(question) {
  // Will be implemented with QuestionModal
}

async function deleteQuestion(question) {
  if (!confirm('Are you sure you want to delete this question?')) return
  
  try {
    await axios.delete(`/api/v1/admin/exams/${route.params.examId}/questions/${question.id}`)
    questions.value = questions.value.filter(q => q.id !== question.id)
  } catch (error) {
    console.error('Error deleting question:', error)
  }
}

function onQuestionCreated(newQuestion) {
  questions.value.push(newQuestion)
  showCreateModal.value = false
}

function formatQuestionType(type) {
  if (!type) return 'Unknown'
  return type.split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

// Add computed property for formatted questions
const formattedQuestions = computed(() => {
  return questions.value.map(q => ({
    ...q,
    formattedType: formatQuestionType(q.type)
  }))
})
</script>