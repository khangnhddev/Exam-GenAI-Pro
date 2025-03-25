<template>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- AI Pro Logo -->
            <svg class="mx-auto h-12 w-auto" viewBox="0 0 567 567" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="567" height="567" fill="transparent" />
                <path
                    d="M408.173 120.286C373.385 80.8661 322.962 56 267.019 56C156.066 56 66 146.066 66 257.019C66 367.972 156.066 458.038 267.019 458.038C322.962 458.038 373.385 433.172 408.173 393.752"
                    stroke="#7E22CE" stroke-width="32" stroke-linecap="round" />
                <path d="M501 257.019H298.481" stroke="#7E22CE" stroke-width="32" stroke-linecap="round" />
                <circle cx="267.019" cy="257.019" r="52.4919" fill="#7E22CE" />
            </svg>

            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                Email Verification
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We're verifying your email address to secure your account
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-8 px-4 shadow-xl shadow-purple-100/10 sm:rounded-lg sm:px-10 border border-purple-50">
                <!-- Loading State -->
                <div v-if="isLoading" class="flex flex-col items-center py-6">
                    <div class="flex items-center justify-center">
                        <div class="h-16 w-16">
                            <svg class="animate-spin h-16 w-16 text-purple-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="3"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-base text-gray-600">Verifying your email address...</p>
                </div>

                <!-- Success State -->
                <div v-else-if="isVerified" class="text-center py-6">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-purple-100">
                        <CheckIcon class="h-10 w-10 text-purple-600" aria-hidden="true" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Verification Successful</h3>
                    <p class="mt-2 text-base text-gray-600">{{ message }}</p>
                    <div class="mt-8">
                        <router-link to="/"
                            class="inline-flex w-full justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <ArrowLeftIcon class="h-5 w-5 mr-2" />
                            Return to Home
                        </router-link>
                    </div>
                </div>

                <!-- Error State -->
                <div v-else class="text-center py-6">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-purple-100">
                        <XMarkIcon class="h-10 w-10 text-purple-600" aria-hidden="true" />
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Verification Failed</h3>
                    <p class="mt-2 text-base text-gray-600">{{ error }}</p>

                    <div class="mt-8 space-y-4">
                        <button @click="resendVerification" :disabled="isResending"
                            class="inline-flex w-full justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                            <div v-if="isResending" class="mr-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                            <ArrowPathIcon v-else class="h-5 w-5 mr-2" />
                            {{ isResending ? 'Sending verification email...' : 'Resend Verification Email' }}
                        </button>

                        <router-link to="/support"
                            class="inline-flex w-full justify-center items-center px-4 py-3 border border-purple-200 text-base font-medium rounded-md text-purple-700 bg-purple-50 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <QuestionMarkCircleIcon class="h-5 w-5 mr-2" />
                            Need Help?
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
    CheckIcon,
    XMarkIcon,
    ArrowLeftIcon,
    ArrowPathIcon,
    QuestionMarkCircleIcon
} from '@heroicons/vue/24/outline'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const isLoading = ref(true)
const isVerified = ref(false)
const isResending = ref(false)
const message = ref('')
const error = ref('')

onMounted(async () => {
    try {
        const { id, hash } = route.params;
        const { expires, signature } = route.query;

        // Include the full signed URL parameters
        const response = await axios.get(`/email/verify/${id}/${hash}`, {
            baseURL: '/api', // Override base URL without v1 prefix
            params: {
                expires,
                signature
            }
        })
        isVerified.value = true
        message.value = response.data.message
    } catch (err) {
        console.error('Verification error:', err)
        error.value = err.response?.data?.message ||
            'The verification link may be invalid or has expired.'
        isVerified.value = false
    } finally {
        isLoading.value = false
    }
})

async function resendVerification() {
    if (isResending.value) return

    isResending.value = true
    try {
        const response = await axios.post('/api/email/resend-verification')
        message.value = response.data.message
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to resend verification email'
    } finally {
        isResending.value = false
    }
}
</script>