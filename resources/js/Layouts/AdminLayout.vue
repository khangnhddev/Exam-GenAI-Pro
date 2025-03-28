<template>
    <div class="h-screen flex overflow-hidden bg-gray-50">
        <!-- Sidebar with white background -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200">
                    <!-- Logo section -->
                    <div class="flex items-center h-16 flex-shrink-0 px-4 border-b border-gray-200">
                        <router-link to="/admin" class="flex items-center gap-3 py-2">
                            <div class="flex-shrink-0">
                                <div class="flex items-center">
                                    <img src="/images/JVITTechs-Logo.png" alt="JVITTechs Logo" class="h-10 w-auto" />
                                    <div class="flex items-center gap-1 ml-2">
                                        <span class="text-lg font-medium text-indigo-600">AI</span>
                                        <span
                                            class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Pro</span>
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>

                    <!-- Navigation section -->
                    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
                        <nav class="flex-1 px-2 space-y-1">
                            <div v-for="(item, index) in navigationItems" :key="index">
                                <!-- Menu item with children -->
                                <div v-if="item.children" class="space-y-1">
                                    <!-- Parent menu item -->
                                    <button @click="toggleSubmenu(item)"
                                        class="w-full group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                                        :class="[
                                            isActive(item.path)
                                                ? 'bg-indigo-50 text-indigo-600'
                                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                        ]">
                                        <component :is="item.icon" class="mr-3 flex-shrink-0 h-6 w-6" :class="[
                                            isActive(item.path) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500'
                                        ]" />
                                        <span class="flex-1 text-left">{{ item.name }}</span>
                                        <!-- Dropdown arrow -->
                                        <svg :class="[item.isOpen ? 'transform rotate-180' : '', 'h-5 w-5 transition-transform']"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <!-- Submenu items -->
                                    <div v-show="item.isOpen" class="space-y-1">
                                        <router-link v-for="child in item.children" :key="child.path" :to="child.path"
                                            class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md"
                                            :class="[
                                                isActive(child.path)
                                                    ? 'bg-indigo-50 text-indigo-600'
                                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                            ]">
                                            {{ child.name }}
                                        </router-link>
                                    </div>
                                </div>

                                <!-- Single menu item -->
                                <router-link v-else :to="item.path"
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
                            </div>
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
                        <!-- Breadcrumb -->
                        <nav class="hidden sm:flex" aria-label="Breadcrumb">
                            <ol role="list" class="flex items-center space-x-4">
                                <li>
                                    <div class="flex">
                                        <router-link to="/admin"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                            Admin
                                        </router-link>
                                    </div>
                                </li>
                                <template v-for="(crumb, index) in breadcrumbs" :key="index">
                                    <li>
                                        <div class="flex items-center">
                                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" />
                                            <router-link v-if="crumb.path && !isLastCrumb(index)" :to="crumb.path"
                                                class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                                {{ crumb.name }}
                                            </router-link>
                                            <span v-else class="ml-4 text-sm font-medium text-gray-900">
                                                {{ crumb.name }}
                                            </span>
                                        </div>
                                    </li>
                                </template>
                            </ol>
                        </nav>
                    </div>

                    <!-- Right side navigation items -->
                    <div class="ml-4 flex items-center space-x-4">
                        <!-- Search -->
                        <div class="hidden md:block">
                            <div class="relative">
                                <input type="text" placeholder="Search..."
                                    class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="relative">
                            <button @click.stop="showQuickActions = !showQuickActions"
                                class="dropdown-trigger p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <PlusIcon class="h-6 w-6" />
                            </button>
                            <!-- Quick Actions Dropdown -->
                            <div v-if="showQuickActions"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <router-link to="/admin/exams/create"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    New Exam
                                </router-link>
                                <router-link to="/admin/questions/create"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    New Question
                                </router-link>
                                <router-link to="/admin/resources/create"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    New Resource
                                </router-link>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button @click.stop="showNotifications = !showNotifications"
                                class="dropdown-trigger p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <div class="relative">
                                    <BellIcon class="h-6 w-6" />
                                    <span
                                        class="absolute -top-1 -right-1 inline-flex items-center justify-center h-4 w-4 rounded-full bg-red-500 text-xs font-bold text-white">
                                        3
                                    </span>
                                </div>
                            </button>
                            <!-- Notifications Dropdown -->
                            <div v-show="showNotifications"
                                class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-gray-200">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <UserCircleIcon class="h-6 w-6 text-gray-400" />
                                                </div>
                                                <div class="ml-3 w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">New user registration
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500">John Doe has registered</p>
                                                    <p class="mt-1 text-xs text-gray-400">2 minutes ago</p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- Add more notification items here -->
                                    </div>
                                    <div class="px-4 py-2 border-t border-gray-200">
                                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            View all notifications
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative">
                            <button @click.stop="showUserMenu = !showUserMenu"
                                class="dropdown-trigger flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-500">
                                        {{ authStore.user?.name?.[0] ?? 'U' }}
                                    </span>
                                </div>
                            </button>
                            <!-- User Menu Dropdown -->
                            <div v-if="showUserMenu"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <router-link to="/admin/profile"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Your Profile
                                </router-link>
                                <router-link to="/admin/settings"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </router-link>
                                <button @click="handleLogout"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </div>
                        </div>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
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
    ChevronRightIcon,
    MagnifyingGlassIcon,
    PlusIcon,
    UserCircleIcon
} from '@heroicons/vue/24/outline'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const sidebarOpen = ref(false)

