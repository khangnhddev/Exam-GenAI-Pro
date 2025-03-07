<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <button 
        @click="$router.back()" 
        class="mb-6 inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-100"
      >
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-5 w-5 mr-2" 
          viewBox="0 0 20 20" 
          fill="currentColor"
        >
          <path 
            fill-rule="evenodd" 
            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" 
            clip-rule="evenodd"
          />
        </svg>
        Back
      </button>

      <!-- Certificate Header -->
      <div class="mb-8">
        <div class="flex items-center space-x-2">
          <AcademicCapIcon class="h-6 w-6" />
          <div class="flex items-baseline gap-2">
            <h1 class="text-base font-medium">Certificate</h1>
            <span>/</span>
            <p class="text-lg font-semibold">
              {{ certificate?.exam?.title || 'Loading...' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center py-12">
        <div class="inline-flex items-center px-6 py-3 rounded-xl text-lg font-semibold text-indigo-600 bg-white shadow-lg mb-3">
          <svg class="animate-spin -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Loading certificate...
        </div>
        <p class="text-gray-600">
          for {{ certificate?.user?.fullname || 'loading...' }}
        </p>
      </div>

      <template v-else-if="certificate">
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column - Certificate -->
          <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <!-- Certificate Content -->
            <div class="p-6">
              <div class="border-2 border-indigo-100 rounded-lg p-6 bg-gradient-to-br from-white to-indigo-50/50">
                <div class="text-center">
                  <!-- Logo -->
                  <div class="mb-6">
                    <div class="inline-flex items-center gap-2">
                      <div class="p-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                      </div>
                      <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        AI Pro Certificate
                      </span>
                    </div>
                  </div>

                  <!-- Recipient Info -->
                  <p class="text-xl font-semibold text-gray-900 mb-2">
                    {{ certificate.user?.fullname }}
                  </p>
                  <p class="text-sm text-gray-600 mb-4">has successfully completed</p>
                  <h3 class="text-2xl font-bold text-gray-900 mb-6">
                    {{ certificate.exam?.title }}
                  </h3>

                  <!-- Certificate Details Grid -->
                  <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                      <p class="text-xs text-gray-500 mb-1">Issue Date</p>
                      <p class="text-sm font-medium text-gray-900">
                        {{ formatDate(certificate.issued_at) }}
                      </p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                      <p class="text-xs text-gray-500 mb-1">Certificate ID</p>
                      <p class="text-sm font-medium text-gray-900">
                        {{ certificate.certificate_number }}
                      </p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                      <p class="text-xs text-gray-500 mb-1">Score</p>
                      <p class="text-sm font-medium" :class="getScoreClass()">
                        {{ certificate.score }}%
                      </p>
                    </div>
                  </div>

                  <!-- QR Code -->
                  <div class="inline-flex flex-col items-center">
                    <QRCode 
                      :value="verificationUrl" 
                      :size="100" 
                      level="H" 
                      :options="{ color: { dark: '#4F46E5', light: '#ffffff' } }" 
                      class="p-2 bg-white rounded-lg shadow-sm"
                    />
                    <p class="mt-2 text-xs text-gray-500">Scan to verify</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Details -->
          <div class="space-y-6">
            <!-- Header with Actions -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <!-- <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h2 class="text-lg font-semibold text-white flex items-center">
                  <AcademicCapIcon class="h-5 w-5 mr-2" />
                  Certificate Details
                </h2>
              </div> -->
              <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-medium text-gray-900 flex items-center">
                  <ClipboardDocumentListIcon class="h-5 w-5 mr-2 text-indigo-600" />
                  Certificate Information
                </h3>
              </div>
              <div class="p-6">
                <!-- Certificate Info -->
                <dl class="space-y-4">
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Certificate ID</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ certificate.certificate_number }}</dd>
                  </div>
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Issue Date</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ formatDate(certificate.issued_at) }}</dd>
                  </div>
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Status</dt>
                    <dd>
                      <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="certificate.is_valid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                        {{ certificate.is_valid ? 'Valid' : 'Expired' }}
                      </span>
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Exam Details Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-medium text-gray-900 flex items-center">
                  <ClipboardDocumentListIcon class="h-5 w-5 mr-2 text-indigo-600" />
                  Exam Information
                </h3>
              </div>
              <div class="p-6">
                <dl class="space-y-4">
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Title</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ certificate.exam?.title }}</dd>
                  </div>
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Duration</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ certificate.exam?.duration }} minutes</dd>
                  </div>
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Questions</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ certificate.exam?.total_questions }}</dd>
                  </div>
                  <div class="flex justify-between items-center">
                    <dt class="text-sm text-gray-500">Score Achieved</dt>
                    <dd class="text-sm font-medium" :class="getScoreClass()">{{ certificate.score }}%</dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                  <button
                    @click="downloadCertificate"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors"
                  >
                    <DocumentArrowDownIcon class="h-4 w-4 mr-2" />
                    Download PDF
                  </button>
                  <button
                    @click="shareCertificate"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors"
                  >
                    <ShareIcon class="h-4 w-4 mr-2" />
                    Share
                  </button>
                </div>
              </div>
            </div>
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
  ShieldCheckIcon,
  ChevronLeftIcon
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
    const { data } = await axios.get(`/certificates/${route.params.id}`);
    console.log(data.data.user.fu);
    certificate.value = data.data;
  } catch (error) {
    // console.error('Error loading certificate:', error)
    // router.push({ name: 'certificates' })
  } finally {
    loading.value = false
  }
})
</script>