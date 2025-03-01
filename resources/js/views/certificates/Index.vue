<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            My Certificates
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            View and download your earned certificates
          </p>
        </div>
      </div>

      <!-- Certificates Grid -->
      <div v-if="!loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="certificate in certificates" 
          :key="certificate.id" 
          class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200"
        >
          <div class="p-6">
            <!-- Certificate Header -->
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900">
                  {{ certificate.exam_title }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                  Completed on {{ formatDate(certificate.completed_at) }}
                </p>
              </div>
              <div class="ml-4">
                <span 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getScoreClass(certificate.score)"
                >
                  {{ certificate.score }}%
                </span>
              </div>
            </div>

            <!-- Certificate Details -->
            <dl class="mt-4 grid grid-cols-2 gap-4 text-sm">
              <div>
                <dt class="text-gray-500">Duration</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ formatDuration(certificate.duration) }}</dd>
              </div>
              <div>
                <dt class="text-gray-500">Questions</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ certificate.total_questions }}</dd>
              </div>
            </dl>

            <!-- Actions -->
            <div class="mt-6 flex space-x-3">
              <button
                @click="downloadCertificate(certificate)"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
              >
                <DocumentArrowDownIcon class="mr-2 h-5 w-5 text-gray-400" />
                Download
              </button>
              <button
                @click="viewCertificate(certificate)"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
              >
                <EyeIcon class="mr-2 h-5 w-5" />
                View
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div 
          v-if="certificates.length === 0" 
          class="sm:col-span-2 lg:col-span-3 text-center py-12 bg-white rounded-lg border border-gray-200"
        >
          <TrophyIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">No certificates yet</h3>
          <p class="mt-1 text-sm text-gray-500">
            Complete exams to earn your certificates
          </p>
          <div class="mt-6">
            <router-link
              :to="{ name: 'exams.index' }"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800"
            >
              <AcademicCapIcon class="mr-2 h-5 w-5" />
              Browse Exams
            </router-link>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { 
  DocumentArrowDownIcon, 
  EyeIcon, 
  TrophyIcon,
  AcademicCapIcon 
} from '@heroicons/vue/24/outline'

const router = useRouter()
const certificates = ref([])
const loading = ref(true)

// Format date helper
function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Format duration helper
function formatDuration(minutes) {
  return `${minutes} min`
}

// Get score class helper
function getScoreClass(score) {
  if (score >= 90) return 'bg-green-100 text-green-800'
  if (score >= 75) return 'bg-blue-100 text-blue-800'
  return 'bg-yellow-100 text-yellow-800'
}

// Download certificate
async function downloadCertificate(certificate) {
  try {
    const response = await axios.get(`/certificates/${certificate.id}/download`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `certificate-${certificate.id}.pdf`)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}

// View certificate
function viewCertificate(certificate) {
  router.push({ 
    name: 'certificates.show', 
    params: { id: certificate.id }
  })
}


onMounted(async () => {
  try {
    const { data } = await axios.get('/certificates');
    certificates.value = data.data;
  } catch (error) {
    console.error('Error loading certificates:', error)
  } finally {
    loading.value = false
  }
})
</script>