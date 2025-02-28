<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Knowledge Base
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Upload and manage learning materials for AI processing
          </p>
        </div>
      </div>

      <!-- Upload Section -->
      <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
            :class="{ 'border-indigo-500': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
          >
            <div class="space-y-1 text-center">
              <svg
                class="mx-auto h-12 w-12 text-gray-400"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 48 48"
              >
                <path
                  d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                  <span>Upload a file</span>
                  <input 
                    type="file" 
                    class="sr-only" 
                    @change="handleFileSelect"
                    accept=".pdf,.docx,.txt"
                  >
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">
                PDF, DOCX, TXT up to 10MB
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Files List -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  File Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Chunks
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Uploaded
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="file in files" :key="file.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ file.filename }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': file.status === 'processed',
                      'bg-yellow-100 text-yellow-800': file.status === 'processing',
                      'bg-red-100 text-red-800': file.status === 'failed'
                    }"
                  >
                    {{ file.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ file.chunks_count || 0 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(file.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button
                    v-if="file.status === 'failed'"
                    @click="reprocessFile(file)"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Retry
                  </button>
                  <button
                    @click="deleteFile(file)"
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
import { ref, onMounted } from 'vue'
import { format } from 'date-fns'
import axios from 'axios'

const files = ref([])
const isDragging = ref(false)
const loading = ref(true)

async function fetchFiles() {
  try {
    const { data } = await axios.get('/admin/knowledge-base')
    files.value = data.data
  } catch (error) {
    console.error('Error fetching files:', error)
  } finally {
    loading.value = false
  }
}

async function handleFileSelect(event) {
  const file = event.target.files[0]
  if (file) {
    await uploadFile(file)
  }
}

async function handleDrop(event) {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  if (file) {
    await uploadFile(file)
  }
}

async function uploadFile(file) {
  const formData = new FormData()
  formData.append('file', file)

  try {
    const { data } = await axios.post('/api/v1/admin/knowledge-base/upload', formData)
    files.value.unshift(data.data)
  } catch (error) {
    console.error('Error uploading file:', error)
  }
}

async function deleteFile(file) {
  if (!confirm(`Are you sure you want to delete ${file.filename}?`)) return

  try {
    await axios.delete(`/admin/knowledge-base/${file.id}`)
    files.value = files.value.filter(f => f.id !== file.id)
  } catch (error) {
    console.error('Error deleting file:', error)
  }
}

async function reprocessFile(file) {
  try {
    const { data } = await axios.post(`/admin/knowledge-base/${file.id}/reprocess`)
    const index = files.value.findIndex(f => f.id === file.id)
    if (index !== -1) {
      files.value[index] = data.data
    }
  } catch (error) {
    console.error('Error reprocessing file:', error)
  }
}

function formatDate(date) {
  return format(new Date(date), 'MMM dd, yyyy HH:mm')
}

onMounted(() => {
   console.log('Fetching files...');
  fetchFiles();
})
</script>