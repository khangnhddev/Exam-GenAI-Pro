<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            General Settings
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage system-wide settings and configurations
          </p>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
          </path>
        </svg>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="rounded-md bg-red-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error loading settings</h3>
            <div class="mt-2 text-sm text-red-700">{{ error }}</div>
          </div>
        </div>
      </div>

      <!-- Settings Form -->
      <div v-else class="bg-white shadow rounded-lg">
        <form @submit.prevent="saveSettings" class="space-y-8 divide-y divide-gray-200 p-8">
          <!-- Site Information -->
          <div class="space-y-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Site Information</h3>
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <div class="sm:col-span-4">
                <label class="block text-sm font-medium text-gray-700">Site Name</label>
                <input type="text" v-model="settings.site_name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>

              <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Site Description</label>
                <textarea v-model="settings.site_description" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
              </div>

              <div class="sm:col-span-4">
                <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input type="email" v-model="settings.contact_email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>
            </div>
          </div>

          <!-- System Settings -->
          <div class="pt-8 space-y-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Settings</h3>
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Default Language</label>
                <select v-model="settings.default_language"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                  <option value="en">English</option>
                  <option value="vi">Vietnamese</option>
                </select>
              </div>

              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Timezone</label>
                <select v-model="settings.timezone"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                  <option value="UTC">UTC</option>
                  <option value="Asia/Ho_Chi_Minh">Asia/Ho Chi Minh</option>
                </select>
              </div>

              <div class="sm:col-span-6">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input type="checkbox" v-model="settings.maintenance_mode"
                      class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                  </div>
                  <div class="ml-3 text-sm">
                    <label class="font-medium text-gray-700">Maintenance Mode</label>
                    <p class="text-gray-500">Put the site into maintenance mode</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Email Settings -->
          <div class="pt-8 space-y-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Email Settings</h3>
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                <input type="text" v-model="settings.smtp_host"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>

              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                <input type="number" v-model="settings.smtp_port"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>

              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                <input type="text" v-model="settings.smtp_username"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>

              <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                <input type="password" v-model="settings.smtp_password"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              </div>
            </div>
          </div>

          <!-- Save Button -->
          <div class="pt-5">
            <div class="flex justify-end">
              <button type="submit" :disabled="saving"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                {{ saving ? 'Saving...' : 'Save Settings' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { XCircleIcon } from '@heroicons/vue/24/solid'
import { useToast } from 'vue-toastification'

const toast = useToast()
const loading = ref(true)
const saving = ref(false)
const error = ref(null)

const settings = ref({
  site_name: '',
  site_description: '',
  contact_email: '',
  default_language: 'en',
  timezone: 'UTC',
  maintenance_mode: false,
  smtp_host: '',
  smtp_port: '',
  smtp_username: '',
  smtp_password: ''
})

onMounted(async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/admin/settings/general')
    settings.value = response.data.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error loading settings'
    console.error('Error fetching settings:', err)
  } finally {
    loading.value = false
  }
})

async function saveSettings() {
  try {
    saving.value = true
    await axios.post('/api/admin/settings/general', settings.value)
    toast.success('Settings saved successfully!')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Error saving settings')
    console.error('Error saving settings:', err)
  } finally {
    saving.value = false
  }
}
</script>