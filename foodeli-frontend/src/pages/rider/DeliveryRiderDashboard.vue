<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center py-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center">
              <i class="fas fa-motorcycle text-white text-xl"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Delivery Dashboard</h1>
              <p class="text-gray-500 text-sm">Manage your deliveries</p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
              <i class="fas fa-utensils text-white text-xl"></i>
            </div>
            <div>
              <h1 class="text-xl font-bold text-gray-900">Foodeli</h1>
              <p class="text-gray-500 text-xs">Food Delivery</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2 px-3 py-2 bg-gray-100 rounded-lg">
              <div class="w-2 h-2 rounded-full" :class="rider?.is_available ? 'bg-green-500' : 'bg-red-500'"></div>
              <span class="text-sm font-medium text-gray-700">{{ rider?.is_available ? 'Available' : 'Offline' }}</span>
            </div>
            <button @click="toggleAvailability" class="px-4 py-2 rounded-lg font-medium" :class="rider?.is_available ? 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100' : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100'">
              {{ rider?.is_available ? 'Go Offline' : 'Go Online' }}
            </button>
            <button @click="signOut" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
              Sign Out
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-motorcycle text-gray-600 text-lg"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500 font-medium">Total Deliveries</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.total_deliveries || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-star text-gray-600 text-lg"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500 font-medium">Rating</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.rating || '0.0' }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-check-circle text-gray-600 text-lg"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500 font-medium">Status</p>
              <p class="text-lg font-bold text-gray-900">{{ stats?.status || 'Pending' }}</p>
            </div>
          </div>
        </div>


        
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <span class="text-gray-600 text-lg font-bold">৳</span>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500 font-medium">Total Earnings</p>
              <p class="text-2xl font-bold text-gray-900">৳{{ stats?.total_earnings || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Orders -->
      <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-clipboard-list text-gray-600"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900">Active Orders</h2>
        </div>
        
        <div v-if="currentOrders.length === 0" class="text-center py-12">
          <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-box-open text-gray-400 text-2xl"></i>
          </div>
          <p class="text-gray-600 text-lg font-medium mb-2">No active orders</p>
          <p class="text-gray-400">Orders will appear here when available</p>
        </div>
        
        <div v-else class="space-y-4">
          <div v-for="order in currentOrders" :key="order.id" class="border rounded-xl p-6 hover:bg-gray-50">
            <div class="flex justify-between items-start mb-4">
              <div class="space-y-2">
                <h3 class="text-lg font-bold text-gray-900">Order #{{ order.order_number }}</h3>
                <p class="text-gray-600 font-medium">{{ order.restaurant?.name }}</p>
                <p class="text-gray-700">Total: <span class="font-bold">৳{{ order.total_amount }}</span></p>
                <p class="text-gray-500 flex items-center"><i class="fas fa-map-marker-alt mr-2"></i>{{ order.delivery_address?.address || order.delivery_address?.[0] || 'No address' }}</p>
              </div>
              <span :class="['px-3 py-1 rounded-lg text-sm font-medium', getStatusClass(order.status)]">
                {{ formatStatus(order.status) }}
              </span>
            </div>
            
            <div class="flex space-x-3 pt-4 border-t">
              <button v-if="order.status === 'ready'" 
                      @click="updateOrderStatus(order.id, 'picked_up')"
                      class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                <i class="fas fa-hand-paper mr-2"></i>Pick Up
              </button>
              
              <button v-if="order.status === 'picked_up'" 
                      @click="updateOrderStatus(order.id, 'delivered')"
                      class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium">
                <i class="fas fa-check-circle mr-2"></i>Delivered
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Information -->
      <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-user text-gray-600"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
        </div>
        <div v-if="rider" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Full Name</p>
            <p class="text-gray-900 font-semibold">{{ rider.full_name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Phone</p>
            <p class="text-gray-900 font-semibold">{{ rider.phone }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Vehicle Type</p>
            <p class="text-gray-900 font-semibold capitalize">{{ rider.vehicle_type }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Vehicle Number</p>
            <p class="text-gray-900 font-semibold">{{ rider.vehicle_number }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const rider = ref(null)
const stats = ref(null)
const currentOrders = ref([])

const loadDashboard = async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await fetch('http://127.0.0.1:8000/api/delivery-rider/dashboard', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      rider.value = data.rider
      stats.value = data.stats
      currentOrders.value = data.current_orders || []
    }
  } catch (error) {
    console.error('Dashboard load error:', error)
  }
}

const updateOrderStatus = async (orderId, status) => {
  try {
    const token = localStorage.getItem('token')
    const response = await fetch(`http://127.0.0.1:8000/api/delivery-rider/orders/${orderId}/status`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status })
    })

    if (response.ok) {
      loadDashboard() // Reload to get updated orders
    }
  } catch (error) {
    console.error('Order update error:', error)
  }
}

const getStatusClass = (status) => {
  const classes = {
    ready: 'bg-blue-50 text-blue-700',
    picked_up: 'bg-orange-50 text-orange-700',
    delivered: 'bg-green-50 text-green-700'
  }
  return classes[status] || 'bg-gray-50 text-gray-700'
}

const formatStatus = (status) => {
  return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const toggleAvailability = () => {
  if (rider.value) {
    rider.value.is_available = !rider.value.is_available
    // TODO: Update availability on server
  }
}

const signOut = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/')
}

onMounted(() => {
  loadDashboard()
  // Poll for new orders every 10 seconds
  setInterval(loadDashboard, 10000)
})
</script>