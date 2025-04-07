import { defineStore } from 'pinia'
import authService from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')),
    token: localStorage.getItem('token'), // Add token to state
    isLoading: false,
    error: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token, // Check authentication by token
  },
  actions: {
    async register(userData) {
      this.isLoading = true
      this.error = null
      try {
        const response = await authService.register(userData)

        // Store user and token from response
        this.user = response.data.user
        this.token = response.data.token

        // Save to localStorage
        localStorage.setItem('user', JSON.stringify(response.data.user))
        localStorage.setItem('token', response.data.token)

        return response
      } catch (error) {
        this.error = error.response?.data?.message || error.message
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async login(credentials) {
      this.isLoading = true
      this.error = null
      try {
        const response = await authService.login(credentials)

        // Store user and token from response
        this.user = response.data.user
        this.token = response.data.token

        // Save to localStorage
        localStorage.setItem('user', JSON.stringify(response.data.user))
        localStorage.setItem('token', response.data.token)

        return response
      } catch (error) {
        this.error = error.response?.data?.message || error.message
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async logout() {
      this.user = null
      this.token = null

      const response = await authService.logout()
      if (response.data.success) {
        localStorage.removeItem('user')
        localStorage.removeItem('token')
        if (localStorage.getItem('user') || localStorage.getItem('token')) {
          this.error = 'Failed to clear user session'
          return false
        }
        return true
      } else {
        this.error = response.message || 'Logout failed'
      }
    },
  },
})
