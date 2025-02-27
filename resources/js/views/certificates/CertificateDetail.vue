<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="certificate" class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Certificate Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-2xl font-bold text-gray-900">
            {{ certificate.exam.title }}
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Certificate #{{ certificate.certificate_number }}
          </p>
        </div>

        <!-- Certificate Content -->
        <div class="px-6 py-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-3">
              <div>
                <label class="text-sm font-medium text-gray-500">Issued To</label>
                <p class="mt-1">{{ certificate.user.name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Issue Date</label>
                <p class="mt-1">{{ formatDate(certificate.issued_at) }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Score</label>
                <p class="mt-1">{{ certificate.score }}%</p>
              </div>
            </div>
            <div class="space-y-3">
              <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1">
                  <span 
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      certificate.is_valid 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-red-100 text-red-800'
                    ]"
                  >
                    {{ certificate.is_valid ? 'Valid' : 'Expired' }}
                  </span>
                </p>
              </div>
              <div v-if="certificate.expires_at">
                <label class="text-sm font-medium text-gray-500">Expires On</label>
                <p class="mt-1">{{ formatDate(certificate.expires_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Verification URL -->
          <div class="mt-6 p-4 bg-gray-50 rounded-md">
            <label class="text-sm font-medium text-gray-500">Verification URL</label>
            <div class="mt-1 flex">
              <input
                type="text"
                :value="certificate.verification_url"
                readonly
                class="flex-1 block w-full px-3 py-2 sm:text-sm border-gray-300 rounded-md bg-white"
              />
              <button
                @click="copyVerificationUrl"
                class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Copy
              </button>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
          <button
            @click="router.back()"
            class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Back
          </button>
          <button
            @click="downloadCertificate"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
          >
            Download PDF
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
const certificate = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/v1/certificates/${route.params.id}`)
    certificate.value = response.data.certificate
  } catch (error) {
    console.error('Error loading certificate:', error)
  }
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const copyVerificationUrl = () => {
  navigator.clipboard.writeText(certificate.value.verification_url)
}

const downloadCertificate = async () => {
  try {
    const response = await axios.get(
      `/api/v1/certificates/${certificate.value.id}/download`, 
      { responseType: 'blob' }
    )
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
</script>