<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="mb-8">
        <button 
          @click="router.back()" 
          class="group mb-6 inline-flex items-center gap-2 text-slate-600 hover:text-slate-900"
        >
          <ChevronLeftIcon class="w-5 h-5 transition-transform group-hover:-translate-x-1" />
          <span>Back</span>
        </button>
        
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="p-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-md shadow-indigo-500/20">
              <AcademicCapIcon class="w-6 h-6 text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900">
                Certificate of Achievement
              </h1>
              <p class="text-sm text-slate-500 mt-1">
                {{ certificate?.exam?.title }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <button
              @click="shareCertificate"
              class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
            >
              <ShareIcon class="w-4 h-4 mr-2" />
              Share
            </button>
            <button
              @click="downloadCertificate"
              class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md shadow-indigo-500/20"
            >
              <DocumentArrowDownIcon class="w-4 h-4 mr-2" />
              Download Certificate
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center min-h-[60vh]">
        <div class="w-16 h-16 border-4 border-[#6C2BD9] border-l-transparent rounded-full animate-spin"></div>
        <p class="mt-4 text-gray-500">Loading certificate...</p>
      </div>

      <template v-else-if="certificate">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Certificate Preview -->
          <div 
            ref="certificateRef"
            class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 relative group hover:shadow-xl transition-shadow duration-300"
          >
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
            
            <div class="border border-slate-200 rounded-xl p-10 bg-white relative">
              <!-- Certificate Content -->
              <div class="text-center">
                <!-- Logo -->
                <div class="mb-12 inline-flex items-center gap-2">
                  <!-- Logo Icon -->
                  <!-- <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 rounded-lg">
                    <svg 
                      class="w-6 h-6 text-white" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                    >
                      <path 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2" 
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                      />
                    </svg>
                  </div> -->
                  <img src="/images/JVITTechs-Logo.png" alt="JVITTechs Logo" class="h-10 w-auto">

                  <!-- Logo Text -->
                  <div class="flex items-center gap-1">
                    <span class="text-lg font-medium text-indigo-600">AI</span>
                    <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                      Pro
                    </span>
                  </div>
                </div>

                <!-- Certificate Text -->
                <div class="space-y-8 mb-12">
                  <div>
                    <h2 class="text-4xl font-bold text-slate-900">
                      {{ certificate.user?.fullname }}
                    </h2>
                    <p class="text-indigo-600 mt-3 font-medium">
                      has successfully completed
                    </p>
                  </div>
                  
                  <h3 class="text-2xl font-bold text-slate-800">
                    {{ certificate.exam?.title }}
                  </h3>
                </div>

                <!-- Certificate Details -->
                <div class="grid grid-cols-3 gap-8 mb-12">
                  <div>
                    <p class="text-slate-500 text-sm mb-1">Issue Date</p>
                    <p class="font-semibold text-slate-800">
                      {{ formatDate(certificate.issued_at) }}
                    </p>
                  </div>
                  <div>
                    <p class="text-slate-500 text-sm mb-1">Certificate ID</p>
                    <p class="font-mono font-medium text-slate-800">
                      {{ certificate.certificate_number }}
                    </p>
                  </div>
                  <div>
                    <p class="text-slate-500 text-sm mb-1">Score</p>
                    <p class="font-semibold text-indigo-600">
                      {{ certificate.score }}%
                    </p>
                  </div>
                </div>

                <!-- QR Code -->
                <div class="inline-flex flex-col items-center">
                  <QRCode 
                    :value="verificationUrl" 
                    :size="120"
                    :margin="0"
                    :level="'H'"
                    render-as="svg"
                    :foreground="'#1f2937'"
                    class="opacity-80 qr-code"
                  />
                  <p class="mt-3 text-sm text-slate-500">
                    Scan to verify
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Certificate Details -->
          <div class="space-y-6">
            <!-- Verification Status -->
            <div class="bg-white rounded-xl shadow-md border border-slate-100 overflow-hidden">
              <div class="p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-green-100 rounded-lg">
                    <ShieldCheckIcon class="w-5 h-5 text-green-600" />
                  </div>
                  <div>
                    <h3 class="font-medium text-gray-900">
                      Certificate Status
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5">
                      This certificate is valid and verified
                    </p>
                  </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                  Valid
                </span>
              </div>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <!-- Certificate Info -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                  <h3 class="font-medium text-gray-900">
                    Certificate Details
                  </h3>
                </div>
                <div class="p-6">
                  <dl class="space-y-4">
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">ID Number</dt>
                      <dd class="text-sm font-medium text-gray-900 font-mono">
                        {{ certificate.certificate_number }}
                      </dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">Issue Date</dt>
                      <dd class="text-sm font-medium text-gray-900">
                        {{ formatDate(certificate.issued_at) }}
                      </dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">Recipient</dt>
                      <dd class="text-sm font-medium text-gray-900">
                        {{ certificate.user?.fullname }}
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>

              <!-- Exam Info -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                  <h3 class="font-medium text-gray-900">
                    Exam Details
                  </h3>
                </div>
                <div class="p-6">
                  <dl class="space-y-4">
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">Score</dt>
                      <dd class="text-sm font-medium text-[#6C2BD9]">
                        {{ certificate.score }}%
                      </dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">Questions</dt>
                      <dd class="text-sm font-medium text-gray-900">
                        {{ certificate.exam?.total_questions }}
                      </dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-500">Duration</dt>
                      <dd class="text-sm font-medium text-gray-900">
                        {{ certificate.exam?.duration }} minutes
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>

            <!-- Verification Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
              <h3 class="font-medium text-gray-900 mb-4">
                Verification Link
              </h3>
              <div class="flex items-center gap-3">
                <input 
                  type="text" 
                  :value="verificationUrl"
                  readonly
                  class="flex-1 text-sm text-gray-500 bg-gray-50 rounded-lg px-4 py-2 border border-gray-200"
                />
                <button
                  @click="copyVerificationLink"
                  class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-indigo-600 hover:bg-indigo-50 transition-colors"
                >
                  <ClipboardDocumentListIcon class="w-4 h-4" />
                </button>
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
 * Download the certificate as a high-quality PNG
 */
const downloadCertificate = async () => {
  try {
    loading.value = true
    const certificateElement = certificateRef.value.querySelector('.border')
    
    if (!certificateElement) {
      throw new Error('Certificate element not found')
    }

    // Tạo container tạm
    const tempContainer = document.createElement('div')
    tempContainer.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: white;
      padding: 40px;
      z-index: -9999;
    `
    document.body.appendChild(tempContainer)
    
    // Clone chứng chỉ
    const clonedCertificate = certificateElement.cloneNode(true)
    
    // Style cho chứng chỉ
    clonedCertificate.style.cssText = `
      width: 800px;
      padding: 60px;
      border-width: 2px;
      border-color: #e2e8f0;
      border-style: solid;
      border-radius: 16px;
      background-color: white;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      transform: scale(1);
      transform-origin: center;
    `
    tempContainer.appendChild(clonedCertificate)
    
    // PHƯƠNG PHÁP MỚI: Thay thế logo hoàn toàn bằng hình ảnh
    const fixStyles = async (element) => {
      try {
        // 1. Tìm logo container
        const logoContainer = element.querySelector('.mb-12.inline-flex');
        if (logoContainer) {
          // 2. Tạo logo JVITTechs cho certificate download
          const logoImg = document.createElement('img');
          
          // Sử dụng base64 để đảm bảo logo luôn hiển thị đúng
          // Sử dụng một trong 2 cách:
          
          // Cách 1: Tải image về, convert to base64, rồi hiển thị
          const loadLogoAsBase64 = async () => {
            try {
              const response = await fetch('/images/JVITTechs-Logo.png');
              const blob = await response.blob();
              
              const reader = new FileReader();
              reader.readAsDataURL(blob);
              
              return new Promise((resolve) => {
                reader.onloadend = () => {
                  logoImg.src = reader.result;
                  logoImg.alt = 'JVITTechs Logo';
                  logoImg.style.cssText = `
                    height: 40px; 
                    margin-bottom: 48px;
                  `;
                  
                  resolve();
                };
              });
            } catch (err) {
              console.error('Error loading logo:', err);
              // Sử dụng path tương đối nếu không convert được
              logoImg.src = '/images/JVITTechs-Logo.png';
            }
          };
          
          await loadLogoAsBase64();
          
          // 3. Xóa nội dung cũ và thêm logo mới
          logoContainer.innerHTML = '';
          logoContainer.appendChild(logoImg);
          logoContainer.style.justifyContent = 'center';
          logoContainer.style.display = 'flex';
        }
        
        // Các style khác giữ nguyên...
      } catch (error) {
        console.error('Error fixing styles:', error);
      }
    };
    
    await fixStyles(clonedCertificate);
    
    // Đảm bảo logo được tải xong
    await new Promise(resolve => setTimeout(resolve, 800));
    
    // Generate the image với scale cao hơn
    const html2canvas = (await import('html2canvas')).default;
    const canvas = await html2canvas(clonedCertificate, {
      scale: 4,
      useCORS: true,
      allowTaint: true,
      backgroundColor: '#ffffff',
      logging: false,
      imageTimeout: 30000
    });
    
    // Remove container
    document.body.removeChild(tempContainer);
    
    // Tạo download link
    const dataUrl = canvas.toDataURL('image/png', 1.0);
    const link = document.createElement('a');
    link.download = `AI-Pro-Certificate-${certificate.value.certificate_number}.png`;
    link.href = dataUrl;
    link.click();
    
  } catch (error) {
    console.error('Failed to generate certificate:', error);
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
    //toast.success('Verification link copied to clipboard')
  }
}

// Add ref for certificate element
const certificateRef = ref(null)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/certificates/${route.params.id}`);
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