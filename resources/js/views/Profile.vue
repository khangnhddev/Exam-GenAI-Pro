<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back button above the card -->
      <button 
        @click="$router.back()" 
        class="mb-6 inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors rounded-lg hover:bg-gray-100"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back
      </button>

      <!-- Profile Card -->
      <div class="bg-white shadow-lg rounded-3xl overflow-hidden">
        <!-- Cover Image & Profile Info Header -->
        <div class="relative">
          <!-- Cover Background -->
          <div class="h-48 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-white/10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            <!-- Animated Gradient Overlay -->
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_50%_150%,rgba(255,255,255,0.8),transparent_50%)]"></div>
            <!-- Inverted Rounded Corners -->
            <div class="absolute -bottom-24 left-0 right-0 h-24 bg-white" style="border-radius: 50% 50% 0 0;"></div>
          </div>

          <!-- Profile Info Overlay -->
          <div class="absolute bottom-0 left-0 right-0 px-8 pb-6 pt-24">
            <div class="flex items-end space-x-6 relative z-10">
              <!-- Avatar -->
              <div class="relative">
                <template v-if="user.avatar_url">
                  <img 
                    :src="user.avatar_url"
                    class="w-28 h-28 rounded-full border-4 border-white shadow-xl object-cover bg-white"
                    alt="Profile"
                  >
                </template>
                <template v-else>
                  <div class="w-28 h-28 rounded-full border-4 border-white shadow-xl bg-white flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                </template>
                <button 
                  @click="triggerAvatarUpload"
                  class="absolute -bottom-1 -right-1 bg-indigo-500 rounded-full p-2 text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 shadow-lg transition-all duration-200"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </button>
                <input 
                  type="file" 
                  ref="avatarInput" 
                  class="hidden" 
                  accept="image/*"
                  @change="handleAvatarChange"
                >
              </div>

              <!-- User Info -->
              <div class="flex-1 text-white">
                <h2 class="text-2xl font-bold mb-1 drop-shadow-sm">{{ form.fullname }}</h2>
                <p class="text-white mb-3 opacity-90 drop-shadow-sm">{{ form.email }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div class="px-8 py-6">
          <form @submit.prevent="updateProfile" class="space-y-8">
            <!-- Basic Info Section -->
            <div class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Basic Information</h3>
              
              <div class="grid grid-cols-1 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Full Name</label>
                  <input 
                    type="text" 
                    v-model="form.fullname"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-500">Email</label>
                  <input 
                    type="email" 
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm"
                    disabled
                    readonly
                  >
                  <p class="mt-1 text-xs text-gray-500">Contact support to change your email address</p>
                </div>
              </div>
            </div>

            <!-- Change Password Section -->
            <div class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Change Password</h3>
              
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Current Password</label>
                  <input 
                    type="password" 
                    v-model="form.current_password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">New Password</label>
                  <input 
                    type="password" 
                    v-model="form.new_password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                  <input 
                    type="password" 
                    v-model="form.new_password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                </div>
              </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end pt-6">
              <button
                type="submit"
                :disabled="loading"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              >
                <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ loading ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import axios from 'axios'

const toast = useToast()
const loading = ref(false)
const user = ref({})

// Update form ref
const form = ref({
  fullname: '',
  email: '',
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

// Update onMounted
onMounted(async () => {
  try {
    const userResponse = await axios.get('/profile');
    user.value = userResponse.data.data;
    
    form.value = {
      fullname: user.value.fullname,
      email: user.value.email,
      current_password: '',
      new_password: '',
      new_password_confirmation: ''
    }
  } catch (error) {
    toast.error('Error loading profile data')
  }
})

const updateProfile = async () => {
  loading.value = true
  try {
    const response = await axios.put('/profile', form.value)
    user.value = response.data.data
    toast.success('Profile updated successfully')
    
    // Clear password fields
    form.value.current_password = ''
    form.value.new_password = ''
    form.value.new_password_confirmation = ''
  } catch (error) {
    toast.error(error.response?.data?.message || 'Error updating profile')
  } finally {
    loading.value = false
  }
}

const triggerAvatarUpload = () => {
  avatarInput.value.click()
}

const handleAvatarChange = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('avatar', file)

  try {
    loading.value = true
    const response = await axios.post('/api/profile/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    user.value.avatar_url = response.data.data.avatar_url
    toast.success('Avatar updated successfully')
  } catch (error) {
    toast.error('Error uploading avatar')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.disabled-field {
  @apply bg-gray-50 text-gray-500 cursor-not-allowed border-gray-200;
}

input:disabled {
  -webkit-text-fill-color: #6B7280;
  opacity: 1;
}
</style>