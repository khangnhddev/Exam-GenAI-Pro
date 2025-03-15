import { computed } from 'vue'
import { useStore } from 'vuex'

export function useRoles() {
    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const roles = computed(() => store.state.auth.roles || [])

    const hasRole = (role) => {
        return user.value?.roles?.includes(role) ?? false
    }

    const hasAnyRole = (roles) => {
        return roles.some(role => hasRole(role))
    }

    const hasAllRoles = (roles) => {
        return roles.every(role => hasRole(role))
    }

    const canViewExams = computed(() => hasAnyRole(['admin', 'instructor', 'student']))
    const canCreateExams = computed(() => hasAnyRole(['admin', 'instructor']))
    const canManageUsers = computed(() => hasAnyRole(['admin', 'super-admin']))
    const isAdmin = computed(() => {
        return roles.value.some(role => ['admin', 'super-admin'].includes(role))
    })

    return {
        hasRole,
        hasAnyRole,
        hasAllRoles,
        canViewExams,
        canCreateExams,
        canManageUsers,
        isAdmin
    }
}