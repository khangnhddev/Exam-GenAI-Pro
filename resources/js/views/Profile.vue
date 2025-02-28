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
                  alt="User avatar"
                >
                <!-- Default SVG Avatar -->
                <svg
                  v-else
                  class="h-24 w-24 rounded-full bg-indigo-100 p-2"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"
                    fill="#4F46E5"
                  />
                </svg>
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

            <!-- Fullname -->
            <div>
              <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
              <input
                id="fullname"
                v-model="form.fullname"
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

            <!-- Department -->
            <div>
              <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
              <input
                id="department"
                v-model="form.department"
                type="text"
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
  fullname: '',        // Add fullname
  email: '',
  department: '',      // Add department
  password: '',
  avatar: null
})

onMounted(() => {
  if (user.value) {
    form.value.name = user.value.name
    form.value.fullname = user.value.fullname
    form.value.email = user.value.email
    form.value.department = user.value.department
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
    formData.append('fullname', form.value.fullname)
    formData.append('email', form.value.email)
    formData.append('department', form.value.department)
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