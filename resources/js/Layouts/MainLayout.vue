<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-indigo-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left side -->
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <router-link :to="{ name: 'home' }" class="flex items-center">
                <!-- Logo SVG -->
                <svg class="h-8 w-auto mr-2" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25 2L2 13L25 24L48 13L25 2Z" fill="white"/>
                  <path d="M25 31L2 20V38L25 49L48 38V20L25 31Z" fill="white" fill-opacity="0.7"/>
                  <circle cx="25" cy="25" r="8" fill="#4F46E5"/>
                  <path d="M25 19C23.4087 19 21.8826 19.6321 20.7574 20.7574C19.6321 21.8826 19 23.4087 19 25C19 26.5913 19.6321 28.1174 20.7574 29.2426C21.8826 30.3679 23.4087 31 25 31C26.5913 31 28.1174 30.3679 29.2426 29.2426C30.3679 28.1174 31 26.5913 31 25C31 23.4087 30.3679 21.8826 29.2426 20.7574C28.1174 19.6321 26.5913 19 25 19Z" fill="#4F46E5"/>
                </svg>
                <!-- Company Name -->
                <div class="flex flex-col">
                  <span class="text-xl font-bold text-white">ExamPro</span>
                  <span class="text-xs text-indigo-200">Learning Management System</span>
                </div>
              </router-link>
            </div>
            <div class="hidden md:block ml-10">
              <div class="flex space-x-4">
                <router-link
                  v-for="item in navigation"
                  :key="item.name"
                  :to="item.to"
                  class="px-3 py-2 rounded-md text-sm font-medium"
                  :class="[
                    route.name === item.to.name
                      ? 'bg-indigo-700 text-white'
                      : 'text-indigo-200 hover:bg-indigo-500 hover:text-white'
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
              <!-- Profile dropdown -->
              <div class="ml-3 relative">
                <div>
                  <button
                    @click="showProfileMenu = !showProfileMenu"
                    class="max-w-xs bg-indigo-600 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white"
                  >
                    <span class="sr-only">Open user menu</span>
                    <img
                      v-if="user?.avatar"
                      :src="user.avatar"
                      class="h-8 w-8 rounded-full"
                    >
                    <span v-else class="h-8 w-8 rounded-full bg-indigo-700 flex items-center justify-center text-white">
                      {{ user?.name?.charAt(0).toUpperCase() }}
                    </span>
                  </button>
                </div>

                <div
                  v-if="showProfileMenu"
                  class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                >
                  <router-link
                    :to="{ name: 'profile' }"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Your Profile
                  </router-link>
                  <router-link
                    :to="{ name: 'my-learning' }"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    My Learning
                  </router-link>
                  <router-link
                    :to="{ name: 'certificates' }"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Certificates
                  </router-link>
                  <button
                    @click="logout"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Sign out
                  </button>
                </div>
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
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import AILearningAssistant from '../components/AILearningAssistant.vue'

const route = useRoute()
const router = useRouter()
const { user, logout } = useAuth()
const showProfileMenu = ref(false)

const navigation = [
  { name: 'Home', to: { name: 'home' } },
  { name: 'Exams', to: { name: 'exams.index' } },
  { name: 'My Learning', to: { name: 'my-learning' } },
  { name: 'Certificates', to: { name: 'certificates' } }
]
</script>