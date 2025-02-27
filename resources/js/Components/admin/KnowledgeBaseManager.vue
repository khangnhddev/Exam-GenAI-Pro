<template>
  <div class="p-6">
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-800">Knowledge Base Management</h2>
      <button @click="refreshEmbeddings" 
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
        Refresh Embeddings
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900">Total Entries</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ stats.totalEntries }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900">File Types</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ stats.fileTypes }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900">Latest Update</h3>
        <p class="text-lg text-gray-600">{{ formatDate(stats.lastUpdate) }}</p>
      </div>
    </div>

    <!-- Knowledge Base Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Content Preview</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="entry in knowledgeBase" :key="entry.id">
            <td class="px-6 py-4">{{ entry.file_name }}</td>
            <td class="px-6 py-4">{{ entry.file_type }}</td>
            <td class="px-6 py-4">
              <div class="truncate max-w-md">{{ entry.content }}</div>
            </td>
            <td class="px-6 py-4">{{ formatDate(entry.created_at) }}</td>
            <td class="px-6 py-4">
              <button @click="deleteEntry(entry.id)" 
                      class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const knowledgeBase = ref([])
const stats = ref({
  totalEntries: 0,
  fileTypes: 0,
  lastUpdate: null
})

onMounted(async () => {
  await fetchKnowledgeBase()
  await fetchStats()
})

async function fetchKnowledgeBase() {
  try {
    const { data } = await axios.get('/api/admin/knowledge-base')
    knowledgeBase.value = data.entries
  } catch (error) {
    console.error('Error fetching knowledge base:', error)
  }
}

async function fetchStats() {
  try {
    const { data } = await axios.get('/api/admin/knowledge-base/stats')
    stats.value = data
  } catch (error) {
    console.error('Error fetching stats:', error)
  }
}

async function refreshEmbeddings() {
  try {
    await axios.post('/api/admin/knowledge-base/refresh-embeddings')
    await fetchKnowledgeBase()
  } catch (error) {
    console.error('Error refreshing embeddings:', error)
  }
}

async function deleteEntry(id) {
  if (confirm('Are you sure you want to delete this entry?')) {
    try {
      await axios.delete(`/api/admin/knowledge-base/${id}`)
      await fetchKnowledgeBase()
      await fetchStats()
    } catch (error) {
      console.error('Error deleting entry:', error)
    }
  }
}

function formatDate(date) {
  return new Date(date).toLocaleString()
}
</script>