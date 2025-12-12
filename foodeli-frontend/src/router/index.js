import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/public/HomePage.vue'
import SignUpPage from '@/pages/auth/SignUpPage.vue'
import SignInPage from '@/pages/auth/SignInPage.vue'
import RestaurantOwnerForm from '@/pages/auth/RestaurantOwnerForm.vue'
import RestaurantWaitingPage from '@/pages/auth/RestaurantWaitingPage.vue'
import RestaurantOwnerDashboard from '@/pages/owner/RestaurantOwnerDashboard.vue'
import CustomerDashboard from '@/pages/CustomerDashboard.vue'
import AdminDashboard from '@/pages/admin/AdminDashboard.vue'
import { guestGuard, authGuard } from './authGuard'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUpPage,
    beforeEnter: guestGuard
  },
  {
    path: '/signin',
    name: 'SignIn',
    component: SignInPage,
    beforeEnter: guestGuard
  },
  {
    path: '/restaurant-owner-form',
    name: 'RestaurantOwnerForm',
    component: RestaurantOwnerForm,
    beforeEnter: authGuard
  },
  {
    path: '/restaurant-waiting',
    name: 'RestaurantWaiting',
    component: RestaurantWaitingPage,
    beforeEnter: authGuard
  },
  {
    path: '/restaurant-owner-dashboard',
    name: 'RestaurantOwnerDashboard',
    component: RestaurantOwnerDashboard,
    beforeEnter: authGuard
  },
  {
    path: '/dashboard',
    name: 'CustomerDashboard',
    component: CustomerDashboard,
    beforeEnter: authGuard
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: AdminDashboard
  },
  {
    path: '/restaurant/:id/menu',
    name: 'RestaurantMenu',
    component: () => import('@/pages/public/RestaurantMenuPage.vue')
  },
  {
    path: '/delivery-rider/register',
    name: 'DeliveryRiderRegistration',
    component: () => import('@/pages/auth/DeliveryRiderRegistration.vue'),
    beforeEnter: authGuard
  },
  {
    path: '/delivery-rider/waiting',
    name: 'DeliveryRiderWaiting',
    component: () => import('@/pages/auth/DeliveryRiderWaiting.vue'),
    beforeEnter: authGuard
  },
  {
    path: '/delivery-rider/dashboard',
    name: 'DeliveryRiderDashboard',
    component: () => import('@/pages/rider/DeliveryRiderDashboard.vue'),
    beforeEnter: authGuard
  },
  {
    path: '/delivery-rider-dashboard',
    name: 'DeliveryRiderDashboardAlt',
    component: () => import('@/pages/rider/DeliveryRiderDashboard.vue'),
    beforeEnter: authGuard
  },
  {
    path: '/restaurant/analytics',
    name: 'RestaurantAnalytics',
    component: () => import('@/pages/owner/RestaurantAnalytics.vue'),
    beforeEnter: authGuard
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    return { top: 0 }
  }
})



export default router