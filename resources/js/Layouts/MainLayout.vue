<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Changed nav to fixed position -->
    <nav v-if="!isHideNavigation" 
      class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50 backdrop-blur-lg bg-white/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20"> <!-- Increased height -->
          <!-- Left side with logo -->
          <div class="flex items-center gap-8"> <!-- Increased gap -->
            <!-- Logo remains same -->
            <div class="flex-shrink-0">
              <router-link to="/" class="flex items-center gap-2">
                <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 rounded-lg">
                  <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <span class="text-lg font-medium text-indigo-600">AI</span>
                  <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Pro</span>
                </div>
              </router-link>
            </div>

            <!-- Updated Navigation Links -->
            <div class="hidden md:flex items-center space-x-8"> <!-- Increased spacing -->
              <router-link 
                v-for="item in navigation" 
                :key="item.name" 
                :to="item.to"
                class="text-base font-semibold transition-colors duration-150 px-3 py-2 rounded-md"
                :class="[
                  route.name === item.to.name
                    ? 'text-indigo-600 bg-indigo-50'
                    : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50'
                ]"
              >
                {{ item.name }}
              </router-link>
            </div>
          </div>

          <!-- Center - Search Bar -->
          <div class="hidden md:flex flex-1 justify-center max-w-lg px-8">
            <div class="relative w-full">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                type="text"
                placeholder="Search exams..."
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-gray-50"
              >
            </div>
          </div>

          <!-- Right side -->
          <div class="hidden md:flex items-center gap-6">
            <!-- User is logged in -->
            <template v-if="user">
              <!-- Notification button with badge -->
              <button class="relative p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100">
                <BellIcon class="h-6 w-6" />
                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-indigo-600 ring-2 ring-white"></span>
              </button>

              <!-- Profile dropdown -->
              <div class="relative">
                <button 
                  @click="showProfileMenu = !showProfileMenu"
                  class="profile-menu flex items-center gap-2 p-1.5 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:bg-gray-100"
                >
                  <img 
                    class="h-9 w-9 rounded-full border-2 border-gray-200"
                    :src="userAvatar"
                    :alt="user?.name || 'User'"
                  />
                  <span class="font-medium text-gray-700">{{ user?.fullname }}</span>
                </button>

                <!-- Dropdown menu -->
                <transition enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95">
                  <div v-show="showProfileMenu"
                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <router-link v-for="item in profileMenuItems" :key="item.name" :to="item.to"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600" 
                      @click="showProfileMenu = false">
                      <div class="flex items-center">
                        <component :is="item.icon" class="mr-3 h-5 w-5 text-gray-400" />
                        {{ item.name }}
                      </div>
                    </router-link>

                    <button @click="handleLogout"
                      class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                      <div class="flex items-center">
                        <ArrowRightOnRectangleIcon class="mr-3 h-5 w-5 text-red-400" />
                        Sign out
                      </div>
                    </button>
                  </div>
                </transition>
              </div>
            </template>

            <!-- User is not logged in -->
            <template v-else>
              <div class="flex items-center gap-4">
                <button 
                  @click="showLoginDialog = true"
                  class="text-base font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-150 px-3 py-2 rounded-md hover:bg-gray-50"
                >
                  Sign in
                </button>
                <button 
                  @click="showSignUpDialog = true"
                  class="inline-flex items-center px-4 py-2.5 border border-transparent text-base font-semibold rounded-xl shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                >
                  Sign up
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Add padding-top to main content to prevent overlap -->
    <main class="flex-1 py-10 mt-9">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <router-view></router-view>
        <!-- <AILearningAssistant /> -->
      </div>
    </main>

    <!-- Add Footer -->
    <Footer v-if="!isHideNavigation" />
  </div>

  <!-- Login Dialog -->
  <LoginDialog 
    :is-open="showLoginDialog" 
    @close="showLoginDialog = false"
    @success="handleLoginSuccess"
    @switch-to-signup="switchToSignup"
  />
  
  <SignUpDialog
    :is-open="showSignUpDialog"
    @close="showSignUpDialog = false"
    @switch-to-login="switchToLogin"
  />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import AILearningAssistant from '../Components/AILearningAssistant.vue'
import Footer from '../Components/Footer.vue'
import {
  HomeIcon,
  AcademicCapIcon,
  BookOpenIcon,
  ClipboardDocumentListIcon,
  BellIcon,
  ArrowRightOnRectangleIcon,
  UserCircleIcon,
  Cog6ToothIcon
} from '@heroicons/vue/24/outline'
import { useAuthStore } from '@/stores/auth'
import LoginDialog from '@/Components/LoginDialog.vue'
import SignUpDialog from '@/Components/SignupDialog.vue'

const route = useRoute()
const router = useRouter()
const { user, logout, checkAuth } = useAuth()
const showProfileMenu = ref(false)
const isLoading = ref(true)
const authStore = useAuthStore()
const showLoginDialog = ref(false)
const showSignUpDialog = ref(false)

const isAuthenticated = computed(() => authStore.isAuthenticated)

// Add computed property for user avatar
const userAvatar = computed(() => {
  console.log(user.value.fullname);
  if (!user.value) return 'https://ui-avatars.com/api/?name=User'
  return user.value.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.value.fullname)}`
})

const navigation = computed(() => {
  // Menu items for logged in users
  if (user.value) {
    return [
      { name: 'Explore', to: { name: 'exams.index' } },
      { name: 'Resources', to: { name: 'resources' } },
    ]
  }
  
  // Menu items for guests
  return [
    { name: 'Explore', to: { name: 'exams.index' } },
    { name: 'Resources', to: { name: 'resources' } }
  ]
})

const profileMenuItems = [
  {
    name: 'Your Profile',
    to: { name: 'profile' },
    icon: UserCircleIcon
  },
  {
    name: 'History',
    to: { name: 'my-learning' },
    icon: BookOpenIcon
  },
  {
    name: 'Certificates',
    to: { name: 'certificates' },
    icon: AcademicCapIcon
  }
]

// Check if current route should hide navigation
const isHideNavigation = computed(() => {
  return ['exams.attempt', 'exams.result'].includes(route.name)
})

// Add click outside handler to close menu
onMounted(async () => {
  document.addEventListener('click', closeProfileMenu)
  try {
    await checkAuth() // Check authentication state when component mounts
  } catch (error) {
    console.error('Error checking auth:', error)
  } finally {
    isLoading.value = false
  }
})

onUnmounted(() => {
  document.removeEventListener('click', closeProfileMenu)
})

function closeProfileMenu(event) {
  if (!event.target.closest('.profile-menu')) {
    showProfileMenu.value = false
  }
}

// Handle logout
const handleLogout = async () => {
  try {
    await logout()
    router.push('/')
  } catch (error) {
    console.error('Logout failed:', error)
  }
}

const handleLoginSuccess = () => {
  showLoginDialog.value = false
}

function switchToSignup() {
  showLoginDialog.value = false
  showSignUpDialog.value = true
}

function switchToLogin() {
  showSignUpDialog.value = false
  showLoginDialog.value = true
}
</script>

<style scoped>
.profile-menu {
  cursor: pointer;
}
</style>