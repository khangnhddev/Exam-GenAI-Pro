<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header with Achievement Style -->
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
          My Achievements
        </h2>
        <p class="mt-3 text-lg text-gray-500 max-w-2xl mx-auto">
          View and download your earned certificates from completed assessments
        </p>
        
        <!-- Achievement Stats -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-3 max-w-3xl mx-auto">
          <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex items-center justify-center">
                <AcademicCapIcon class="h-8 w-8 text-indigo-600" />
              </div>
              <div class="mt-3 text-center">
                <div class="text-2xl font-semibold text-gray-900">{{ certificates.length }}</div>
                <div class="text-sm font-medium text-gray-500">Certificates Earned</div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex items-center justify-center">
                <TrophyIcon class="h-8 w-8 text-purple-600" />
              </div>
              <div class="mt-3 text-center">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ certificates.filter(c => c.score >= 90).length }}
                </div>
                <div class="text-sm font-medium text-gray-500">Excellence Awards</div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex items-center justify-center">
                <ChartBarIcon class="h-8 w-8 text-indigo-600" />
              </div>
              <div class="mt-3 text-center">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ certificates.length > 0 ? Math.round(certificates.reduce((acc, curr) => acc + curr.score, 0) / certificates.length) : 0 }}%
                </div>
                <div class="text-sm font-medium text-gray-500">Average Score</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Certificates Grid -->
      <div v-if="!loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="certificate in certificates" 
          :key="certificate.id" 
          class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200 hover:shadow-xl transition-all duration-300"
        >
          <div class="p-6">
            <!-- Certificate Header with Badge -->
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                      <AcademicCapIcon class="h-6 w-6 text-white" />
                    </div>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">
                      {{ certificate.exam_title }}
                    </h3>
                    <p class="text-sm text-gray-500">
                      Completed on {{ formatDate(certificate.completed_at) }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="ml-4">
                <span 
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="getScoreClass(certificate.score)"
                >
                  {{ certificate.score }}%
                </span>
              </div>
            </div>

            <!-- Certificate Details -->
            <div class="mt-6 grid grid-cols-2 gap-6 text-center">
              <div class="border-r border-gray-200">
                <div class="text-sm font-medium text-gray-500">Duration</div>
                <div class="mt-1 text-lg font-semibold text-gray-900">
                  {{ formatDuration(certificate.duration) }}
                </div>
              </div>
              <div>
                <div class="text-sm font-medium text-gray-500">Questions</div>
                <div class="mt-1 text-lg font-semibold text-gray-900">
                  {{ certificate.total_questions }}
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-3">
              <button
                @click="downloadCertificate(certificate)"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
              >
                <DocumentArrowDownIcon class="mr-2 h-5 w-5 text-gray-400" />
                Download
              </button>
              <button
                @click="viewCertificate(certificate)"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
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
          class="sm:col-span-2 lg:col-span-3"
        >
          <div class="text-center py-12 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100">
              <TrophyIcon class="h-8 w-8 text-gray-400" />
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No certificates yet</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
              Start your learning journey by taking exams and earn your first certificate!
            </p>
            <div class="mt-6">
              <router-link
                :to="{ name: 'exams.index' }"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
              >
                <AcademicCapIcon class="mr-2 h-5 w-5" />
                Browse Exams
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else class="flex justify-center py-12">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-indigo-600">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Loading certificates...
        </div>
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
  AcademicCapIcon,
  ChartBarIcon
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
  if (minutes >= 60) {
    const hours = Math.floor(minutes / 60)
    const mins = minutes % 60
    return `${hours}h ${mins}m`
  }
  return `${minutes}m`
}

// Get score class helper
function getScoreClass(score) {
  if (score >= 90) {
    return 'bg-green-100 text-green-800 border border-green-200'
  }
  if (score >= 75) {
    return 'bg-blue-100 text-blue-800 border border-blue-200'
  }
  return 'bg-yellow-100 text-yellow-800 border border-yellow-200'
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
    const { data } = await axios.get('/certificates')
    certificates.value = data.data
  } catch (error) {
    console.error('Error loading certificates:', error)
  } finally {
    loading.value = false
  }
})
</script>