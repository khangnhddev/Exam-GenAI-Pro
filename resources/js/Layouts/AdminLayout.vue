<template>
    <div class="h-screen flex overflow-hidden bg-gray-50">
        <!-- Sidebar with white background -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200">
                    <!-- Logo section -->
                    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-white border-b border-gray-200">
                        <svg class="h-8 w-auto mr-2" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 2L2 13L25 24L48 13L25 2Z" fill="#111827"/>
                            <path d="M25 31L2 20V38L25 49L48 38V20L25 31Z" fill="#4F46E5" fill-opacity="0.7"/>
                        </svg>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-gray-900">AIPro</span>
                            <span class="text-xs text-gray-500">Admin Dashboard</span>
                        </div>
                    </div>

                    <!-- Navigation section -->
                    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
                        <nav class="flex-1 px-2 space-y-1">
                            <!-- Navigation items -->
                            <!-- Keep your existing router-links but update their styling -->
                            <router-link v-for="(item, index) in navigationItems" 
                                :key="index"
                                :to="item.path"
                                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                                :class="[
                                    isActive(item.path)
                                        ? 'bg-indigo-50 text-indigo-600'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                ]"
                            >
                                <component 
                                    :is="item.icon" 
                                    class="mr-3 flex-shrink-0 h-6 w-6"
                                    :class="[
                                        isActive(item.path) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500'
                                    ]"
                                />
                                {{ item.name }}
                            </router-link>
                        </nav>
                    </div>

                    <!-- User profile section -->
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex items-center w-full">
                            <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center">
                                <img 
                                    v-if="authStore.user?.avatar_url" 
                                    :src="authStore.user.avatar_url" 
                                    class="h-8 w-8 rounded-full"
                                    alt=""
                                >
                                <span v-else class="text-sm font-medium text-gray-500">
                                    {{ authStore.user?.name?.[0] ?? 'U' }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">{{ authStore.user?.name }}</p>
                                <button 
                                    @click="handleLogout"
                                    class="text-xs text-gray-500 hover:text-gray-700"
                                >
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm">
                <button 
                    @click="sidebarOpen = true"
                    class="md:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                >
                    <span class="sr-only">Open sidebar</span>
                    <MenuIcon class="h-6 w-6" />
                </button>
                
                <!-- Top nav content -->
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ currentPageTitle }}
                        </h1>
                    </div>
                    
                    <!-- Notification button -->
                    <div class="ml-4 flex items-center md:ml-6">
                        <button class="p-1 rounded-full text-gray-400 hover:text-gray-500">
                            <BellIcon class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <router-view></router-view>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
    Bars3Icon as MenuIcon,
    BellIcon,
    HomeIcon,
    UsersIcon,
    DocumentTextIcon,
    QuestionMarkCircleIcon,
    AcademicCapIcon,
    ChartBarIcon,
    ChartPieIcon,
    Cog6ToothIcon,
    ClipboardDocumentListIcon,
    BookOpenIcon,
    DocumentDuplicateIcon, // Add this import for Resources icon
} from '@heroicons/vue/24/outline'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const sidebarOpen = ref(false)

const navigationItems = [
    { name: 'Dashboard', path: '/admin', icon: HomeIcon },
    { name: 'Users', path: '/admin/users', icon: UsersIcon },
    { name: 'Exams', path: '/admin/exams', icon: DocumentTextIcon },
    { name: 'Questions', path: '/admin/questions', icon: QuestionMarkCircleIcon },
    { name: 'Resources', path: '/admin/resources', icon: DocumentDuplicateIcon }, // Add Resources menu item
    { name: 'Certificates', path: '/admin/certificates', icon: AcademicCapIcon },
    { name: 'Activity Logs', path: '/admin/activity-logs', icon: ClipboardDocumentListIcon },
    { name: 'Knowledge Base', path: '/admin/knowledge-base', icon: BookOpenIcon },
    { name: 'Results', path: '/admin/results', icon: ChartBarIcon },
    { name: 'Analytics', path: '/admin/analytics', icon: ChartPieIcon },
    { name: 'Settings', path: '/admin/settings', icon: Cog6ToothIcon },
]

const currentPageTitle = computed(() => {
    return navigationItems.find(item => route.path.startsWith(item.path))?.name || 'Dashboard'
})

const isActive = (path) => {
    return route.path.startsWith(path)
}

const handleLogout = async () => {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.router-link-active {
    transition: all 0.3s ease;
}
</style>