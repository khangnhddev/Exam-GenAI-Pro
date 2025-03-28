<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8 text-center">
      <!-- Error Icon -->
      <div class="mx-auto h-24 w-24">
        <component 
          :is="errorIcon" 
          class="h-full w-full" 
          :class="iconColor"
        />
      </div>

      <!-- Error Content -->
      <div class="space-y-4">
        <h1 class="text-4xl font-bold text-gray-900">
          {{ title }}
        </h1>
        <p class="text-lg text-gray-600">
          {{ message }}
        </p>
        <p class="text-sm text-gray-500" v-if="errorCode">
          Error {{ errorCode }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex justify-center space-x-4 pt-6">
        <button 
          @click="goBack"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium rounded-md border border-gray-300 shadow-sm bg-white text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <ArrowLeftIcon class="h-5 w-5 mr-2" />
          Go Back
        </button>
        <button 
          @click="goHome"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-md shadow-sm transition-all"
        >
          Home
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { 
  ExclamationTriangleIcon,
  ExclamationCircleIcon,
  ServerStackIcon,
  ShieldExclamationIcon,
  SignalSlashIcon
} from '@heroicons/vue/24/outline'
import { ERROR_TYPES } from '@/utils/errorHandler'

const props = defineProps({
  type: {
    type: String,
    default: ERROR_TYPES.UNKNOWN
  },
  message: {
    type: String,
    required: true
  },
  errorCode: {
    type: [Number, String],
    default: null
  }
})

const router = useRouter()

const errorIcon = computed(() => {
  const icons = {
    [ERROR_TYPES.VALIDATION]: ExclamationCircleIcon,
    [ERROR_TYPES.AUTH]: ShieldExclamationIcon,
    [ERROR_TYPES.NOT_FOUND]: ExclamationTriangleIcon,
    [ERROR_TYPES.SERVER]: ServerStackIcon,
    [ERROR_TYPES.NETWORK]: SignalSlashIcon,
    [ERROR_TYPES.UNKNOWN]: ExclamationCircleIcon
  }
  return icons[props.type]
})

const iconColor = computed(() => {
  const colors = {
    [ERROR_TYPES.VALIDATION]: 'text-yellow-500',
    [ERROR_TYPES.AUTH]: 'text-red-500',
    [ERROR_TYPES.NOT_FOUND]: 'text-orange-500',
    [ERROR_TYPES.SERVER]: 'text-purple-500',
    [ERROR_TYPES.NETWORK]: 'text-blue-500',
    [ERROR_TYPES.UNKNOWN]: 'text-gray-500'
  }
  return colors[props.type]
})

const title = computed(() => {
  const titles = {
    [ERROR_TYPES.VALIDATION]: 'Invalid Input',
    [ERROR_TYPES.AUTH]: 'Access Denied',
    [ERROR_TYPES.NOT_FOUND]: 'Not Found',
    [ERROR_TYPES.SERVER]: 'Server Error',
    [ERROR_TYPES.NETWORK]: 'Connection Error',
    [ERROR_TYPES.UNKNOWN]: 'Oops!'
  }
  return titles[props.type]
})

const goBack = () => router.back()
const goHome = () => router.push({ name: 'home' })
</script>