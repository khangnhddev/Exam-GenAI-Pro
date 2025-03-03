<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Departments
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage your organization's departments
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <button @click="openCreateModal" 
            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <PlusIcon class="h-5 w-5 mr-2" />
            Add Department
          </button>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <input type="text" 
                v-model="searchQuery"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Search departments...">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Departments Table -->
      <div class="bg-white shadow-sm rounded-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Department
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Code
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Description
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Created
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Actions</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="department in filteredDepartments" :key="department.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ department.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                    {{ department.code }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 line-clamp-2">{{ department.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(department.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button @click="editDepartment(department)" 
                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                    Edit
                  </button>
                  <button @click="confirmDelete(department)" 
                    class="text-red-600 hover:text-red-900">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button @click="prevPage" :disabled="!canGoPrev"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !canGoPrev }">
                Previous
              </button>
              <button @click="nextPage" :disabled="!canGoNext"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !canGoNext }">
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
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
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  {{ editingDepartment ? 'Edit Department' : 'Create Department' }}
                </DialogTitle>

                <form @submit.prevent="saveDepartment" class="mt-4 space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" v-model="form.name" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required>
                  </div>

                  <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" id="code" v-model="form.code" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required>
                  </div>

                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" v-model="form.description" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required></textarea>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="closeModal"
                      class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                      Cancel
                    </button>
                    <button type="submit"
                      class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                      {{ editingDepartment ? 'Update' : 'Create' }}
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot appear :show="isDeleteModalOpen" as="template">
      <Dialog as="div" @close="closeDeleteModal" class="relative z-10">
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
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Delete Department
                </DialogTitle>

                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this department? This action cannot be undone.
                  </p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                  <button type="button" @click="closeDeleteModal"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancel
                  </button>
                  <button type="button" @click="deleteDepartment"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Delete
                  </button>
                </div>
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
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { PlusIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'
import { format } from 'date-fns'

// State
const departments = ref([])
const searchQuery = ref('')
const isModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const editingDepartment = ref(null)
const departmentToDelete = ref(null)
const form = ref({
  name: '',
  code: '',
  description: ''
})

// Computed
const filteredDepartments = computed(() => {
  return departments.value.filter(department => {
    const searchLower = searchQuery.value.toLowerCase()
    return department.name.toLowerCase().includes(searchLower) ||
           department.code.toLowerCase().includes(searchLower) ||
           department.description.toLowerCase().includes(searchLower)
  })
})

/**
 * Fetch departments
 */
const fetchDepartments = async () => {
  try {
    const response = await axios.get('/admin/departments');
    departments.value = response.data.data;
  } catch (error) {
    console.error('Error fetching departments:', error)
  }
}

/**
 * Open create modal
 * @returns {void}
 */
const openCreateModal = () => {
  editingDepartment.value = null
  form.value = {
    name: '',
    code: '',
    description: ''
  }
  isModalOpen.value = true
}

const editDepartment = (department) => {
  editingDepartment.value = department
  form.value = {
    name: department.name,
    code: department.code,
    description: department.description
  }
  isModalOpen.value = true
}

const saveDepartment = async () => {
  try {
    if (editingDepartment.value) {
      await axios.put(`/api/admin/departments/${editingDepartment.value.id}`, form.value)
    } else {
      await axios.post('/api/admin/departments', form.value)
    }
    await fetchDepartments()
    closeModal()
  } catch (error) {
    console.error('Error saving department:', error)
  }
}

const confirmDelete = (department) => {
  departmentToDelete.value = department
  isDeleteModalOpen.value = true
}

const deleteDepartment = async () => {
  try {
    await axios.delete(`/admin/departments/${departmentToDelete.value.id}`)
    await fetchDepartments()
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting department:', error)
  }
}

const closeModal = () => {
  isModalOpen.value = false
  editingDepartment.value = null
  form.value = {
    name: '',
    code: '',
    description: ''
  }
}

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false
  departmentToDelete.value = null
}

const formatDate = (date) => {
  return format(new Date(date), 'MMM d, yyyy')
}

// Lifecycle
onMounted(() => {
  fetchDepartments()
})
</script> 