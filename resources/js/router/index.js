import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Import the admin layout
import AdminLayout from '../layouts/AdminLayout.vue'
import ActivityLogManager from '../components/admin/ActivityLogManager.vue'
import KnowledgeBaseManager from '../components/admin/KnowledgeBaseManager.vue'

// Frontend routes
const frontendRoutes = {
    path: '/',
    component: () => import('../Layouts/MainLayout.vue'),
    meta: { requiresAuth: true },
    children: [
        {
            path: '',
            name: 'home',
            component: () => import('../views/Home.vue')
        },
        {
            path: 'exams',
            name: 'exams.index',
            component: () => import('../views/exams/Index.vue')
        },
        {
            path: 'exams/:id',
            name: 'exams.show',
            component: () => import('../views/exams/Show.vue'),
            props: true
        },
        {
            path: 'exams/:id/attempt/:attemptId?',
            name: 'exams.attempt',
            component: () => import('../views/exams/AttemptExam.vue'),
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: 'my-learning',
            name: 'my-learning',
            component: () => import('../views/my-learning/Index.vue')
        },
        {
            path: 'certificates',
            name: 'certificates',
            component: () => import('../views/certificates/Index.vue')
        },
        {
            path: 'certificates/:id',
            name: 'certificates.show',
            component: () => import('../views/certificates/Show.vue'),
            props: true
        },
        {
            path: 'profile',
            name: 'profile',
            component: () => import('../views/Profile.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: 'settings',
            name: 'settings',
            component: () => import('../views/Settings.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: 'resources',
            name: 'resources',
            component: () => import('../views/resources/Index.vue'),
            meta: { requiresAuth: true }
        }
    ]
}

// Admin routes
const adminRoutes = {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: false },
    children: [
        {
            path: '',
            name: 'admin.dashboard',
            component: () => import('../views/admin/Dashboard.vue')
        },
        {
            path: 'users',
            name: 'admin.users.index',
            component: () => import('../views/admin/users/Index.vue')
        },
        {
            path: 'users/:id',
            name: 'admin.users.show',
            component: () => import('../views/admin/users/Show.vue'),
            props: true
        },
        {
            path: 'exams',
            name: 'admin.exams.index',
            component: () => import('../views/admin/exams/Index.vue')
        },
        {
            path: 'exams/create',
            name: 'admin.exams.create',
            component: () => import('../views/admin/exams/ExamForm.vue')
        },
        {
            path: 'exams/:id/edit',
            name: 'admin.exams.edit',
            component: () => import('../views/admin/exams/ExamForm.vue'),
            props: route => ({
                id: parseInt(route.params.id),
                isEditing: true
            }),
            beforeEnter: (to, from, next) => {
                const id = parseInt(to.params.id)
                if (isNaN(id)) {
                    next({ name: 'admin.exams.index' })
                } else {
                    next()
                }
            }
        },
        {
            path: 'questions',
            name: 'admin.questions.index',
            component: () => import('../views/admin/questions/Index.vue')
        },
        {
            path: 'questions/create',
            name: 'admin.questions.create',
            component: () => import('../views/admin/questions/QuestionForm.vue')
        },
        {
            path: 'questions/:id/edit',
            name: 'admin.questions.edit',
            component: () => import('../views/admin/questions/QuestionForm.vue'),
            props: route => ({
                id: parseInt(route.params.id),
                isEditing: true
            })
        },
        {
            path: 'certificates',
            name: 'admin.certificates.index',
            component: () => import('../views/admin/certificates/Index.vue')
        },
        {
            path: 'settings',
            name: 'admin.settings',
            component: () => import('../views/admin/Settings.vue')
        },
        {
            path: 'exams/:examId/questions',
            name: 'admin.exams.questions',
            component: () => import('../views/admin/exams/Questions.vue'),
            props: true
        },
        {
            path: 'activity-logs',
            name: 'admin-activity-logs',
            component: ActivityLogManager,
            meta: { requiresAuth: true, requiresAdmin: false }
        },
        // {
        //     path: 'knowledge-base',
        //     name: 'admin-knowledge-base',
        //     component: KnowledgeBaseManager,
        //     meta: { requiresAuth: true, requiresAdmin: false }
        // },
        {
            path: 'analytics',
            name: 'admin.analytics',
            component: () => import('../views/admin/Analytics.vue')
        },
        {
            path: 'knowledge-base',
            name: 'admin.knowledge-base',
            component: () => import('@/views/admin/knowledge-base/Index.vue'),
            meta: { requiresAuth: true, requiresAdmin: false }
        },
        {
            path: 'resources',
            name: 'admin.resources',
            component: () => import('../views/admin/resources/Index.vue'),
            meta: { requiresAuth: true }
        }
    ]
}

// Auth routes
const authRoutes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/auth/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/logout',
        name: 'logout',
        beforeEnter: async (to, from, next) => {
            const authStore = useAuthStore()
            await authStore.logout()
            next({ name: 'login' })
        }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../views/auth/Register.vue'),
        meta: { requiresGuest: true }
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('../views/auth/ForgotPassword.vue'),
        meta: { requiresGuest: true }
    },
    {
        path: '/reset-password/:token',
        name: 'reset-password',
        component: () => import('../views/auth/ResetPassword.vue'),
        meta: { requiresGuest: true }
    }
]

const routes = [
    frontendRoutes,
    adminRoutes,
    ...authRoutes
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login' })
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'home' })
    } else if (to.meta.requiresAdmin && !authStore.user?.is_admin) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router