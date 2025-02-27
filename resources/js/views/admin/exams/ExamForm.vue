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
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const examId = route.params.examId
const isEditing = computed(() => !!examId)
const loading = ref(false)

const form = ref({
  title: '',
  description: '',
  duration: 60,
  passing_score: 70,
  max_attempts: 3
})

const handleSubmit = async () => {
  loading.value = true
  try {
    const url = isEditing.value
      ? `/admin/exams/${examId}`
      : '/admin/exams'
    
    const method = isEditing.value ? 'put' : 'post'
    
    const { data } = await axios[method](url, form.value)
    router.push({ name: 'admin.exams.index' })
  } catch (error) {
    console.error('Error saving exam:', error)
  } finally {
    loading.value = false
  }
}

// Load exam data if editing
onMounted(async () => {
  if (isEditing.value) {
    loading.value = true
    try {
      const { data } = await axios.get(`/api/v1/admin/exams/${examId}`)
      form.value = {
        title: data.title,
        description: data.description,
        duration: data.duration,
        passing_score: data.passing_score,
        max_attempts: data.max_attempts
      }
    } catch (error) {
      console.error('Error loading exam:', error)
    } finally {
      loading.value = false
    }
  }
})
</script>