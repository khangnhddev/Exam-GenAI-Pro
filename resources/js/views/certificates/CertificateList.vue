<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">My Certificates</h1>

      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="certificate in certificates" 
             :key="certificate.id" 
             class="bg-white rounded-lg shadow-lg overflow-hidden">
          <!-- Certificate Card -->
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">
                {{ certificate.exam_title }}
              </h3>
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
            </div>

            <div class="space-y-2 text-sm text-gray-500">
              <div class="flex justify-between">
                <span>Certificate Number:</span>
                <span class="font-medium">{{ certificate.certificate_number }}</span>
              </div>
              <div class="flex justify-between">
                <span>Issued Date:</span>
                <span>{{ certificate.issued_at }}</span>
              </div>
              <div class="flex justify-between">
                <span>Score:</span>
                <span class="font-medium">{{ certificate.score }}%</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-3">
              <button
                @click="viewCertificate(certificate.id)"
                class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
              >
                View
              </button>
              <button
                @click="downloadCertificate(certificate.id)"
                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50"
              >
                Download
              </button>
            </div>
          </div>
        </div>
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
    const response = await axios.get('/certificates')
    certificates.value = response.data
  } catch (error) {
    console.error('Error loading certificates:', error)
  }
})

const viewCertificate = (id) => {
  router.push({ name: 'certificate-detail', params: { id } })
}

const downloadCertificate = async (id) => {
  try {
    const response = await axios.get(`/api/v1/certificates/${id}/download`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `certificate-${id}.pdf`)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}
</script>