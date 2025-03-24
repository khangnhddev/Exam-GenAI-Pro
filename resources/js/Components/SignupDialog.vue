<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="onClose" class="relative z-50">
      <!-- Overlay -->
      <TransitionChild enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
        leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" />
      </TransitionChild>

      <!-- Dialog positioning -->
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
          <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95" class="w-full">
            <DialogPanel
              class="mx-auto w-full max-w-md transform overflow-hidden rounded-2xl bg-gradient-to-br from-gray-900 via-purple-900 to-violet-800 p-8 text-left align-middle shadow-xl transition-all">
              <!-- Close button -->
              <button @click="onClose"
                class="absolute right-6 top-6 rounded-lg p-1 text-gray-400 hover:text-gray-300 focus:outline-none">
                <XMarkIcon class="h-6 w-6" />
              </button>

              <!-- Content container -->
              <div class="mx-auto max-w-xs w-full space-y-8">
                <!-- Logo/Brand -->
                <div class="flex flex-col items-center justify-center">
                  <div class="flex items-center justify-center gap-2 mb-6">
                    <div class="flex items-center bg-white/10 p-3 rounded-xl backdrop-blur-sm">
                      <!-- <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                      </svg> -->
                      <img src="/images/JVITTechs-Logo.png" alt="JVITTechs Logo" class="h-10 w-auto" />
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="text-2xl font-medium text-white">AI</span>
                      <span
                        class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">Pro</span>
                    </div>
                  </div>
                  <h2 class="text-3xl font-bold text-white mb-2">Create an account</h2>
                  <p class="text-gray-300">Join our community to start learning</p>
                </div>

                <!-- Sign Up Form -->
                <form @submit.prevent="handleSignUp" class="space-y-6">
                  <!-- Full Name Input -->
                  <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-200">
                      Full Name
                    </label>
                    <div class="mt-1 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <UserIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input id="fullname" v-model="form.fullname" type="text" required
                        class="block w-full pl-10 py-2 sm:text-sm rounded-lg bg-white/10 border border-gray-400/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Your name" />
                    </div>
                    <div v-if="errors.fullname" class="mt-1 text-sm text-red-400">
                      {{ errors.fullname[0] }}
                    </div>
                  </div>

                  <!-- Email Input -->
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-200">
                      Email address
                    </label>
                    <div class="mt-1 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input id="email" v-model="form.email" type="email" required
                        class="block w-full pl-10 py-2 sm:text-sm rounded-lg bg-white/10 border border-gray-400/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="you@example.com" />
                    </div>
                    <div v-if="errors.email" class="mt-1 text-sm text-red-400">
                      {{ errors.email[0] }}
                    </div>
                  </div>

                  <!-- Password Input -->
                  <div>
                    <label for="password" class="block text-sm font-medium text-gray-200">
                      Password
                    </label>
                    <div class="mt-1 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <LockClosedIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input id="password" v-model="form.password" type="password" required
                        class="block w-full pl-10 py-2 sm:text-sm rounded-lg bg-white/10 border border-gray-400/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="••••••••" />
                    </div>
                    <div v-if="errors.password" class="mt-1 text-sm text-red-400">
                      {{ errors.password[0] }}
                    </div>
                  </div>

                  <!-- Password Confirmation Input -->
                  <!-- <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-200">
                      Confirm Password
                    </label>
                    <div class="mt-1 relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <KeyIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        class="block w-full pl-10 py-2 sm:text-sm rounded-lg bg-white/10 border border-gray-400/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="••••••••"
                      />
                    </div>
                  </div> -->

                  <!-- Submit Button -->
                  <button type="submit" :disabled="loading"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    {{ loading ? 'Creating account...' : 'Create account' }}
                  </button>
                </form>
                <!-- Error Message -->
                <!-- <div v-if="Object.keys(errors).length > 0" class="mt-4">
                  <div class="rounded-lg bg-red-500/10 backdrop-blur-sm border border-red-500/20 p-4">
                    <div class="flex">
                      <ExclamationCircleIcon class="h-5 w-5 text-red-400" />
                      <div class="ml-3">
                        <ul class="list-disc list-inside">
                          <template v-for="(errorMessages, field) in errors" :key="field">
                            <li v-for="message in errorMessages" :key="message" class="text-sm font-medium text-red-300">
                              {{ message }}
                            </li>
                          </template>
</ul>
</div>
</div>
</div>
</div> -->

                <!-- Footer -->
                <div class="text-center">
                  <p class="text-sm text-gray-400">
                    Already have an account?
                    <button @click="switchToLogin" class="font-medium text-indigo-400 hover:text-indigo-300">
                      Sign in
                    </button>
                  </p>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {
  XMarkIcon,
  ExclamationCircleIcon,
  UserIcon,
  EnvelopeIcon,
  LockClosedIcon,
  EyeIcon,
  EyeSlashIcon,
  KeyIcon
} from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const props = defineProps({
  isOpen: Boolean
})

const router = useRouter();
const authStore = useAuthStore();

const emit = defineEmits(['close', 'switch-to-login'])

const toast = useToast()
const loading = ref(false)
const errors = ref({})
const showPassword = ref(false)

const form = reactive({
  name: '',
  fullname: '',
  email: '',
  password: '',
  password_confirmation: ''
})

function onClose() {
  errors.value = {}
  loading.value = false
  emit('close')
  // Reset form
  form.fullname = ''
  form.email = ''
  form.password = ''
  form.password_confirmation = ''
}

function switchToLogin() {
  emit('switch-to-login')
}

async function handleSignUp() {
  loading.value = true
  errors.value = {}
  try {
    await useAuthStore().register(form)
    toast.success('Registration successful!')
    // Clear form
    form.fullname = ''
    form.email = ''
    form.password = ''
    form.password_confirmation = ''
    // Close signup dialog
    emit('close')
    // Switch to login dialog
    emit('switch-to-login')
  } catch (err) {
    console.error('Registration error:', err)
    if (err.response?.status === 422) {
      // Server validation errors
      errors.value = err.response.data.errors
    } else {
      // Other errors
      errors.value = {
        general: [err.response?.data?.message || 'Registration failed. Please try again.']
      }
    }
  } finally {
    loading.value = false
  }
}
</script>