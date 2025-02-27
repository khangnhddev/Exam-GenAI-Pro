<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Question Bank</h1>
          <p class="mt-2 text-sm text-gray-700">Manage all questions</p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0">
          <button
            @click="showAIModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            AI Generate
          </button>
          <router-link
            :to="{ name: 'admin.questions.create' }"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Add Question
          </router-link>
        </div>
      </div>

      <!-- Question List -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul v-if="questions?.length > 0" role="list" class="divide-y divide-gray-200">
          <li v-for="question in questions" :key="question.id">
            <div class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-indigo-600 truncate">
                  {{ question.text }}
                </p>
                <div class="ml-2 flex-shrink-0 flex">
                  <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                     :class="question.type === 'single' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                  >
                    {{ question.type === 'single' ? 'Single Choice' : 'Multiple Choice' }}
                  </p>
                </div>
              </div>
              <div class="mt-2 sm:flex sm:justify-between">
                <div class="sm:flex">
                  <p class="flex items-center text-sm text-gray-500">
                    {{ question.options.length }} options
                  </p>
                  <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                    {{ question.points }} points
                  </p>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                  <div class="flex space-x-2">
                    <router-link
                      :to="{ 
                        name: 'admin.questions.edit', 
                        params: { id: question.id }
                      }"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      Edit
                    </router-link>
                    <button
                      @click="deleteQuestion(question.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No questions</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by creating a new question.</p>
        </div>
      </div>
    </div>

    <!-- AI Generator Modal -->
    <div v-if="showAIModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Questions with AI</h3>
        <form @submit.prevent="generateQuestions" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Topic</label>
            <input
              v-model="aiForm.topic"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="e.g. Neural Networks, Deep Learning"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Difficulty</label>
            <select
              v-model="aiForm.difficulty"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="easy">Easy</option>
              <option value="medium">Medium</option>
              <option value="hard">Hard</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Number of Questions</label>
            <input
              v-model.number="aiForm.count"
              type="number"
              required
              min="1"
              max="10"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <div class="flex justify-end space-x-3 pt-4">
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
              class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
            >
              {{ generating ? 'Generating...' : 'Generate' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const questions = ref([])
const showAIModal = ref(false)
const generating = ref(false)

const aiForm = ref({
  topic: '',
  difficulty: 'medium',
  count: 5
})

// Load questions
onMounted(async () => {
  await loadQuestions()
})

async function loadQuestions() {
  try {
    const { data } = await axios.get('/admin/questions');
    questions.value = data.data;
  } catch (error) {
    console.error('Error loading questions:', error)
    questions.value = []
  }
}

async function deleteQuestion(questionId) {
  if (!confirm('Are you sure you want to delete this question?')) return

  try {
    await axios.delete(`/api/admin/questions/${questionId}`)
    questions.value = questions.value.filter(q => q.id !== questionId)
  } catch (error) {
    console.error('Error deleting question:', error)
  }
}

async function generateQuestions() {
  generating.value = true
  try {
    const { data } = await axios.post('/admin/questions/generate', {
      topic: aiForm.value.topic,
      difficulty: aiForm.value.difficulty,
      count: aiForm.value.count
    })
    await loadQuestions()
    showAIModal.value = false
  } catch (error) {
    console.error('Error generating questions:', error)
  } finally {
    generating.value = false
  }
}
</script>