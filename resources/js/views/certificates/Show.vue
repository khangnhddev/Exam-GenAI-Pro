<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="inline-flex items-center px-6 py-3 rounded-xl text-lg font-semibold text-indigo-600 bg-white shadow-lg">
          <svg class="animate-spin -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Loading certificate...
        </div>
      </div>

      <template v-else-if="certificate">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-4">
            <li>
              <router-link :to="{ name: 'certificates' }" 
                class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                Certificates
              </router-link>
            </li>
            <li class="flex items-center">
              <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" />
              <span class="ml-4 text-sm font-medium text-gray-500">{{ certificate.exam_title }}</span>
            </li>
          </ol>
        </nav>

        <!-- Certificate Preview -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-indigo-100">
          <!-- Action Header -->
          <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="flex items-center justify-between">
              <h2 class="text-xl font-bold text-white flex items-center">
                <AcademicCapIcon class="h-6 w-6 mr-2" />
                Certificate of Achievement
              </h2>
              <div class="flex space-x-3">
                <button
                  @click="downloadCertificate"
                  class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-700 bg-white hover:bg-indigo-50 transition-colors duration-200"
                >
                  <DocumentArrowDownIcon class="mr-2 h-5 w-5" />
                  Download
                </button>
                <button
                  @click="shareCertificate"
                  class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-white/20 hover:bg-white/30 transition-colors duration-200"
                >
                  <ShareIcon class="mr-2 h-5 w-5" />
                  Share
                </button>
              </div>
            </div>
          </div>

          <!-- Certificate Content -->
          <div class="p-8">
            <div class="border-4 border-indigo-200 rounded-xl p-8 bg-gradient-to-br from-white to-indigo-50">
              <div class="max-w-3xl mx-auto text-center">
                <!-- Logo & Title -->
                <div class="mb-8">
                  <div class="flex items-center justify-center gap-2 mb-6">
                    <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-3 rounded-xl">
                      <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" 
                        />
                      </svg>
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="text-2xl font-medium text-indigo-600">AI</span>
                      <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Pro</span>
                    </div>
                  </div>
                  <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-2">
                    Certificate of Achievement
                  </h1>
                  <p class="text-lg text-gray-600">in AI Proficiency</p>
                </div>

                <!-- Recipient Name -->
                <p class="text-2xl font-semibold text-gray-900 mb-4">
                  {{ certificate.user?.name || 'Loading...' }}
                </p>
                
                <!-- Course Info -->
                <p class="text-lg text-gray-600 mb-6">has successfully completed</p>
                <p class="text-3xl font-bold text-gray-900 mb-8">
                  {{ certificate.exam?.title || certificate.exam_title }}
                </p>

                <!-- Certificate Details -->
                <div class="grid grid-cols-3 gap-8 mb-8">
                  <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-1">Issue Date</p>
                    <p class="text-lg font-medium text-gray-900">
                      {{ certificate.issued_at ? formatDate(certificate.issued_at) : '-' }}
                    </p>
                  </div>
                  <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-1">Certificate ID</p>
                    <p class="text-lg font-medium text-gray-900">
                      {{ certificate.certificate_number || '-' }}
                    </p>
                  </div>
                  <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-1">Score Achieved</p>
                    <p class="text-lg font-medium" :class="getScoreClass()">
                      {{ certificate.score || 0 }}%
                    </p>
                  </div>
                </div>

                <!-- QR Code -->
                <div class="inline-block p-6 bg-white rounded-xl shadow-sm">
                  <QRCode 
                    :value="verificationUrl" 
                    :size="120" 
                    level="H" 
                    :options="{ color: { dark: '#4F46E5', light: '#ffffff' } }" 
                  />
                  <p class="mt-2 text-sm text-gray-500">Scan to verify</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- Exam Details Card -->
          <div class="bg-white rounded-xl shadow-lg p-6 border border-indigo-100">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-indigo-100 rounded-lg">
                <ClipboardDocumentListIcon class="h-6 w-6 text-indigo-600" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900">Exam Details</h3>
            </div>
            
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

          <!-- Verification Card -->
          <div class="bg-white rounded-xl shadow-lg p-6 border border-indigo-100">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-indigo-100 rounded-lg">
                <ShieldCheckIcon class="h-6 w-6 text-indigo-600" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900">Verification</h3>
            </div>
            
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
  ChevronRightIcon,
  AcademicCapIcon,
  ClipboardDocumentListIcon,
  ShieldCheckIcon
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

/**
 * Download the certificate as a PDF
 */
const downloadCertificate = async () => {
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
    link.remove()
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Error downloading certificate:', error)
    toast.error('Failed to download certificate')
  }
}

const shareCertificate = () => {
  const verifyUrl = `${window.location.origin}/verify/${certificate.value.certificate_number}`
  
  if (navigator.share) {
    navigator.share({
      title: 'My Achievement Certificate',
      text: `Check out my certificate for ${certificate.value.exam_title}`,
      url: verifyUrl
    })
  } else {
    // Fallback - copy to clipboard
    navigator.clipboard.writeText(verifyUrl)
    toast.success('Verification link copied to clipboard')
  }
}

onMounted(async () => {
  try {
    console.log('Loading certificate:', route.params.id)
    const { data } = await axios.get(`/certificates/${route.params.id}`);
    console.log('Certificate:', data.data)
    certificate.value = data.data;
  } catch (error) {
    // console.error('Error loading certificate:', error)
    // router.push({ name: 'certificates' })
  } finally {
    loading.value = false
  }
})
</script>