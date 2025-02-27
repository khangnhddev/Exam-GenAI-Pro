<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            My Certificates
          </h2>
        </div>
      </div>

      <!-- Certificates Grid -->
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="certificate in certificates" 
          :key="certificate.id" 
          class="bg-white overflow-hidden shadow rounded-lg"
        >
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900">
              {{ certificate.exam_title }}
            </h3>
            <div class="mt-4 grid grid-cols-2 gap-4">
              <div>
                <dt class="text-xs font-medium text-gray-500">Issue Date</dt>
                <dd class="mt-1 text-sm text-gray-900">
                  {{ formatDate(certificate.created_at) }}
                </dd>
              </div>
              <div>
                <dt class="text-xs font-medium text-gray-500">Score</dt>
                <dd class="mt-1 text-sm text-gray-900">
                  {{ certificate.score }}%
                </dd>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center">
            <span 
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                certificate.is_valid 
                  ? 'bg-green-100 text-green-800'
                  : 'bg-red-100 text-red-800'
              ]"
            >
              {{ certificate.is_valid ? 'Valid' : 'Revoked' }}
            </span>
            <div class="flex space-x-3">
              <button
                @click="viewCertificate(certificate)"
                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
              >
                View
              </button>
              <button
                @click="downloadCertificate(certificate)"
                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
              >
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!certificates.length" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No certificates yet</h3>
        <p class="mt-1 text-sm text-gray-500">Complete exams to earn certificates.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const certificates = ref([])

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/certificates')
    certificates.value = data.data
  } catch (error) {
    console.error('Error loading certificates:', error)
  }
})

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString()
}

function viewCertificate(certificate) {
  router.push({
    name: 'certificates.show',
    params: { id: certificate.id }
  })
}

async function downloadCertificate(certificate) {
  try {
    const response = await axios.get(`/api/v1/certificates/${certificate.id}/download`, {
      responseType: 'blob'
    })
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `certificate-${certificate.id}.pdf`)
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}
</script>