<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Settings</h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Update your personal information
          </p>
        </div>

        <form @submit.prevent="updateProfile" class="border-t border-gray-200">
          <div class="px-4 py-5 sm:p-6 space-y-6">
            <!-- Avatar -->
            <div class="flex items-center space-x-6">
              <div class="flex-shrink-0">
                <img
                  v-if="preview || form.avatar"
                  :src="preview || form.avatar"
                  class="h-24 w-24 rounded-full object-cover"
                >
                <span
                  v-else
                  class="h-24 w-24 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl"
                >
                  {{ form.name?.charAt(0).toUpperCase() }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Photo</label>
                <input
                  type="file"
                  @change="handleFileUpload"
                  accept="image/*"
                  class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100"
                >
              </div>
            </div>

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
              <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
            </div>
          </div>

          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '@/composables/useAuth'
import axios from 'axios'

const { user, updateUser } = useAuth()
const loading = ref(false)
const preview = ref(null)

const form = ref({
  name: '',
  email: '',
  password: '',
  avatar: null
})

onMounted(() => {
  if (user.value) {
    form.value.name = user.value.name
    form.value.email = user.value.email
    form.value.avatar = user.value.avatar
  }
})

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    form.value.avatar = file
    preview.value = URL.createObjectURL(file)
  }
}

async function updateProfile() {
  loading.value = true
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('email', form.value.email)
    if (form.value.password) {
      formData.append('password', form.value.password)
    }
    if (form.value.avatar instanceof File) {
      formData.append('avatar', form.value.avatar)
    }

    const { data } = await axios.post('/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    updateUser(data.user)
    alert('Profile updated successfully')
  } catch (error) {
    console.error('Error updating profile:', error)
    alert('Error updating profile')
  } finally {
    loading.value = false
  }
}
</script>