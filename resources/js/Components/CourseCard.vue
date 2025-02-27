<template>
  <div 
    :class="[
      'bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow',
      viewType === 'list' ? 'flex items-center p-4' : 'p-6'
    ]"
  >
    <div :class="{ 'flex-1': viewType === 'list' }">
      <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
          <div 
            class="h-12 w-12 rounded-lg flex items-center justify-center"
            :class="getLevelClass(course.level)"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-gray-900">{{ course.title }}</h3>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ course.description }}</p>
        </div>
      </div>

      <div :class="['mt-4', viewType === 'list' ? 'flex items-center justify-between' : '']">
        <div class="flex items-center space-x-4 text-sm text-gray-500">
          <span>{{ course.lessons_count }} lessons</span>
          <span>{{ course.duration }} hours</span>
          <span>{{ course.students_count }} students</span>
        </div>
        
        <router-link
          :to="{ name: 'courses.show', params: { id: course.id }}"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
        >
          View Course
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStyles } from '../composables/useStyles'  // Update import path

const props = defineProps({
  course: {
    type: Object,
    required: true
  },
  viewType: {
    type: String,
    default: 'grid'
  }
})

const { getLevelClass } = useStyles()

// Estimate course duration (30 mins per lesson)
const estimatedHours = computed(() => {
  return Math.ceil((props.course.lessons_count * 30) / 60)
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>