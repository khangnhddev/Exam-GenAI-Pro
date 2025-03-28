<template>
  <div class="rounded-md bg-purple-50 p-4 mb-6">
    <div class="flex">
      <div class="flex-shrink-0">
        <InformationCircleIcon class="h-5 w-5 text-purple-400" aria-hidden="true" />
      </div>
      <div class="ml-3 flex-1 md:flex md:justify-between">
        <p class="text-sm text-purple-700">
          Please verify your email address to access all features. Check your inbox for the verification link.
        </p>
        <p class="mt-3 text-sm md:mt-0 md:ml-6">
          <button
            @click="resendVerification"
            :disabled="isResending"
            class="whitespace-nowrap font-medium text-purple-700 hover:text-purple-600 disabled:opacity-50"
          >
            <span v-if="isResending">Sending...</span>
            <span v-else>Resend verification email</span>
          </button>
        </p>
      </div>
    </div>
    
    <!-- Success Message -->
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div v-if="showSuccess" class="mt-2 text-sm text-green-600">
        Verification email sent successfully!
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

const isResending = ref(false)
const showSuccess = ref(false)

async function resendVerification() {
  if (isResending.value) return
  
  isResending.value = true
  try {
    await axios.post('/api/email/resend-verification')
    showSuccess.value = true
    setTimeout(() => {
      showSuccess.value = false
    }, 3000)
  } catch (error) {
    console.error('Failed to resend:', error)
  } finally {
    isResending.value = false
  }
}
</script>