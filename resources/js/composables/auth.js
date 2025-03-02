import { computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

export function useAuth() {
  const authStore = useAuthStore()
  const router = useRouter()

  const user = computed(() => authStore.user)
  const isAuthenticated = computed(() => authStore.isAuthenticated)

  const login = async (credentials) => {
    try {
      await authStore.login(credentials)
      return true
    } catch (error) {
      console.error('Login failed:', error)
      throw error
    }
  }

  const logout = async () => {
    try {
      await authStore.logout()
      router.push('/')
    } catch (error) {
      console.error('Logout failed:', error)
      throw error
    }
  }

  const checkAuth = async () => {
    if (authStore.token && !authStore.user) {
      await authStore.fetchUser()
    }
  }

  async function register(userData) {
    try {
      await authStore.register(userData)
      return true
    } catch (error) {
      console.error('Registration failed:', error)
      throw error
    }
  }

  async function forgotPassword(email) {
    try {
      await axios.post('/api/auth/forgot-password', { email })
      return { message: 'Password reset link has been sent to your email' }
    } catch (error) {
      console.error('Failed to send reset link:', error)
      throw error
    }
  }

  async function resetPassword(data) {
    try {
      await axios.post('/api/auth/reset-password', data)
      return { message: 'Password has been reset successfully' }
    } catch (error) {
      console.error('Failed to reset password:', error)
      throw error
    }
  }

  function updateUser(userData) {
    authStore.setUser(userData)
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    checkAuth,
    register,
    updateUser,
    forgotPassword,
    resetPassword
  }
} 