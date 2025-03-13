import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'
import NotFound from '../views/errors/404.vue'
import ServerError from '../views/errors/500.vue'
import { ERROR_TYPES } from '@/utils/errorHandler'
import ErrorPage from '@/views/errors/ErrorPage.vue'
import TestCode from '@/views/TestCode.vue'
import TestPrompt from '@/views/TestPrompt.vue'
import AttemptReview from '@/views/exams/AttemptReview.vue'
// import EmailVerification from '@/views/auth/EmailVerification.vue'
// import verifiedEmail from './middleware/verifiedEmail'

// Import the admin layout
import AdminLayout from '../Layouts/AdminLayout.vue'
import ActivityLogManager from '../Components/admin/ActivityLogManager.vue'
// import KnowledgeBaseManager from '../components/admin/KnowledgeBaseManager.vue'
import GeneralSettings from '@/views/admin/settings/General.vue'

// Frontend routes
const frontendRoutes = {
    path: '/',
    component: () => import('../Layouts/MainLayout.vue'),
    children: [
        {
            path: '',
            name: 'home',
            component: () => import('../views/Home.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: 'exams',
            name: 'exams.index',
            component: () => import('../views/exams/Index.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: 'exams/:id',
            name: 'exams.show',
            component: () => import('../views/exams/Show.vue'),
            props: true,
            meta: { requiresAuth: false }
        },
        {
            path: 'exams/:id/attempt/:attemptId',
            name: 'exams.attempt',
            component: () => import('../views/exams/AttemptExam.vue'),
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: 'exams/attempts/:attemptId/result',
            name: 'exams.result',
            component: () => import('../views/exams/Result.vue'),
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: 'exams/:id/attempts/:attemptId/review',
            name: 'exams.review',
            component: () => import('@/views/exams/Review.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: 'my-learning',
            name: 'my-learning',
            component: () => import('../views/my-learning/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: 'certificates',
            name: 'certificates',
            component: () => import('../views/certificates/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: 'certificates/:id',
            name: 'certificates.show',
            component: () => import('../views/certificates/Show.vue'),
            props: true,
            meta: { requiresAuth: true }
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
            meta: { requiresAuth: false }
        },
        // {
        //     path: '/verify/:id',
        //     name: 'certificates.verify',
        //     component: () => import('@/views/certificates/Verify.vue')
        // }
        // {
        //     path: '/exams',
        //     name: 'exams.index',
        //     component: () => import('@/views/exams/Index.vue'),
        //     meta: { requiresAuth: true }
        // },
        // {
        //     path: '/exams/:id/take',
        //     name: 'exams.take',
        //     component: () => import('@/views/exams/Take.vue'),
        //     meta: { requiresAuth: true }
        // }
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
        // {
        //     path: 'users/:id',
        //     name: 'admin.users.show',
        //     component: () => import('../views/admin/users/Show.vue'),
        //     props: true
        // },
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
            component: () => import('@/views/admin/questions/Index.vue')
        },
        {
            path: 'questions/create',
            name: 'admin.questions.create',
            component: () => import('../views/admin/questions/QuestionForm.vue')
        },
        {
            path: 'questions/:id/edit',
            name: 'admin.questions.edit',
            component: () => import('@/views/admin/questions/QuestionForm.vue'),
            props: true
        },
        {
            path: 'exams/:examId/questions',
            name: 'admin.exams.questions',
            component: () => import('../views/admin/exams/Questions.vue'),
            props: true
        },
        {
            path: 'exams/:examId/questions/create',
            name: 'admin.exams.questions.create',
            component: () => import('../views/admin/questions/QuestionForm.vue'),
            props: true
        },
        {
            path: 'exams/:examId/questions/:id/edit',
            name: 'admin.exams.questions.edit',
            component: () => import('@/views/admin/questions/QuestionForm.vue'),
            props: route => ({
                id: parseInt(route.params.id),
                examId: parseInt(route.params.examId),
                isEditing: true
            }),
            beforeEnter: (to, from, next) => {
                const id = parseInt(to.params.id)
                const examId = parseInt(to.params.examId)
                if (isNaN(id) || isNaN(examId)) {
                    next({ name: 'admin.exams.questions' })
                } else {
                    next()
                }
            }
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
            path: 'activity-logs',
            name: 'admin-activity-logs',
            component: ActivityLogManager,
            meta: { requiresAuth: true, requiresAdmin: false }
        },
        // {
        //     path: 'analytics',
        //     name: 'admin.analytics',
        //     component: () => import('../views/admin/Analytics.vue')
        // },
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
        },
        {
            path: 'departments',
            name: 'admin.departments',
            component: () => import('@/views/admin/departments/Index.vue'),
            meta: { requiresAuth: true, isAdmin: true }
        },
        {
            path: 'settings',
            children: [
                {
                    path: 'general',
                    name: 'admin.settings.general',
                    component: GeneralSettings,
                    meta: {
                        title: 'General Settings',
                        requiresAuth: true,
                        adminOnly: true
                    }
                }
            ]
        }
    ]
}

// Auth routes
const authRoutes = [
    // {
    //     path: '/login',
    //     name: 'login',
    //     component: () => import('../views/auth/Login.vue'),
    //     meta: { guest: true }
    // },
    {
        path: '/logout',
        name: 'logout',
        beforeEnter: async (to, from, next) => {
            const authStore = useAuthStore()
            await authStore.logout()
            next({ name: 'login' })
        }
    },
    // {
    //     path: '/register',
    //     name: 'register',
    //     component: () => import('../views/auth/Register.vue'),
    //     meta: { requiresGuest: true }
    // },
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
    ...authRoutes,
    {
        path: '/error',
        name: 'error',
        component: ErrorPage,
        props: true
    },
    {
        path: '/404',
        name: 'not-found',
        component: NotFound
    },
    {
        path: '/500',
        name: 'server-error',
        component: ServerError
    },
    {
        path: '/test-code',
        name: 'test-code',
        component: TestCode,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/test-prompt',
        name: 'test-prompt',
        component: TestPrompt,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/email/verify/:id/:hash',
        name: 'verification.verify',
        component: () => import('@/views/auth/EmailVerification.vue'),
        props: true,
        meta: {
            title: 'Email Verification',
            requiresAuth: false
        }
    },
    {
        path: '/exams/:slug',
        name: 'exam.show',
        component: () => import('@/views/exam/Show.vue'),
        props: true,
        meta: {
            requiresAuth: true,
            title: 'Take Exam'
        }
    },
    {
        path: '/exams/:examId/attempts/:attemptId/review',
        name: 'exams.attempt.review',
        component: AttemptReview,
        meta: {
            requiresAuth: true
        }
    },
    // {
    //     path: '/email/verify',
    //     name: 'verification.notice',
    //     component: () => import('@/views/auth/EmailVerification.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Verify Email'
    //     }
    // },
    // {
    //     path: '/dashboard',
    //     name: 'dashboard',
    //     component: () => import('@/views/Dashboard.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         requiresVerification: true,
    //         title: 'Dashboard'
    //     }
    // },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/404'
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        }
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth'
            }
        }
        return { 
            top: 0,
            left: 0,
            behavior: 'smooth'
        }
    }
})

// Add middleware to router
// router.beforeEach(verifiedEmail)

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login' })
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'home' })
    } else if (to.meta.requiresAdmin && !authStore.user?.is_admin) {
        next({ name: 'home' })
    } else {
        // Reset scroll position
        if (document.documentElement) {
            document.documentElement.scrollTop = 0
        }
        if (document.body) {
            document.body.scrollTop = 0
        }
        next()
    }
})

const fetchUser = async () => {
    try {
        const response = await axios.get('/api/auth/user')
        authStore.setUser(response.data)
        return response.data
    } catch (error) {
        authStore.setToken(null)
        authStore.setUser(null)
        throw error
    }
}

const checkAuth = async () => {
    try {
        if (!authStore.token) return false
        
        if (!authStore.user) {
            await fetchUser()
        }
        return true
    } catch (error) {
        console.error('Error checking auth:', error)
        return false
    }
}

export default router