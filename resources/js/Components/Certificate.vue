<template>
  <div class="certificate-container bg-white rounded-3xl p-12 shadow-lg" ref="certificateRef">
    <!-- Logo and Title -->
    <div class="flex items-center justify-center mb-12">
      <div class="bg-indigo-600 rounded-xl p-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-gray-900 ml-4">AI Pro Certificate</h1>
    </div>

    <!-- Student Name -->
    <div class="text-center mb-8">
      <h2 class="text-4xl font-bold text-gray-900">{{ certificate?.user?.name || 'Loading...' }}</h2>
      <p class="text-xl text-gray-600 mt-4">has successfully completed</p>
    </div>

    <!-- Course Name -->
    <div class="text-center mb-16">
      <h3 class="text-4xl font-bold text-gray-900">{{ certificate?.exam?.title || 'Loading...' }}</h3>
    </div>

    <!-- Certificate Details -->
    <div class="grid grid-cols-3 gap-8 text-center mb-12">
      <div>
        <p class="text-gray-600 mb-2">Issue Date</p>
        <p class="text-xl font-semibold text-gray-900">{{ certificate?.created_at ? formatDate(certificate.created_at) : 'Loading...' }}</p>
      </div>
      <div>
        <p class="text-gray-600 mb-2">Certificate ID</p>
        <p class="text-xl font-semibold text-gray-900">{{ certificate?.certificate_id || 'Loading...' }}</p>
      </div>
      <div>
        <p class="text-gray-600 mb-2">Score</p>
        <p class="text-xl font-semibold text-green-600">{{ certificate?.score ? `${certificate.score}%` : 'Loading...' }}</p>
      </div>
    </div>

    <!-- QR Code -->
    <div class="flex justify-center">
      <div class="text-center">
        <QRCode 
          v-if="verifyUrl"
          :value="verifyUrl" 
          :options="{ width: 128 }"
          class="mx-auto mb-4"
        />
        <p class="text-gray-600">Scan to verify</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'

// Import QR code component asynchronously with error handling
const QRCode = defineAsyncComponent({
  loader: () => import('@chenfengyuan/vue-qrcode'),
  error: () => ({
    render() {
      return h('div', { class: 'text-gray-500 text-sm' }, 'QR Code unavailable')
    }
  }),
  loading: () => ({
    render() {
      return h('div', { class: 'animate-pulse bg-gray-200 w-32 h-32 mx-auto rounded' })
    }
  })
})

const props = defineProps({
  certificate: {
    type: Object,
    required: true,
    default: () => ({})
  }
})

const certificateRef = ref(null)
const verifyUrl = computed(() => {
  if (!props.certificate?.certificate_id) return ''
  return window.location.origin + '/verify/' + props.certificate.certificate_id
})

const formatDate = (date) => {
  if (!date) return 'Loading...'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const downloadCertificate = async () => {
  if (!certificateRef.value || !props.certificate?.certificate_id) {
    console.error('Certificate not ready for download')
    alert('Certificate is still loading. Please try again in a moment.')
    return
  }

  try {
    const html2canvas = (await import('html2canvas')).default
    
    const canvas = await html2canvas(certificateRef.value, {
      scale: 2,
      backgroundColor: '#ffffff',
      logging: false,
      useCORS: true
    })
    
    const link = document.createElement('a')
    link.download = 'certificate-' + props.certificate.certificate_id + '.png'
    link.href = canvas.toDataURL('image/png')
    link.click()
  } catch (error) {
    console.error('Error generating certificate:', error)
    alert('Failed to generate certificate. Please try again.')
  }
}

defineExpose({
  downloadCertificate
})
</script>

<style scoped>
.certificate-container {
  width: 1000px;
  aspect-ratio: 1.414;
  background-image: 
    radial-gradient(circle at top left, rgba(79, 70, 229, 0.05) 0%, transparent 50%),
    radial-gradient(circle at bottom right, rgba(147, 51, 234, 0.05) 0%, transparent 50%);
}
</style> 