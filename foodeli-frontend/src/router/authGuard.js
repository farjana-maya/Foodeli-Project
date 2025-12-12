import { useAuthStore } from '@/store/authStore'

export const authGuard = (to, from, next) => {
  const authStore = useAuthStore()

  if (authStore.isAuthenticated) {
    // Redirect based on user role
    if (to.path === '/dashboard') {
      if (authStore.isRestaurantOwner) {
        next('/restaurant-owner-dashboard')
      } else if (authStore.isAdmin) {
        next('/admin/dashboard')
      } else {
        next() // Allow customer to access /dashboard
      }
    } else {
      next()
    }
  } else {
    next('/signin')
  }
}

export const guestGuard = (to, from, next) => {
  const authStore = useAuthStore()
  
  if (!authStore.isAuthenticated) {
    next()
  } else {
    next('/')
  }
}