import { ref, computed } from 'vue'
import axios from 'axios'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))

  const isAuthenticated = computed(() => !!token.value)

  const setToken = (newToken) => {
    token.value = newToken
    if (newToken) {
      localStorage.setItem('token', newToken)
      axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
    } else {
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    }
  }

  const setUser = (userData) => {
    user.value = userData
  }

  const fetchUser = async () => {
    try {
      const response = await axios.get('/api/auth/user')
      setUser(response.data)
      return response.data
    } catch (error) {
      setToken(null)
      setUser(null)
      throw error
    }
  }

  const login = async (credentials) => {
    try {
      const response = await axios.post('/auth/login', credentials)
      const { token, user: userData } = response.data

      setToken(token)
      setUser(userData)

      return response.data
    } catch (error) {
      throw error
    }
  }

  const register = async (userData) => {
    try {
      const response = await axios.post('/api/auth/register', userData)
      const { access_token, user: newUser } = response.data

      setToken(access_token)
      setUser(newUser)

      return response.data
    } catch (error) {
      throw error
    }
  }

  const logout = async () => {
    try {
      await axios.post('/api/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      setToken(null)
      setUser(null)
    }
  }

  const checkAuth = async () => {
    try {
      if (!token.value) return false
      
      if (!user.value) {
        await fetchUser()
      }
      return true
    } catch (error) {
      console.error('Error checking auth:', error)
      return false
    }
  }

  // Initialize axios header if token exists
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    checkAuth,
    fetchUser,
    setUser,
    setToken
  }
})