<template>
  <!-- ... existing form fields ... -->
  <div>
    <label class="block text-sm font-medium text-gray-700">Exam Image</label>
    <div class="mt-1 flex items-center space-x-4">
      <div class="flex-shrink-0">
        <img
          v-if="preview || form.image_url"
          :src="preview || form.image_url"
          class="h-32 w-32 object-cover rounded-lg"
          :alt="form.image_alt || form.title"
        >
        <div
          v-else
          class="h-32 w-32 rounded-lg bg-gray-100 flex items-center justify-center"
        >
          <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </div>
      <div>
        <input
          type="file"
          accept="image/*"
          class="hidden"
          ref="fileInput"
          @change="handleImageUpload"
        >
        <button
          type="button"
          @click="$refs.fileInput.click()"
          class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
          Choose Image
        </button>
        <p class="mt-2 text-sm text-gray-500">
          PNG, JPG, GIF up to 2MB
        </p>
      </div>
    </div>
  </div>
  <!-- ... rest of the form ... -->
</template>

<script setup>
// ... existing imports and setup ...

const preview = ref(null)

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    form.value.image = file
    preview.value = URL.createObjectURL(file)
  }
}

// ... rest of the component logic ...
</script>