<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Hero Section -->
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
          Your Achievement Gallery
        </h2>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
          Showcase of your learning journey and professional achievements
        </p>
      </div>
      
      <!-- Achievement Stats with Glass Effect -->
      <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3 max-w-4xl mx-auto mb-16">
        <div class="backdrop-blur-lg bg-white/30 rounded-2xl border border-white/50 shadow-xl p-6">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl">
              <AcademicCapIcon class="h-6 w-6 text-white" />
            </div>
            <div>
              <div class="text-3xl font-bold text-gray-900">{{ certificates.length }}</div>
              <div class="text-sm font-medium text-gray-500">Certificates</div>
            </div>
          </div>
        </div>

        <div class="backdrop-blur-lg bg-white/30 rounded-2xl border border-white/50 shadow-xl p-6">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl">
              <TrophyIcon class="h-6 w-6 text-white" />
            </div>
            <div>
              <div class="text-3xl font-bold text-gray-900">
                {{ certificates.filter(c => c.score >= 90).length }}
              </div>
              <div class="text-sm font-medium text-gray-500">Excellence</div>
            </div>
          </div>
        </div>

        <div class="backdrop-blur-lg bg-white/30 rounded-2xl border border-white/50 shadow-xl p-6">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl">
              <ChartBarIcon class="h-6 w-6 text-white" />
            </div>
            <div>
              <div class="text-3xl font-bold text-gray-900">
                {{ certificates.length > 0 ? Math.round(certificates.reduce((acc, curr) => acc + curr.score, 0) / certificates.length) : 0 }}%
              </div>
              <div class="text-sm font-medium text-gray-500">Avg Score</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Replace the Certificates Grid section with this List view -->
      <div v-if="!loading" class="max-w-4xl mx-auto">
        <!-- List of Certificates -->
        <div class="space-y-4">
          <div 
            v-for="certificate in certificates" 
            :key="certificate.id" 
            class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100"
          >
            <div class="relative p-6">
              <div class="flex items-center justify-between">
                <!-- Left side - Certificate Info -->
                <div>
                  <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                    {{ certificate.exam_title }}
                  </h3>
                  <p class="text-sm text-gray-500 mt-0.5">
                    Completed {{ formatDate(certificate.issued_at) }}
                  </p>
                </div>

                <!-- Right side - Score & Action -->
                <div class="flex items-center space-x-6">
                  <span 
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="getScoreClass(certificate.score)"
                  >
                    {{ certificate.score }}%
                  </span>

                  <button
                    @click="viewCertificate(certificate)"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
                  >
                    <EyeIcon class="mr-2 h-5 w-5" />
                    View Certificate
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State (Updated) -->
          <div v-if="certificates.length === 0" class="text-center py-16 px-8 bg-white rounded-xl border border-gray-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 mb-4">
              <TrophyIcon class="h-8 w-8 text-indigo-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Certificates Yet</h3>
            <p class="text-gray-500 max-w-sm mx-auto mb-6">
              Start your learning journey today and earn your first certificate!
            </p>
            <router-link
              :to="{ name: 'exams.index' }"
              class="inline-flex items-center px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
            >
              <AcademicCapIcon class="mr-2 h-5 w-5" />
              Browse Assessments
            </router-link>
          </div>
        </div>
      </div>

      <!-- Loading State (Updated) -->
      <div v-else class="flex justify-center py-16">
        <div class="inline-flex items-center px-6 py-3 rounded-xl text-lg font-semibold text-indigo-600 bg-white shadow-lg">
          <svg class="animate-spin -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Loading your achievements...
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
    certificates.value = data.data;
    console.log(certificates.value);
  } catch (error) {
    console.error('Error loading certificates:', error)
  } finally {
    loading.value = false
  }
})
</script>