<template>
  <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <div 
          class="flex items-center space-x-3 cursor-pointer group" 
          @click="scrollToSection('home')"
        >
          <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-110">
            <i class="fas fa-utensils text-white text-xl"></i>
          </div>
          <div>
            <span class="text-2xl font-bold text-gray-900">
              Foodeli
            </span>
            <p class="text-xs text-gray-500 -mt-1">Food Delivery</p>
          </div>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-1">
          <a 
            v-for="link in navLinks" 
            :key="link.id"
            @click.prevent="scrollToSection(link.id)"
            href="#"
            class="nav-link-modern px-4 py-2 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 rounded-lg hover:bg-blue-50 cursor-pointer relative group"
          >
            {{ link.name }}
            <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-blue-600 group-hover:w-1/2 group-hover:left-1/4 transition-all duration-300"></span>
          </a>
        </div>
        
        <!-- Icons & Auth Buttons -->
        <div class="hidden md:flex items-center space-x-3">
          <!-- Authenticated User Section -->
          <template v-if="authStore.isAuthenticated">
            <!-- Admin Icon Dropdown (only for admins) -->
            <div v-if="authStore.user?.role === 'admin'" class="relative">
              <button 
                @click="showDropdown = !showDropdown"
                class="relative p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 group"
              >
                <i class="fas fa-user-shield text-xl text-blue-600 group-hover:text-blue-700"></i>
              </button>
              
              <!-- Admin Dropdown Menu -->
              <div 
                v-if="showDropdown"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
              >
                <a 
                  @click="goToAdminDashboard"
                  class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition-all duration-200"
                >
                  <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                  Admin Dashboard
                </a>
                <a 
                  @click="goToProfile"
                  class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 cursor-pointer transition-all duration-200"
                >
                  <i class="fas fa-user-circle mr-3 text-gray-600"></i>
                  Profile
                </a>
                <hr class="my-2">
                <a 
                  @click="handleLogout"
                  class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 cursor-pointer transition-all duration-200"
                >
                  <i class="fas fa-sign-out-alt mr-3"></i>
                  Sign Out
                </a>
              </div>
            </div>
            
            <!-- Notification Icon (only for customers) -->
            <button 
              v-if="showNotifications"
              class="relative p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 group"
              @click="handleNotificationClick"
            >
              <i class="fas fa-bell text-xl group-hover:animate-pulse"></i>
              <span v-if="notificationCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse shadow-lg">
                {{ notificationCount }}
              </span>
            </button>
            
            <!-- Cart Icon -->
            <button 
              class="relative p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 group"
              @click="handleCartClick"
            >
              <i class="fas fa-shopping-cart text-xl group-hover:animate-bounce"></i>
              <span v-if="cartCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse shadow-lg">
                {{ cartCount }}
              </span>
            </button>
            
            <!-- Regular User Profile (for non-admin users) -->
            <div v-if="authStore.user?.role !== 'admin'" class="relative">
              <button 
                @click="showDropdown = !showDropdown"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-300"
              >
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                  <i class="fas fa-user text-white"></i>
                </div>
                <div class="text-left">
                  <p class="text-sm font-semibold text-gray-900">{{ authStore.user?.name }}</p>
                  <p class="text-xs text-gray-500">{{ authStore.user?.role }}</p>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
              </button>
              
              <!-- Regular User Dropdown Menu -->
              <div 
                v-if="showDropdown"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
              >
                <a 
                  @click="goToProfile"
                  class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 cursor-pointer"
                >
                  <i class="fas fa-user-circle mr-3 text-gray-600"></i>
                  Profile
                </a>
                <hr class="my-2">
                <a 
                  @click="handleLogout"
                  class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 cursor-pointer"
                >
                  <i class="fas fa-sign-out-alt mr-3"></i>
                  Sign Out
                </a>
              </div>
            </div>
          </template>
          
          <!-- Guest Section -->
          <template v-else>
            <!-- Sign In Button -->
            <button 
              @click="handleSignIn"
              class="px-6 py-2.5 text-blue-600 font-semibold hover:bg-blue-50 rounded-xl transition-all duration-300 border-2 border-transparent hover:border-blue-100"
            >
              Sign In
            </button>
            
            <!-- Sign Up Button -->
            <button 
              @click="handleSignUp"
              class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95"
            >
              <span class="flex items-center space-x-2">
                <span>Sign Up</span>
                <i class="fas fa-arrow-right text-sm"></i>
              </span>
            </button>
          </template>
        </div>
        
        <!-- Mobile Menu Button -->
        <button 
          @click="toggleMobileMenu"
          class="md:hidden p-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-300"
        >
          <i :class="[isMobileMenuOpen ? 'fas fa-times' : 'fas fa-bars', 'text-2xl']"></i>
        </button>
      </div>
    </div>
    
    <!-- Mobile Menu -->
    <Transition name="mobile-menu">
      <div 
        v-if="isMobileMenuOpen"
        class="md:hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm"
        @click="toggleMobileMenu"
      >
        <div 
          class="absolute right-0 top-0 h-full w-80 bg-white shadow-2xl p-6 overflow-y-auto"
          @click.stop
        >
          <!-- Close Button -->
          <button 
            @click="toggleMobileMenu"
            class="absolute top-6 right-6 p-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-300"
          >
            <i class="fas fa-times text-2xl"></i>
          </button>
          
          <!-- Logo -->
          <div class="flex items-center space-x-3 mb-8">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
              <i class="fas fa-utensils text-white text-xl"></i>
            </div>
            <div>
              <span class="text-2xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Foodeli
              </span>
            </div>
          </div>
          
          <!-- Mobile Navigation Links -->
          <div class="space-y-2 mb-8">
            <a 
              v-for="link in navLinks" 
              :key="link.id"
              @click="handleMobileNavClick(link.id)"
              href="#"
              class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-all duration-300 group"
            >
              <i :class="[link.icon, 'text-lg group-hover:text-indigo-600']"></i>
              <span>{{ link.name }}</span>
            </a>
          </div>
          
          <!-- Mobile Action Buttons -->
          <div class="space-y-3">
            <template v-if="authStore.isAuthenticated">
              <!-- User Info -->
              <div class="bg-blue-50 p-4 rounded-xl mb-4">
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">{{ authStore.user?.name }}</p>
                    <p class="text-sm text-gray-600">{{ authStore.user?.role }}</p>
                  </div>
                </div>
              </div>
              
              <!-- Admin Dashboard Button (only for admins) -->
              <template v-if="authStore.user?.role === 'admin'">
                <button 
                  @click="goToAdminDashboard"
                  class="w-full p-4 bg-blue-50 text-blue-600 rounded-xl font-medium hover:bg-blue-100 transition-all duration-300 mb-4"
                >
                  <i class="fas fa-tachometer-alt mr-2"></i>
                  Admin Dashboard
                </button>
              </template>
              
              <!-- Notifications & Cart -->
              <div class="grid grid-cols-2 gap-3 mb-4">
                <button 
                  v-if="showNotifications"
                  @click="handleNotificationClick"
                  class="relative p-4 bg-indigo-50 text-indigo-600 rounded-xl font-medium hover:bg-indigo-100 transition-all duration-300"
                >
                  <i class="fas fa-bell text-xl"></i>
                  <span v-if="notificationCount > 0" class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ notificationCount }}
                  </span>
                </button>
                <button 
                  @click="handleCartClick"
                  class="relative p-4 bg-green-50 text-green-600 rounded-xl font-medium hover:bg-green-100 transition-all duration-300"
                >
                  <i class="fas fa-shopping-cart text-xl"></i>
                  <span v-if="cartCount > 0" class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ cartCount }}
                  </span>
                </button>
              </div>
              
              <button 
                @click="handleLogout"
                class="w-full px-6 py-4 text-red-600 font-semibold border-2 border-red-600 rounded-xl hover:bg-red-50 transition-all duration-300"
              >
                <i class="fas fa-sign-out-alt mr-2"></i>
                Logout
              </button>
            </template>
            
            <template v-else>
              <button 
                @click="handleSignIn"
                class="w-full px-6 py-4 text-indigo-600 font-semibold border-2 border-indigo-600 rounded-xl hover:bg-indigo-50 transition-all duration-300"
              >
                Sign In
              </button>
              <button 
                @click="handleSignUp"
                class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300"
              >
                Sign Up Free
              </button>
            </template>
          </div>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'

