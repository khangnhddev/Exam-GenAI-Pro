import { ref, computed } from 'vue'
import axios from 'axios'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const roles = ref([])
  const permissions = ref([])

  const isAuthenticated = computed(() => !!token.value)

  // Update setToken to ensure headers are set
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
    user.value = userData;
    // Add roles and permissions from user data
    roles.value = userData?.roles || [];
    permissions.value = userData?.permissions || [];
  }

  const fetchUser = async () => {
    try {
      const response = await axios.get('/auth/user');
      setUser(response.data.data.user);
      return response.data.data;
    } catch (error) {
      setToken(null)
      setUser(null)
      throw error
    }
  }

  // Add initialization on auth store creation
  const initializeAuth = async () => {
    if (token.value) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      try {
        await fetchUser()
      } catch (error) {
        console.error('Failed to initialize auth:', error)
      }
    }
  }

  // Call initialize immediately
  initializeAuth()

  /**
   * login
   * @param {*} credentials 
   * @returns 
   */
  const login = async (credentials) => {
    try {
      const response = await axios.post('/auth/login', credentials)
      const { token, user: userData } = response.data.data;
  
      setToken(token);
      setUser(userData);

      return response.data.data;
    } catch (error) {
      throw error
    }
  }

  /**
   * register
   * @param {*} userData 
   * @returns 
   */
  const register = async (userData) => {
    try {
      const response = await axios.post('/auth/register', userData)
      const { token, user: newUser } = response.data

      setToken(token)
      setUser(newUser)

      return response.data
    } catch (error) {
      throw error
    }
  }

  const logout = async () => {
    try {
      await axios.post('/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      setToken(null)
      setUser(null)
    }
  }

  // Update checkAuth to be more robust
  const checkAuth = async () => {
    try {
      if (!token.value) {
        return false
      }

      if (!user.value) {
        await fetchUser()
      }
      return !!user.value
    } catch (error) {
      console.error('Error checking auth:', error)
      return false
    }
  }

  const hasRole = (role) => {
    return roles.value.includes(role)
  }

  const hasAnyRole = (roleList) => {
    return roles.value.some(role => roleList.includes(role));
  }

  const hasPermission = (permission) => {
    return permissions.value.includes(permission)
  }

  const isAdmin = computed(() => {
    return hasAnyRole(['admin', 'super-admin'])
  })

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
    setToken,
    roles,
    permissions,
    hasRole,
    hasAnyRole,
    hasPermission,
    isAdmin
  }
})