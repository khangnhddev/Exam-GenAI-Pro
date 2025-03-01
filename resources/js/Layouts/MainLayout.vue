<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left side -->
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <router-link :to="{ name: 'home' }" class="flex items-center">
                <!-- Logo SVG -->
                <svg class="h-8 w-auto mr-2" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25 2L2 13L25 24L48 13L25 2Z" fill="#2563EB" />
                  <path d="M25 31L2 20V38L25 49L48 38V20L25 31Z" fill="#1D4ED8" fill-opacity="0.7" />
                </svg>
                <!-- Company Name -->
                <div class="flex flex-col">
                  <span class="text-xl font-bold text-gray-900">AIPro</span>
                  <span class="text-xs text-gray-500">AI Certification Platform</span>
                </div>
              </router-link>
            </div>
            <div class="hidden md:block ml-10">
              <div class="flex space-x-6">
                <router-link 
                  v-for="item in navigation" 
                  :key="item.name" 
                  :to="item.to"
                  class="text-sm font-medium transition-colors duration-150"
                  :class="[
                    route.name === item.to.name
                      ? 'text-blue-600'
                      : 'text-gray-700 hover:text-blue-600'
                  ]"
                >
                  {{ item.name }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- Right side -->
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <!-- Notification button -->
              <button class="p-1 rounded-full text-gray-400 hover:text-gray-500">
                <BellIcon class="h-6 w-6" />
              </button>

              <!-- Profile dropdown -->
              <div class="relative ml-3">
                <button 
                  @click="showProfileMenu = !showProfileMenu"
                  class="profile-menu flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  <span class="sr-only">Open user menu</span>
                  <img 
                    class="h-8 w-8 rounded-full"
                    :src="userAvatar"
                    :alt="user?.name || 'User'"
                  />
                </button>

                <!-- Dropdown menu -->
                <transition enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95">
                  <div v-show="showProfileMenu"
                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <router-link v-for="item in profileMenuItems" :key="item.name" :to="item.to"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" @click="showProfileMenu = false">
                      <div class="flex items-center">
                        <component :is="item.icon" class="mr-3 h-5 w-5 text-gray-400" />
                        {{ item.name }}
                      </div>
                    </router-link>

                    <button @click="handleLogout"
                      class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                      <div class="flex items-center">
                        <ArrowRightOnRectangleIcon class="mr-3 h-5 w-5 text-red-400" />
                        Sign out
                      </div>
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <router-view></router-view>
        <AILearningAssistant />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import AILearningAssistant from '../components/AILearningAssistant.vue'
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

const route = useRoute()
const router = useRouter()
const { user, logout } = useAuth()
const showProfileMenu = ref(false)
const isLoading = ref(true)

// Add computed property for user avatar
const userAvatar = computed(() => {
  if (!user.value) return 'https://ui-avatars.com/api/?name=User'
  return user.value.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.value.name)}`
})

const navigation = [
  { name: 'Explore', to: { name: 'exams.index' } },
  { name: 'Resources', to: { name: 'resources' } }
]

const profileMenuItems = [
  {
    name: 'Your Profile',
    to: { name: 'profile' },
    icon: UserCircleIcon
  },
  {
    name: 'My Learning Dashboard',
    to: { name: 'my-learning' },
    icon: BookOpenIcon
  },
  {
    name: 'My Certificates',
    to: { name: 'certificates' },
    icon: AcademicCapIcon
  }
]

// Add click outside handler to close menu
onMounted(async () => {
  document.addEventListener('click', closeProfileMenu)
  try {
    await user.value // Wait for user to be loaded
  } catch (error) {
    console.error('Error loading user:', error)
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
async function handleLogout() {
  await logout()
  showProfileMenu.value = false
}
</script>

<style scoped>
.profile-menu {
  cursor: pointer;
}
</style>