// Props
const props = defineProps({
  notificationCount: {
    type: Number,
    default: 3
  },
  cartCount: {
    type: Number,
    default: 5
  }
})

const router = useRouter()
const authStore = useAuthStore()
const showNotifications = computed(() => authStore.isAuthenticated && authStore.isCustomer)

// Emits
const emit = defineEmits([
  'sign-in',
  'sign-up',
  'notification-click',
  'cart-click'
])

// State
const isMobileMenuOpen = ref(false)
const showDropdown = ref(false)

const navLinks = [
  { id: 'home', name: 'Home', icon: 'fas fa-home' },
  { id: 'restaurants', name: 'Restaurants', icon: 'fas fa-utensils' },
  { id: 'about', name: 'About', icon: 'fas fa-info-circle' },
  { id: 'contact', name: 'Contact', icon: 'fas fa-envelope' },
  { id: 'reviews', name: 'Reviews', icon: 'fas fa-star' }
]

// Methods
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const handleMobileNavClick = (sectionId) => {
  scrollToSection(sectionId)
  toggleMobileMenu()
}

const handleSignIn = () => {
  router.push('/signin')
  isMobileMenuOpen.value = false
}

const handleSignUp = () => {
  router.push('/signup')
  isMobileMenuOpen.value = false
}

const handleLogout = async () => {
  showDropdown.value = false
  await authStore.logout()
  router.push('/')
}

const goToAdminDashboard = () => {
  showDropdown.value = false
  router.push('/admin/dashboard')
}

const goToProfile = () => {
  showDropdown.value = false
  router.push('/dashboard')
}

const handleNotificationClick = () => {
  emit('notification-click')
  isMobileMenuOpen.value = false
}

const handleCartClick = () => {
  emit('cart-click')
  isMobileMenuOpen.value = false
}
</script>

<style scoped>
/* Mobile Menu Transitions */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: opacity 0.3s ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
}

.mobile-menu-enter-active > div,
.mobile-menu-leave-active > div {
  transition: transform 0.3s ease;
}

.mobile-menu-enter-from > div,
.mobile-menu-leave-to > div {
  transform: translateX(100%);
}
</style>