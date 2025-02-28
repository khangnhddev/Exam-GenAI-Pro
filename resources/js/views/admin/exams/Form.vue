<template>
  <!-- ... existing form ... -->
  <div class="space-y-8 divide-y divide-gray-200">
    <div class="space-y-6">
      <!-- Image Upload Section -->
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Exam Image
        </label>
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
                <label
                  class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                >
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

      <!-- ... rest of your form fields ... -->
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'

const imageFile = ref(null)
const imagePreview = ref(null)

const form = reactive({
  // ... existing form fields ...
  image_path: '',
  image_alt: ''
})

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
  form.image_alt = file.name.split('.')[0]
}

function removeImage() {
  imageFile.value = null
  imagePreview.value = null
  form.image_path = ''
  form.image_alt = ''
}

async function handleSubmit() {
  try {
    const formData = new FormData()
    
    // Add image if selected
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }
    
    // Add all form fields
    Object.keys(form).forEach(key => {
      formData.append(key, form[key])
    })

    const response = await axios.post(
      examId.value ? `/api/admin/exams/${examId.value}` : '/api/admin/exams',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    // Handle success
    // ... existing success handling ...

  } catch (error) {
    console.error('Error submitting form:', error)
    // Handle error
  }
}
</script>