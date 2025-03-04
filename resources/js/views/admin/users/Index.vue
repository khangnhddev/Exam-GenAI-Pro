<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Users Management
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage user accounts and permissions
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <button type="button" @click="createUser"
            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add User
          </button>
        </div>
      </div>

      <!-- Search and Filter -->
      <SearchFilterPanel v-model:search="searchQuery" v-model:filter="selectedRole" search-label="Search Users"
        search-placeholder="Search by name, email..." filter-label="Role" :filters="roleFilters" />

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
      <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error loading users</h3>
            <div class="mt-2 text-sm text-red-700">{{ error }}</div>
          </div>
        </div>
      </div>

      <!-- Users Table (only show when not loading and no error) -->
      <div v-else class="bg-white shadow overflow-hidden rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  User Info
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Department
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Role
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Joined Date
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in filteredUsers" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0">
                      <img :src="user.avatar_url || '/default-avatar.png'" class="h-10 w-10 rounded-full object-cover" alt="">
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ user.fullname }}</div>
                      <div class="text-sm text-gray-500">{{ user.email }}</div>
                      <div class="text-xs text-gray-400">@{{ user.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                    {{ getDepartmentName(user.department_id) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-purple-100 text-purple-800': user.role === 'admin',
                      'bg-blue-100 text-blue-800': user.role === 'teacher',
                      'bg-green-100 text-green-800': user.role === 'student'
                    }">
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="user.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ user.active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(user.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 mr-4">
                    Edit
                  </button>
                  <button @click="deleteUser(user)" class="text-red-600 hover:text-red-900">
                    Delete
                  </button>
                </td>
              </tr>
              <!-- Empty state -->
              <tr v-if="filteredUsers.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  No users found matching your search criteria
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create User Modal -->
    <TransitionRoot appear :show="showCreateModal" as="template">
      <Dialog as="div" @close="showCreateModal = false" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                  <button
                    type="button"
                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    @click="showCreateModal = false"
                  >
                    <span class="sr-only">Close</span>
                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                  </button>
                </div>

                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Create New User
                </DialogTitle>

                <form @submit.prevent="handleSubmit" class="mt-4">
                  <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Full Name</label>
                      <input 
                        v-model="form.fullname"
                        type="text" 
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter full name"
                      />
                    </div>

                    <!-- Username -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Username</label>
                      <input 
                        v-model="form.name"
                        type="text" 
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter username"
                      />
                    </div>

                    <!-- Email -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email Address</label>
                      <input 
                        v-model="form.email"
                        type="email" 
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="user@example.com"
                      />
                    </div>

                    <!-- Department -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Department</label>
                      <select 
                        v-model="form.department_id"
                        required
                        :disabled="loadingDepartments"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      >
                        <option value="">{{ loadingDepartments ? 'Loading departments...' : 'Select Department' }}</option>
                        <option v-for="dept in departments" 
                                :key="dept.id" 
                                :value="dept.id"
                                class="py-1">
                          {{ dept.name }}
                        </option>
                      </select>
                      <p v-if="departmentError" class="mt-1 text-sm text-red-600">
                        {{ departmentError }}
                      </p>
                    </div>

                    <!-- Role -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Role</label>
                      <select 
                        v-model="form.role"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      >
                        <option value="">Select Role</option>
                        <option value="admin">Administrator</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                      </select>
                    </div>

                    <!-- Password -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Password</label>
                      <input 
                        v-model="form.password"
                        type="password" 
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter password"
                      />
                    </div>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button
                      type="button"
                      class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                      @click="showCreateModal = false"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      :disabled="loading"
                      class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a 8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                      </svg>
                      {{ loading ? 'Creating...' : 'Create User' }}
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { format } from 'date-fns'
import axios from 'axios' // Add this import
import SearchFilterPanel from '@/Components/admin/SearchFilterPanel.vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

// Initialize users as empty array
const users = ref([])
const searchQuery = ref('')
const selectedRole = ref('')
const loading = ref(true)
const error = ref(null)
const showCreateModal = ref(false)
const departments = ref([])
const loadingDepartments = ref(false)
const departmentError = ref(null)

const form = ref({
  name: '',
  fullname: '',
  email: '',
  department_id: '',
  password: '',
  role: ''
})

const roleFilters = [
  { value: '', label: 'All Roles' },
  { value: 'admin', label: 'Administrator' },
  { value: 'teacher', label: 'Teacher' },
  { value: 'student', label: 'Student' }
]

// Add computed property for department names
const getDepartmentName = (departmentId) => {
  const department = departments.value?.find(d => d.id === departmentId);
  console.log(departmentId);
  return department ? department.name : 'N/A'
}

// Fetch departments
async function fetchDepartments() {
  loadingDepartments.value = true
  departmentError.value = null
  try {
    const response = await axios.get('/admin/departments')
    departments.value = response.data.data
  } catch (err) {
    departmentError.value = err.response?.data?.message || 'Error loading departments'
    console.error('Error fetching departments:', err)
  } finally {
    loadingDepartments.value = false
  }
}

// Add error handling in computed property
const filteredUsers = computed(() => {
  if (!users.value) return []

  return users.value.filter(user => {
    const matchesSearch = !searchQuery.value ||
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchesRole = !selectedRole.value || user.role === selectedRole.value

    return matchesSearch && matchesRole
  })
})

// Fetch departments on component mount
onMounted(async () => {
  try {
    // Fetch departments first
    await fetchDepartments()
    
    // Then fetch users
    loading.value = true
    error.value = null
    const response = await axios.get('/admin/users')
    users.value = response.data.data;
    console.log(users.value);
  } catch (err) {
    error.value = err.response?.data?.message || 'Error loading users'
    console.error('Error fetching users:', err)
  } finally {
    loading.value = false
  }
})

function formatDate(date) {
  return format(new Date(date), 'MMM dd, yyyy')
}

function createUser() {
  showCreateModal.value = true
  fetchDepartments()
}

function editUser(user) {
  // Implement user editing logic
}

async function deleteUser(user) {
  if (!confirm(`Are you sure you want to delete ${user.name}?`)) return

  try {
    await axios.delete(`/admin/users/${user.id}`)
    users.value = users.value.filter(u => u.id !== user.id)
  } catch (error) {
    console.error('Error deleting user:', error)
  }
}

async function handleSubmit() {
  loading.value = true
  try {
    const { data } = await axios.post('/api/admin/users', form.value)
    
    // Refresh users list to get updated data including department
    const response = await axios.get('/admin/users')
    users.value = response.data.data

    // Reset form and close modal
    form.value = {
      name: '',
      fullname: '',
      email: '',
      department_id: '',
      password: '',
      role: ''
    }
    showCreateModal.value = false

    // Show success message
    toast.success('User created successfully!')
  } catch (error) {
    console.error('Error creating user:', error)
    toast.error(error.response?.data?.message || 'Error creating user')
  } finally {
    loading.value = false
  }
}
</script>