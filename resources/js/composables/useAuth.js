import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const user = ref(null)
const loading = ref(false)
const error = ref(null)

export function useAuth() {
  const router = useRouter()

  async function login(credentials) {
    try {
      loading.value = true
      error.value = null
      const response = await axios.post('/auth/login', credentials)
      user.value = response.data.user
      router.push({ name: 'home' })
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  async function register(userData) {
    try {
      loading.value = true
      error.value = null
      const response = await axios.post('/api/v1/auth/register', userData)
      user.value = response.data.user
      router.push({ name: 'home' })
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await axios.post('/auth/logout')
      user.value = null
      router.push({ name: 'login' })
    } catch (error) {
      console.error('Error during logout:', error)
    }
  }

  async function forgotPassword(email) {
    try {
      loading.value = true
      error.value = null
      await axios.post('/api/v1/auth/forgot-password', { email })
      return { message: 'Password reset link has been sent to your email' }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to send reset link'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  async function resetPassword(data) {
    try {
      loading.value = true
      error.value = null
      await axios.post('/api/v1/auth/reset-password', data)
      return { message: 'Password has been reset successfully' }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to reset password'
      throw error.value
    } finally {
      loading.value = false
    }
  }

  function updateUser(userData) {
    user.value = userData
  }

  return {
    user,
    loading,
    error,
    login,
    register,
    logout,
    updateUser,
    forgotPassword,
    resetPassword
  }
}