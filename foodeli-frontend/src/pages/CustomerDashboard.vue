<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Modern Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-blue-100 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-3 cursor-pointer" @click="goToHomepage">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">
              <i class="fas fa-utensils text-white text-xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Foodeli</h1>
              <p class="text-xs text-gray-500 -mt-1">Food Delivery</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-3 bg-blue-50 px-4 py-2 rounded-full">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
              </div>
              <span class="text-sm font-medium text-gray-700">{{ authStore.user?.name }}</span>
            </div>
            <button
              @click="handleLogout"
              class="px-6 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium rounded-full hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Welcome Section -->
        <div class="mb-8 text-center">
          <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">Welcome Back!</h2>
          <p class="text-gray-600 text-lg">Manage your profile and track your delicious orders</p>
        </div>

        <!-- Profile Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-blue-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user text-white text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Name</p>
                <p class="text-lg font-bold text-gray-900">{{ authStore.user?.name }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-purple-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-envelope text-white text-xl"></i>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-sm font-bold text-gray-900 truncate">{{ authStore.user?.email }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-green-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-bag text-white text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                <p class="text-lg font-bold text-gray-900">{{ orderHistory.length }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-orange-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar text-white text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Member Since</p>
                <p class="text-lg font-bold text-gray-900">{{ formatDate(authStore.user?.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Order History -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 mb-8">
          <div class="px-6 py-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-history text-white"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900">Order History</h3>
                <p class="text-sm text-gray-500">Track all your delicious orders</p>
              </div>
            </div>
          </div>
          
          <div v-if="orderHistory.length === 0" class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-shopping-bag text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-500 text-lg font-medium">No orders yet</p>
            <p class="text-gray-400 text-sm">Start ordering your favorite food!</p>
          </div>
          
          <div v-else class="p-6">
            <div class="space-y-4">
              <div v-for="order in orderHistory" :key="order.id" class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                      <i class="fas fa-utensils text-white"></i>
                    </div>
                    <div>
                      <div class="text-lg font-bold text-gray-900">Order #{{ order.order_number || order.id }}</div>
                      <div class="text-sm text-gray-600">{{ order.items?.length || 0 }} items • ৳{{ order.total_amount }}</div>
                      <div class="text-xs text-gray-400 flex items-center mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ formatDate(order.created_at) }}
                      </div>
                    </div>
                  </div>
                  <div class="text-right">
                    <span :class="[
                      'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm',
                      getStatusClass(order.status)
                    ]">
                      {{ formatStatus(order.status) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-blue-100 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer" @click="goToHomepage">
            <div class="flex items-center space-x-4 mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-search text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Browse Restaurants</h3>
                <p class="text-gray-500 text-sm">Find your favorite food</p>
              </div>
            </div>
            <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg">
              Browse Now
            </button>
          </div>

          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-green-100 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center space-x-4 mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-heart text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Favorites</h3>
                <p class="text-gray-500 text-sm">Your liked restaurants</p>
              </div>
            </div>
            <button class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-3 rounded-xl font-medium hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg">
              View Favorites
            </button>
          </div>

          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-purple-100 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center space-x-4 mb-4">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-cog text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Settings</h3>
                <p class="text-gray-500 text-sm">Update your profile</p>
              </div>
            </div>
            <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-3 rounded-xl font-medium hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-lg">
              Edit Profile
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/store/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const orderHistory = ref([])

const loadOrderHistory = async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await fetch('http://127.0.0.1:8000/api/orders', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      orderHistory.value = data.orders || []
    }
  } catch (error) {
    console.error('Error loading order history:', error)
  }
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-blue-100 text-blue-800',
    preparing: 'bg-orange-100 text-orange-800',
    ready: 'bg-green-100 text-green-800',
    picked_up: 'bg-purple-100 text-purple-800',
    delivered: 'bg-emerald-100 text-emerald-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatStatus = (status) => {
  return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString()
}

const goToHomepage = () => {
  router.push('/')
}

const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/signin')
  } catch (error) {
    console.error('Logout error:', error)
  }
}

onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/signin')
  } else {
    loadOrderHistory()
  }
})
</script>
