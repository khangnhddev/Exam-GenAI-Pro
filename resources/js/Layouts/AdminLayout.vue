<template>
    <div class="h-screen flex overflow-hidden bg-gray-50">
        <!-- Sidebar with white background -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200">
                    <!-- Logo section -->
                    <div class="flex items-center h-16 flex-shrink-0 px-4 border-b border-gray-200">
                        <router-link to="/admin" class="flex items-center gap-3 py-2">
                            <div class="flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 p-2 rounded-lg w-10 h-10">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex items-center">
                                <span class="text-lg font-medium text-gray-900">AI</span>
                                <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Pro</span>
                                <span class="text-lg font-medium text-gray-500 ml-1">Admin</span>
                            </div>
                        </router-link>
                    </div>

                    <!-- Navigation section -->
                    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
                        <nav class="flex-1 px-2 space-y-1">
                            <!-- Navigation items -->
                            <!-- Keep your existing router-links but update their styling -->
                            <router-link v-for="(item, index) in navigationItems" :key="index" :to="item.path"
                                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md" :class="[
                                    isActive(item.path)
                                        ? 'bg-indigo-50 text-indigo-600'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                ]">
                                <component :is="item.icon" class="mr-3 flex-shrink-0 h-6 w-6" :class="[
                                    isActive(item.path) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500'
                                ]" />
                                {{ item.name }}
                            </router-link>
                        </nav>
                    </div>

                    <!-- User profile section -->
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex items-center w-full">
                            <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center">
                                <img v-if="authStore.user?.avatar_url" :src="authStore.user.avatar_url"
                                    class="h-8 w-8 rounded-full" alt="">
                                <span v-else class="text-sm font-medium text-gray-500">
                                    {{ authStore.user?.name?.[0] ?? 'U' }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">{{ authStore.user?.name }}</p>
                                <button @click="handleLogout" class="text-xs text-gray-500 hover:text-gray-700">
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
                <button @click="sidebarOpen = true"
                    class="md:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
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
    BuildingOffice2Icon,
} from '@heroicons/vue/24/outline'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const sidebarOpen = ref(false)

// Add reactive state for menu items
const navigationItems = ref([
    { name: 'Dashboard', path: '/admin', icon: HomeIcon },
    { name: 'Users', path: '/admin/users', icon: UsersIcon },
    {
        name: 'Department',
        path: '/admin/departments',
        icon: BuildingOffice2Icon,
        isOpen: false,
        children: [
            {
                name: 'All Departments',
                path: '/admin/departments'
            },
            {
                name: 'Add Department',
                path: '/admin/departments/create'
            }
        ]
    },
    { name: 'Exams', path: '/admin/exams', icon: DocumentTextIcon },
    { name: 'Questions', path: '/admin/questions', icon: QuestionMarkCircleIcon },
    { name: 'Resources', path: '/admin/resources', icon: DocumentDuplicateIcon },
    { name: 'Certificates', path: '/admin/certificates', icon: AcademicCapIcon },
    { name: 'Activity Logs', path: '/admin/activity-logs', icon: ClipboardDocumentListIcon },
    { name: 'Knowledge Base', path: '/admin/knowledge-base', icon: BookOpenIcon },
    { name: 'Results', path: '/admin/results', icon: ChartBarIcon },
    { name: 'Analytics', path: '/admin/analytics', icon: ChartPieIcon },
    { name: 'Settings', path: '/admin/settings', icon: Cog6ToothIcon }
])

const toggleSubmenu = (item) => {
    item.isOpen = !item.isOpen
}

const currentPageTitle = computed(() => {
    const currentItem = navigationItems.value.find(item => route.path.startsWith(item.path))
    return currentItem?.name || 'Dashboard'
})

const isActive = (path) => {
    return route.path.startsWith(path)
}

const handleLogout = async () => {
    try {
        await authStore.logout()
        router.push('/')
    } catch (error) {
        console.error('Logout failed:', error)
    }
}
</script>

<style scoped>
.router-link-active {
    transition: all 0.3s ease;
}
</style>