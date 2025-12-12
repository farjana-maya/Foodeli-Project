import api from './api.js'

export const adminService = {
  // Dashboard stats
  getStats() {
    return api.get('/admin/dashboard')
  },

  // Users
  getUsers() {
    return api.get('/admin/users')
  },

  banUser(userId) {
    return api.post(`/admin/users/${userId}/ban`)
  },

  unbanUser(userId) {
    return api.post(`/admin/users/${userId}/unban`)
  },

  // Restaurants
  getRestaurants() {
    return api.get('/admin/restaurants')
  },

  // Restaurant Applications
  getPendingApplications() {
    return api.get('/admin/restaurant-applications')
  },

  approveApplication(id) {
    return api.post(`/admin/restaurant-applications/${id}/approve`)
  },

  rejectApplication(id) {
    return api.post(`/admin/restaurant-applications/${id}/reject`)
  },

  // Orders
  getOrders() {
    return api.get('/admin/orders')
  },

  updateOrderStatus(orderId, status) {
    return api.put(`/admin/orders/${orderId}/status`, { status })
  },

  // Riders
  getRiders() {
    return api.get('/admin/riders')
  }
}
