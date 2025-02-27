<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with Back Button and Title -->
    <div class="mb-6 flex justify-between items-center">
      <div class="flex space-x-4">
        <button
          @click="$router.push({ name: 'admin.exams.index' })"
          class="flex items-center text-sm text-gray-600 hover:text-gray-900"
        >
          <svg 
            class="h-5 w-5 mr-1" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M15 19l-7-7 7-7"
            />
          </svg>
          Back to Exams
        </button>
      </div>
      <button
        @click="showAddForm = true"
        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
      >
        Add Question
      </button>
    </div>

    <!-- Title Section -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ exam.title || 'Exam Questions' }}</h1>
      <p class="mt-1 text-sm text-gray-500">Total Questions: {{ questions.length }}</p>
    </div>

    <!-- Add Question Modal -->
    <Modal v-if="showAddForm" @close="closeForm">
      <template #title>{{ editingQuestion ? 'Edit Question' : 'Add New Question' }}</template>
      <template #content>
        <QuestionForm
          :exam-id="examId"
          :question="editingQuestion"
          @saved="handleQuestionAdded"
          @cancel="closeForm"
        />
      </template>
    </Modal>

    <!-- Questions List -->
    <div v-if="questions.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
      <ul class="divide-y divide-gray-200">
        <li v-for="question in questions" :key="question.id" class="p-6 hover:bg-gray-50">
          <div class="space-y-4">
            <!-- Question Header -->
            <div class="flex justify-between items-start">
              <div class="font-medium text-lg">{{ question.text }}</div>
              <div class="flex space-x-2">
                <button
                  @click="editQuestion(question)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  Edit
                </button>
                <button
                  @click="deleteQuestion(question.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </div>
            </div>

            <!-- Question Options -->
            <ul class="space-y-2 ml-4">
              <li v-for="option in question.options" :key="option.id"
                  :class="['flex items-center space-x-2', option.is_correct ? 'text-green-600 font-medium' : '']">
                <span class="w-4">•</span>
                <span>{{ option.option_text }}</span>
                <span v-if="option.is_correct" class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">
                  Correct
                </span>
              </li>
            </ul>

            <!-- Question Metadata -->
            <div class="text-sm text-gray-500 flex space-x-4">
              <span>Points: {{ question.points || 1 }}</span>
              <span>Type: {{ question.type === 'single' ? 'Single Choice' : 'Multiple Choice' }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12 bg-white shadow rounded-lg">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No questions</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new question.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import QuestionForm from '@/components/admin/questions/QuestionForm.vue'
import Modal from '@/components/Modal.vue'  // Uncomment this line

// Add error handling state
const error = ref(null)

const props = defineProps({
  examId: {
    type: [Number, String],
    required: true
  }
})

const exam = ref({})
const questions = ref([])
const showAddForm = ref(false)
const editingQuestion = ref(null)

// Load exam details
async function loadExam() {
  try {
    const { data } = await axios.get(`/admin/exams/${props.examId}`)
    exam.value = data.data
  } catch (error) {
    console.error('Failed to load exam:', error)
  }
}

// Load questions
async function loadQuestions() {
  try {
    const { data } = await axios.get(`/admin/exams/${props.examId}/questions`);
    questions.value = data.data;
    console.log(data.data);
  } catch (error) {
    console.error('Failed to load questions:', error)
  }
}

function handleQuestionAdded(newQuestion) {
  questions.value.unshift(newQuestion)
  closeForm()
}

function editQuestion(question) {
  editingQuestion.value = question
  showAddForm.value = true
}

async function deleteQuestion(id) {
  if (!confirm('Are you sure you want to delete this question?')) return

  try {
    await axios.delete(`/admin/questions/${id}`)
    questions.value = questions.value.filter(q => q.id !== id)
  } catch (error) {
    console.error('Failed to delete question:', error)
  }
}

function closeForm() {
  showAddForm.value = false
  editingQuestion.value = null
}

onMounted(() => {
  loadExam()
  loadQuestions()
})
</script>