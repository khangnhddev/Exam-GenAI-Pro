<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <!-- <button 
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
      </button> -->

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
            <div class="p-6" ref="certificateRef">
              <div class="border rounded-xl p-10 bg-white">
                <div class="text-center">
                  <!-- Logo -->
                  <div class="mb-10">
                    <div class="inline-flex items-center">
                      <div class="w-14 h-14 bg-[#6C2BD9]/90 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                      </div>
                      <div class="ml-3 flex items-center">
                        <span class="text-2xl font-bold text-[#6C2BD9]/90">AI Pro</span>
                      </div>
                    </div>
                  </div>

                  <!-- Recipient Info -->
                  <p class="text-2xl font-bold text-gray-900 mb-2">
                    {{ certificate.user?.fullname }}
                  </p>
                  <p class="text-[#6C2BD9]/80 mb-4">has successfully completed</p>
                  <h3 class="text-2xl font-bold text-gray-900 mb-10">
                    {{ certificate.exam?.title }}
                  </h3>

                  <!-- Certificate Details Grid -->
                  <div class="grid grid-cols-3 gap-6 mb-10">
                    <div>
                      <p class="text-[#6C2BD9]/80 mb-1">Issue Date</p>
                      <p class="text-base font-semibold text-gray-900">
                        {{ formatDate(certificate.issued_at) }}
                      </p>
                    </div>
                    <div>
                      <p class="text-[#6C2BD9]/80 mb-1">Certificate ID</p>
                      <p class="text-base font-semibold text-gray-900">
                        {{ certificate.certificate_number }}
                      </p>
                    </div>
                    <div>
                      <p class="text-[#6C2BD9]/80 mb-1">Score</p>
                      <p class="text-base font-semibold text-[#6C2BD9]/90">
                        {{ certificate.score }}%
                      </p>
                    </div>
                  </div>

                  <!-- QR Code -->
                  <div class="inline-flex flex-col items-center">
                    <QRCode 
                      :value="verificationUrl" 
                      :size="128"
                      :margin="0"
                      :level="'H'"
                      render-as="svg"
                      :foreground="'#6C2BD9'"
                      class="qr-code opacity-90"
                    />
                    <p class="mt-3 text-[#6C2BD9]/80">Scan to verify</p>
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
                    <dd class="text-sm font-medium text-indigo-600">{{ certificate.score }}%</dd>
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
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-[#6C2BD9]/90 hover:bg-[#6C2BD9] transition-colors"
                  >
                    <DocumentArrowDownIcon class="h-4 w-4 mr-2" />
                    Download PDF
                  </button>
                  <button
                    @click="shareCertificate"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-[#6C2BD9]/90 bg-[#6C2BD9]/10 hover:bg-[#6C2BD9]/20 transition-colors"
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
  return 'text-indigo-600' // Always return indigo color for consistency
}

/**
 * Download the certificate as a PDF
 */
const downloadCertificate = async () => {
  try {
    loading.value = true;
    
    const certificateElement = certificateRef.value.querySelector('.border');
    
    if (!certificateElement) {
      throw new Error('Certificate element not found');
    }

    const html2canvas = (await import('html2canvas')).default;

    // Calculate dimensions for a more compact portrait layout
    const width = 800;
    const height = 1000; // Shorter height

    // Create a temporary container
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.style.width = `${width}px`;
    tempContainer.style.height = `${height}px`;
    tempContainer.style.backgroundColor = '#ffffff';
    document.body.appendChild(tempContainer);

    // Create main container with border
    const mainContainer = document.createElement('div');
    mainContainer.style.width = '100%';
    mainContainer.style.height = '100%';
    mainContainer.style.padding = '32px';
    mainContainer.style.boxSizing = 'border-box';
    tempContainer.appendChild(mainContainer);

    // Create border container
    const borderContainer = document.createElement('div');
    borderContainer.style.width = '100%';
    borderContainer.style.height = '100%';
    borderContainer.style.border = '1px solid rgba(108, 43, 217, 0.9)';
    borderContainer.style.borderRadius = '12px';
    borderContainer.style.padding = '40px';
    borderContainer.style.boxSizing = 'border-box';
    borderContainer.style.backgroundColor = '#ffffff';
    mainContainer.appendChild(borderContainer);

    // Clone the certificate element
    const clone = certificateElement.cloneNode(true);
    clone.style.border = 'none';
    clone.style.width = '100%';
    clone.style.height = '100%';
    clone.style.padding = '0';

    // Fix logo alignment in the cloned element
    const logoContainer = clone.querySelector('.inline-flex.items-center');
    if (logoContainer) {
      logoContainer.style.display = 'flex';
      logoContainer.style.alignItems = 'center';
      logoContainer.style.justifyContent = 'center';
      logoContainer.style.width = '100%';
      logoContainer.style.gap = '12px';
    }

    const logoWrapper = clone.querySelector('.w-14.h-14');
    if (logoWrapper) {
      logoWrapper.style.flexShrink = '0';
      logoWrapper.style.display = 'flex';
      logoWrapper.style.alignItems = 'center';
      logoWrapper.style.justifyContent = 'center';
    }

    const textWrapper = logoContainer.querySelector('.ml-3');
    if (textWrapper) {
      textWrapper.style.marginLeft = '0';  // Remove ml-3 since we're using gap
      textWrapper.style.display = 'flex';
      textWrapper.style.alignItems = 'center';
      textWrapper.style.height = '56px'; // Match logo height
    }

    const logoText = clone.querySelector('.text-2xl.font-bold');
    if (logoText) {
      logoText.style.lineHeight = '1';
      logoText.style.paddingTop = '4px'; // Small adjustment to visually center
    }

    borderContainer.appendChild(clone);

    // Wait for fonts and images
    await document.fonts.ready;
    
    // Create canvas
    const canvas = await html2canvas(tempContainer, {
      scale: 2,
      useCORS: true,
      allowTaint: true,
      backgroundColor: '#ffffff',
      imageTimeout: 15000,
      logging: false,
      onclone: (clonedDoc) => {
        const clonedElement = clonedDoc.querySelector('.border');
        if (clonedElement) {
          clonedElement.style.transform = 'none';
        }
      }
    });

    // Clean up
    document.body.removeChild(tempContainer);

    // Convert to PNG and download
    const dataUrl = canvas.toDataURL('image/png', 1.0);
    const link = document.createElement('a');
    link.download = `certificate-${certificate.value.certificate_number || 'download'}.png`;
    link.href = dataUrl;
    link.click();

  } catch (error) {
    console.error('Error generating certificate:', error);
    alert('Failed to generate certificate. Please try again.');
  } finally {
    loading.value = false;
  }
};

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

// Add ref for certificate element
const certificateRef = ref(null)

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

<style scoped>
.qr-code {
  display: block;
  max-width: 100%;
  height: auto;
}
</style>