// Add reactive state for menu items
const navigationItems = ref([
    {
        name: 'Dashboard',
        path: '/admin',
        icon: HomeIcon,
        description: 'Overview and statistics'
    },
    {
        name: 'User',
        path: '/admin/users',
        icon: UsersIcon,
        description: 'Manage system users'
    },
    {
        name: 'Departments',
        path: '/admin/departments',
        icon: BuildingOffice2Icon,
        description: 'Manage departments and structure'
    },
    {
        name: 'Content',
        path: '/admin/exams',
        icon: DocumentTextIcon,
        description: 'Manage exams and assessments',
        children: [
            {
                name: 'Exams',
                path: '/admin/exams',
                description: 'Manage certification exams'
        },
            {
                name: 'Questions Bank',
                path: '/admin/questions',
                description: 'Manage exam questions'
            },
            {
                name: 'Learning Resources',
                path: '/admin/resources',
                description: 'Manage learning materials'
            }
        ]
    },
    {
        name: 'Certifications',
        path: '/admin/certificates',
        icon: AcademicCapIcon,
        description: 'Manage certificates and badges'
    },
    {
        name: 'Knowledge Base',
        path: '/admin/knowledge-base',
        icon: BookOpenIcon,
        description: 'Manage help articles and guides'
    },
    {
        name: 'Reports & Analytics',
        path: '/admin/analytics',
        icon: ChartBarIcon,
        description: 'View reports and analytics',
        children: [
            {
                name: 'Performance Analytics',
                path: '/admin/analytics',
                description: 'View performance metrics'
            },
            {
                name: 'User Progress',
                path: '/admin/analytics/progress',
                description: 'Track user progress'
            },
            {
                name: 'Exam Statistics',
                path: '/admin/analytics/exams',
                description: 'View exam statistics'
            }
        ]
    },
    {
        name: 'System',
        path: '/admin/settings',
        icon: Cog6ToothIcon,
        description: 'System settings and logs',
        children: [
            {
                name: 'General Settings',
                path: '/admin/settings/general',
                description: 'Basic system configuration',
                children: [
                    {
                        name: 'Site Information',
                        path: '/admin/settings/general/site',
                        description: 'Website name, logo, favicon'
                    },
                    {
                        name: 'Contact Information',
                        path: '/admin/settings/general/contact',
                        description: 'Email, phone, address'
                    },
                    {
                        name: 'SEO Settings',
                        path: '/admin/settings/general/seo',
                        description: 'Meta tags, social media links'
                    }
                ]
            },
            {
                name: 'UI Settings',
                path: '/admin/settings/ui',
                description: 'Customize interface appearance',
                children: [
                    {
                        name: 'Theme Manager',
                        path: '/admin/settings/ui/theme',
                        description: 'Color schemes, dark/light mode'
                    },
                    {
                        name: 'Layout Settings',
                        path: '/admin/settings/ui/layout',
                        description: 'Page layouts, sidebar options'
                    },
                    {
                        name: 'Component Library',
                        path: '/admin/settings/ui/components',
                        description: 'UI components customization'
                    },
                    {
                        name: 'Typography',
                        path: '/admin/settings/ui/typography',
                        description: 'Fonts, text styles, sizes'
                    }
                ]
            },
            {
                name: 'Security Settings',
                path: '/admin/settings/security',
                description: 'System security options',
                children: [
                    {
                        name: 'Authentication',
                        path: '/admin/settings/security/auth',
                        description: 'Login options, 2FA settings'
                    },
                    {
                        name: 'Permissions',
                        path: '/admin/settings/security/permissions',
                        description: 'Role management, access control'
                    },
                    {
                        name: 'API Keys',
                        path: '/admin/settings/security/api',
                        description: 'API authentication, tokens'
                    }
                ]
            },
            {
                name: 'Email Settings',
                path: '/admin/settings/email',
                description: 'Email configuration',
                children: [
                    {
                        name: 'SMTP Configuration',
                        path: '/admin/settings/email/smtp',
                        description: 'Email server settings'
                    },
                    {
                        name: 'Email Templates',
                        path: '/admin/settings/email/templates',
                        description: 'Notification templates'
                    },
                    {
                        name: 'Auto Responders',
                        path: '/admin/settings/email/autoresponders',
                        description: 'Automated email responses'
                    }
                ]
            },
            {
                name: 'Integration',
                path: '/admin/settings/integration',
                description: 'Third-party services',
                children: [
                    {
                        name: 'Social Media',
                        path: '/admin/settings/integration/social',
                        description: 'Social media connections'
                    },
                    {
                        name: 'Payment Gateways',
                        path: '/admin/settings/integration/payment',
                        description: 'Payment provider setup'
                    },
                    {
                        name: 'Analytics',
                        path: '/admin/settings/integration/analytics',
                        description: 'Tracking and analytics tools'
                    }
                ]
            },
            {
                name: 'System Logs',
                path: '/admin/settings/logs',
                description: 'System monitoring',
                children: [
                    {
                        name: 'Activity Logs',
                        path: '/admin/settings/logs/activity',
                        description: 'User activity tracking'
                    },
                    {
                        name: 'Error Logs',
                        path: '/admin/settings/logs/errors',
                        description: 'System error reports'
                    },
                    {
                        name: 'Audit Trail',
                        path: '/admin/settings/logs/audit',
                        description: 'Security audit logs'
                    }
                ]
            },
            {
                name: 'Maintenance',
                path: '/admin/settings/maintenance',
                description: 'System maintenance',
                children: [
                    {
                        name: 'Backup Manager',
                        path: '/admin/settings/maintenance/backup',
                        description: 'Database and file backups'
                    },
                    {
                        name: 'Cache Manager',
                        path: '/admin/settings/maintenance/cache',
                        description: 'System cache control'
                    },
                    {
                        name: 'Updates',
                        path: '/admin/settings/maintenance/updates',
                        description: 'System updates and patches'
                    }
                ]
            },
            {
                name: 'AI Settings',
                path: '/admin/settings/ai',
                description: 'AI configuration and API keys',
                children: [
                    {
                        name: 'Model Configuration',
                        path: '/admin/settings/ai/models',
                        description: 'Configure AI models and providers'
                    },
                    {
                        name: 'API Keys',
                        path: '/admin/settings/ai/api-keys',
                        description: 'Manage AI provider API keys'
                    },
                    {
                        name: 'Usage & Limits',
                        path: '/admin/settings/ai/usage',
                        description: 'Monitor AI usage and set limits'
                    },
                    {
                        name: 'Default Settings',
                        path: '/admin/settings/ai/defaults',
                        description: 'Configure default AI behavior'
                    }
                ]
            }
        ]
    }
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

// Add these refs
const showQuickActions = ref(false)
const showNotifications = ref(false)
const showUserMenu = ref(false)

// Add click outside handler
onMounted(() => {
    document.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns)
})

const closeDropdowns = (event) => {
    // Don't close if clicking inside dropdown content
    if (event.target.closest('.origin-top-right')) return;

    // Don't close if clicking dropdown trigger
    if (event.target.closest('.dropdown-trigger')) return;

    showQuickActions.value = false;
    showNotifications.value = false;
    showUserMenu.value = false;
}

const findMenuItem = (path, items = navigationItems.value) => {
    for (const item of items) {
        if (item.path === path) return item;
        if (item.children) {
            const found = findMenuItem(path, item.children);
            if (found) return found;
        }
    }
    return null;
}

const findParentItem = (path, items = navigationItems.value, parent = null) => {
    for (const item of items) {
        if (item.path === path) return parent;
        if (item.children) {
            const found = findParentItem(path, item.children, item);
            if (found) return found;
        }
    }
    return null;
}

const breadcrumbs = computed(() => {
    const crumbs = [];
    let currentPath = route.path;

    // Find current page
    const currentItem = findMenuItem(currentPath);
    if (!currentItem) return [{ name: currentPageTitle.value, path: currentPath }];

    // Build breadcrumb array
    let item = currentItem;
    crumbs.unshift({ name: item.name, path: item.path });

    // Find parent items
    while (true) {
        const parent = findParentItem(item.path);
        if (!parent) break;
        crumbs.unshift({ name: parent.name, path: parent.path });
        item = parent;
    }

    return crumbs;
});

const isLastCrumb = (index) => {
    return index === breadcrumbs.value.length - 1;
}
</script>

<style scoped>
.router-link-active {
    transition: all 0.3s ease;
}
</style>