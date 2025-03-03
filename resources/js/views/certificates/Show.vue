<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <template v-else-if="certificate">
        <!-- Certificate Header -->
        <div class="mb-8">
          <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
              <li>
                <router-link :to="{ name: 'certificates' }" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                  Certificates
                </router-link>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" />
                  <span class="ml-4 text-sm font-medium text-gray-500">{{ certificate.exam_title }}</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>

        <!-- Certificate Preview -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border-2 border-blue-600">
          <!-- Certificate Header -->
          <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
            <div class="flex items-center justify-between">
              <h2 class="text-xl font-bold text-blue-900">
                Certificate of Completion
              </h2>
              <div class="flex space-x-4">
                <button
                  @click="downloadCertificate"
                  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <DocumentArrowDownIcon class="mr-2 h-5 w-5" />
                  Download
                </button>
                <button
                  @click="shareCertificate"
                  class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <ShareIcon class="mr-2 h-5 w-5 text-blue-500" />
                  Share
                </button>
              </div>
            </div>
          </div>

          <!-- Certificate Content -->
          <div class="px-6 py-8 border-4 border-blue-600 m-4 rounded-lg">
            <div class="max-w-3xl mx-auto text-center">
              <img 
                src="/images/aipro-logo.svg" 
                class="h-16 mx-auto mb-6" 
                alt="AIPro Logo" 
              />
              <h1 class="text-4xl font-bold text-blue-900 mb-4">AIPro Certificate</h1>
              <p class="text-lg text-blue-600 mb-8">Certificate of Achievement in AI Proficiency</p>
              <p class="text-2xl font-semibold text-blue-900 mb-4">
                {{ certificate.user?.name || 'Loading...' }}
              </p>
              <p class="text-lg text-blue-600 mb-8">has successfully completed the course</p>
              <p class="text-3xl font-bold text-blue-900 mb-8">
                {{ certificate.exam?.title || certificate.exam_title }}
              </p>
              <div class="flex justify-center space-x-12 mb-8">
                <div>
                  <p class="text-sm text-blue-500">Issue Date</p>
                  <p class="text-lg font-medium text-blue-900">
                    {{ certificate.issued_at ? formatDate(certificate.issued_at) : '-' }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-blue-500">Certificate ID</p>
                  <p class="text-lg font-medium text-blue-900">
                    {{ certificate.certificate_number || '-' }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-blue-500">Score Achieved</p>
                  <p class="text-lg font-medium text-blue-900">
                    {{ certificate.score || 0 }}%
                  </p>
                </div>
              </div>
              
              <!-- Verification QR Code -->
              <div class="inline-block p-4 bg-blue-50 rounded-lg">
                <QRCode :value="verificationUrl" :size="120" level="H" :options="{ color: { dark: '#2563EB', light: '#ffffff' } }" />
                <p class="mt-2 text-sm text-blue-500">Scan to verify certificate</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="bg-white shadow rounded-lg p-6 border border-blue-100">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Exam Details</h3>
            <dl class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-blue-500">Duration</dt>
                <dd class="mt-1 text-base text-blue-900">
                  {{ certificate.exam?.duration || 0 }} minutes
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-blue-500">Questions</dt>
                <dd class="mt-1 text-base text-blue-900">
                  {{ certificate.exam?.total_questions || 0 }}
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-blue-500">Topics Covered</dt>
                <dd class="mt-1">
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="topic in (certificate.exam?.topics || [])"
                      :key="topic"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                    >
                      {{ topic }}
                    </span>
                  </div>
                </dd>
              </div>
            </dl>
          </div>

          <div class="bg-white shadow rounded-lg p-6 border border-blue-100">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Verification Info</h3>
            <dl class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-blue-500">Status</dt>
                <dd class="mt-1">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="certificate.is_valid ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ certificate.is_valid ? 'Valid' : 'Expired' }}
                  </span>
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-blue-500">Verification URL</dt>
                <dd class="mt-1">
                  <a 
                    :href="verificationUrl"
                    target="_blank"
                    class="text-sm text-blue-900 hover:text-blue-600 break-all"
                  >
                    {{ verificationUrl }}
                  </a>
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import QRCode from 'qrcode.vue' // Changed import
import { 
  DocumentArrowDownIcon,
  ShareIcon,
  ChevronRightIcon
} from '@heroicons/vue/24/outline'

const route = useRoute()
const router = useRouter()
const certificate = ref(null)
const loading = ref(true)

const verificationUrl = computed(() => {
  if (!certificate.value) return ''
  return `${window.location.origin}/verify/${certificate.value.certificate_number}`
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function getScoreClass() {
  const score = certificate.value?.score || 0
  if (score >= 90) return 'text-green-600'
  if (score >= 75) return 'text-blue-600'
  return 'text-yellow-600'
}

async function downloadCertificate() {
  try {
    const response = await axios.get(`/certificates/${certificate.value.id}/download`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `certificate-${certificate.value.certificate_number}.pdf`)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}

function shareCertificate() {
  if (navigator.share) {
    navigator.share({
      title: `${certificate.value.exam_title} Certificate`,
      text: `Check out my certificate for completing ${certificate.value.exam_title}!`,
      url: window.location.href
    })
  } else {
    // Fallback - copy to clipboard
    navigator.clipboard.writeText(window.location.href)
    alert('Certificate URL copied to clipboard!')
  }
}

onMounted(async () => {
  try {
    const { data } = await axios.get(`/certificates/${route.params.id}`);
    certificate.value = data.data;
  } catch (error) {
    console.error('Error loading certificate:', error)
    router.push({ name: 'certificates' })
  } finally {
    loading.value = false
  }
})
</script>