import { defineStore } from 'pinia'
import axios from 'axios'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token')
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.is_admin === true
  },

  actions: {
    setAuth(data) {
      this.user = data.user
      this.token = data.token
      localStorage.setItem('user', JSON.stringify(data.user))
      localStorage.setItem('token', data.token)
    },

    clearAuth() {
      this.user = null
      this.token = null
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },

    async fetchUser() {
      try {
        const { data } = await axios.get('/api/v1/auth/me')
        this.user = data
        return data
      } catch (error) {
        console.error('Error fetching user:', error)
        this.user = null
        return null
      }
    },

    async logout() {
      try {
        await axios.post('/api/v1/auth/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('token')
        router.push({ name: 'login' })
      }
    }
  }
})