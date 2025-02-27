<template>
  <div>
    <div v-if="exam" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <!-- Exam Header -->
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ exam.title }}</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ exam.description }}</p>
      </div>

      <!-- Exam Details -->
      <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Duration</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ exam.duration }} minutes</dd>
          </div>
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Questions</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ exam.total_questions }}</dd>
          </div>
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Passing Score</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ exam.passing_score }}%</dd>
          </div>
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Attempts Allowed</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ exam.max_attempts }}</dd>
          </div>
        </dl>
      </div>

      <!-- Start Exam Button -->
      <div class="bg-gray-50 px-4 py-5 sm:px-6">
        <div class="flex justify-end">
          <button
            @click="startExam"
            :disabled="loading"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ loading ? 'Starting...' : 'Start Exam' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const exam = ref(null)
const loading = ref(false)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/exams/${route.params.id}`);
    exam.value = data.data;
  } catch (error) {
    console.error('Error loading exam:', error)
  }
})

const startExam = async () => {
  loading.value = true
  try { 
    const { data } = await axios.post(`/exams/${route.params.id}/start`);
    
    router.push({ 
      name: 'exams.attempt', 
      params: { 
        id: route.params.id,
        attemptId: data.attempt_id 
      }
    })
  } catch (error) {
    // Improved error handling
    if (error.response?.status === 403) {
      alert(error.response.data.message || 'Maximum attempts reached')
    } else {
      alert('Error starting exam. Please try again.')
    }
    console.error('Error starting exam:', error)
  } finally {
    loading.value = false
  }
}
</script>