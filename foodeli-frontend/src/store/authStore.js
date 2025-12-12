import { defineStore } from 'pinia'
import { authService } from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => {
    const token = localStorage.getItem('token')
    const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null
    return {
      user,
      token,
      isAuthenticated: !!token && !!user,
      loading: false
    }
  },

  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
    isCustomer: (state) => state.user?.role === 'customer',
    isRestaurantOwner: (state) => state.user?.role === 'restaurant_owner',
    isRider: (state) => state.user?.role === 'rider'
  },

  actions: {
    async register(userData) {
      this.loading = true
      try {
        const response = await authService.register(userData)
        this.setAuth(response.data.user, response.data.token)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async login(credentials) {
      this.loading = true
      try {
        const response = await authService.login(credentials)
        this.setAuth(response.data.user, response.data.token)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await authService.logout()
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.clearAuth()
      }
    },

    async fetchUser() {
      if (!this.token) return
      
      try {
        const response = await authService.getUser()
        this.user = response.data.user
        this.isAuthenticated = true
      } catch (error) {
        // Only clear auth on 401 (unauthorized), not on network errors
        if (error.response?.status === 401) {
          this.clearAuth()
        }
      }
    },

    setAuth(user, token) {
      this.user = user
      this.token = token
      this.isAuthenticated = true
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }
})