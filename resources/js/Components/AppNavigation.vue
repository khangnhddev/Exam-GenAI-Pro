<template>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <router-link to="/" class="text-xl font-bold text-indigo-600">
              GenAI Academy
            </router-link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <!-- Existing Links -->
            <router-link 
              to="/courses" 
              class="nav-link"
              :class="{ 'active': $route.path.startsWith('/courses') }"
            >
              Courses
            </router-link>

            <!-- New Exam System Links -->
            <router-link 
              to="/exams" 
              class="nav-link"
              :class="{ 'active': $route.path.startsWith('/exams') }"
            >
              Certifications
            </router-link>

            <router-link 
              to="/my-certificates" 
              class="nav-link"
              :class="{ 'active': $route.path.startsWith('/my-certificates') }"
            >
              My Certificates
            </router-link>

            <router-link 
              to="/verify-certificate" 
              class="nav-link"
              :class="{ 'active': $route.path === '/verify-certificate' }"
            >
              Verify Certificate
            </router-link>
          </div>
        </div>

        <!-- Right Side Menu -->
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <div class="relative" v-if="authStore.user">
            <!-- Profile Dropdown -->
            <div class="ml-3 relative">
              <button 
                @click="isProfileOpen = !isProfileOpen"
                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900"
              >
                <span>{{ authStore.user.name }}</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div v-if="isProfileOpen" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                <div class="py-1 bg-white rounded-md shadow-xs">
                  <router-link 
                    to="/profile" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Profile
                  </router-link>
                  <router-link 
                    to="/my-learning" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    My Learning
                  </router-link>
                  <router-link 
                    to="/my-exams" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    My Exams
                  </router-link>
                  <router-link 
                    to="/logout" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Sign out
                  </router-link>
                  <a 
                    href="#" 
                    @click.prevent="handleLogout" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Sign out
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="flex items-center sm:hidden">
          <button 
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="isMobileMenuOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <router-link 
          to="/courses"
          class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
        >
          Courses
        </router-link>
        <router-link 
          to="/exams"
          class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
        >
          Certifications
        </router-link>
        <router-link 
          to="/my-certificates"
          class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
        >
          My Certificates
        </router-link>
        <router-link 
          to="/verify-certificate"
          class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
        >
          Verify Certificate
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const isProfileOpen = ref(false)
const isMobileMenuOpen = ref(false)

onMounted(async () => {
    await authStore.fetchUser()
})

const handleLogout = () => {
    authStore.logout()
    isProfileOpen.value = false
}
</script>

<style scoped>
.nav-link {
  @apply inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900;
}

.nav-link.active {
  @apply border-b-2 border-indigo-500 text-gray-900;
}
</style>