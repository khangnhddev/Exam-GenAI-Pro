<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Add Back Button -->
      <div class="mb-6">
        <button
          @click="$router.back()"
          class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900"
        >
          <svg 
            class="h-5 w-5 mr-1" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M15 19l-7-7 7-7"
            />
          </svg>
          Back
        </button>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <div class="md:flex md:items-center md:justify-between mb-8">
          <h1 class="text-3xl font-bold text-gray-900">
            {{ isEditing ? 'Edit Exam' : 'Create New Exam' }}
          </h1>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Image Upload Section - Add after Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Exam Image</label>
            <div class="mt-1 flex items-center space-x-5">
              <!-- Image Preview -->
              <div v-if="imagePreview || form.image_path" class="relative w-32 h-32">
                <img 
                  :src="imagePreview || form.image_path" 
                  :alt="form.image_alt"
                  class="object-cover rounded-lg w-full h-full"
                />
                <button
                  type="button"
                  @click="removeImage"
                  class="absolute -top-2 -right-2 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none"
                >
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <!-- Upload Button -->
              <div v-if="!imagePreview && !form.image_path" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 48 48"
                  >
                    <path
                      d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                  <div class="flex text-sm text-gray-600">
                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                      <span>Upload an image</span>
                      <input 
                        type="file" 
                        class="sr-only" 
                        accept="image/*"
                        @change="handleImageUpload" 
                      >
                    </label>
                  </div>
                  <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
          </div>

          <!-- Duration -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
            <input
              v-model.number="form.duration"
              type="number"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Passing Score -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Passing Score (%)</label>
            <input
              v-model.number="form.passing_score"
              type="number"
              min="0"
              max="100"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Max Attempts -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Max Attempts</label>
            <input
              v-model.number="form.max_attempts"
              type="number"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>

          <!-- Submit Buttons -->
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$router.back()"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {{ isEditing ? 'Update Exam' : 'Create Exam' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const props = defineProps({
  id: {
    type: [String, Number],
    required: false
  },
  isEditing: {
    type: Boolean,
    default: false
  }
})

const router = useRouter()
const loading = ref(false)
const imageFile = ref(null)
const imagePreview = ref(null)
const form = ref({
  title: '',
  description: '',
  duration: 60,
  passing_score: 70,
  max_attempts: 2,
  total_questions: 0,
  status: 'draft',
  image_path: '',
  image_alt: ''
})

async function loadExam() {
  try {
    loading.value = true
    const { data } = await axios.get(`/admin/exams/${props.id}`);
    
    form.value = {
      ...form.value,
      title: data.data.title,
      description: data.data.description,
      duration: data.data.duration,
      passing_score: data.data.passing_score,
      max_attempts: data.data.max_attempts,
      total_questions: data.data.total_questions,
      status: data.data.status,
      image_path: data.data.image_path,
      image_alt: data.data.image_alt
    }
  } catch (error) {
    console.error('Failed to load exam:', error)
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  try {
    loading.value = true
    const formData = new FormData()
    
    // Add image if selected
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }
    
    // Add all other form fields
    Object.keys(form.value).forEach(key => {
      formData.append(key, form.value[key])
    })

    const endpoint = props.isEditing 
      ? `/admin/exams/${props.id}`
      : '/admin/exams'
    
    const method = props.isEditing ? 'put' : 'post'
    
    await axios[method](endpoint, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    router.push({ name: 'admin.exams.index' })
  } catch (error) {
    console.error('Failed to save exam:', error)
  } finally {
    loading.value = false
  }
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (!file) return

  if (file.size > 2 * 1024 * 1024) {
    alert('Image size should be less than 2MB')
    return
  }

  imageFile.value = file
  const reader = new FileReader()
  reader.onload = e => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
  
  // Set alt text as filename by default
  form.value.image_alt = file.name.split('.')[0]
}

function removeImage() {
  imageFile.value = null
  imagePreview.value = null
  form.value.image_path = ''
  form.value.image_alt = ''
}

// Load exam data when component mounts
onMounted(() => {
  if (props.isEditing && props.id) {
    loadExam()
  }
})
</script>