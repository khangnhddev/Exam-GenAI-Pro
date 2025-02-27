<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Add Back Button -->
      <div class="mb-6">
        <button
          @click="$router.back()"
          class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900"
        >
          <svg 
            class="h-5 w-5 mr-1" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M15 19l-7-7 7-7"
            />
          </svg>
          Back
        </button>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <div class="md:flex md:items-center md:justify-between mb-8">
          <h1 class="text-3xl font-bold text-gray-900">
            {{ isEditing ? 'Edit Exam' : 'Create New Exam' }}
          </h1>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
          </div>

          <!-- Duration -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
            <input
              v-model.number="form.duration"
              type="number"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Passing Score -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Passing Score (%)</label>
            <input
              v-model.number="form.passing_score"
              type="number"
              min="0"
              max="100"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Max Attempts -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Max Attempts</label>
            <input
              v-model.number="form.max_attempts"
              type="number"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>

          <!-- Submit Buttons -->
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$router.back()"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {{ isEditing ? 'Update Exam' : 'Create Exam' }}
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

const props = defineProps({
  id: {
    type: [String, Number],
    required: false
  },
  isEditing: {
    type: Boolean,
    default: false
  }
})

const router = useRouter()
const loading = ref(false)
const form = ref({
  title: '',
  description: '',
  duration: 60,
  passing_score: 70,
  max_attempts: 2,
  total_questions: 0,
  status: 'draft'
})

async function loadExam() {
  try {
    loading.value = true
    const { data } = await axios.get(`/admin/exams/${props.id}`);
    
    form.value = {
      title: data.data.title,
      description: data.data.description,
      duration: data.data.duration,
      passing_score: data.data.passing_score,
      max_attempts: data.data.max_attempts,
      total_questions: data.data.total_questions,
      status: data.data.status
    }
  } catch (error) {
    console.error('Failed to load exam:', error)
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  try {
    loading.value = true
    const endpoint = props.isEditing 
      ? `/admin/exams/${props.id}`
      : '/admin/exams'
    
    const method = props.isEditing ? 'put' : 'post'
    
    await axios[method](endpoint, form.value)
    router.push({ name: 'admin.exams.index' })
  } catch (error) {
    console.error('Failed to save exam:', error)
  } finally {
    loading.value = false
  }
}

// Load exam data when component mounts
onMounted(() => {
  if (props.isEditing && props.id) {
    loadExam()
  }
})
</script>