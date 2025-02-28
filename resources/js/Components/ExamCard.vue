<template>
  <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
    <div class="aspect-w-16 aspect-h-9 rounded-t-lg overflow-hidden">
      <img 
        :src="exam.image_url" 
        :alt="exam.image_alt || exam.title"
        class="w-full h-full object-cover"
      >
    </div>
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ exam.title }}</h3>
      <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ exam.description }}</p>
      <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-500">
          <span class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ exam.duration }} mins
          </span>
          <span class="ml-4 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ exam.total_questions }} questions
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  exam: {
    type: Object,
    required: true
  }
})
</script>