<template>
  <div v-if="isLoggedIn" class="fixed bottom-6 right-6 z-50">
    <!-- Order Status Display -->
    <div v-if="cartStore.currentOrder && cartStore.currentOrder.status !== 'delivered'" class="mb-3 bg-white rounded-lg shadow-lg p-3 max-w-xs">
      <div class="text-sm text-gray-600 mb-1">Order #{{ cartStore.currentOrder.order_number || cartStore.currentOrder.id }}</div>
      <div class="flex items-center space-x-2">
        <span :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusClass(cartStore.currentOrder.status)]">
          {{ formatStatus(cartStore.currentOrder.status) }}
        </span>
      </div>
    </div>
    
    <!-- Cart Button -->
    <div @click="openCart" 
         class="bg-blue-600 hover:bg-blue-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 cursor-pointer">
      <div class="relative">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
          {{ cartStore.itemCount }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'
import { useCartStore } from '@/store/cartStore'
import { useAuthStore } from '@/store/authStore'
import { useRoute } from 'vue-router'

const emit = defineEmits(['open-cart'])

const cartStore = useCartStore()
const authStore = useAuthStore()
const route = useRoute()

const isLoggedIn = computed(() => {
  // Don't show on admin or owner pages
  if (route.path.includes('/admin') || route.path.includes('/restaurant-owner') || route.path.includes('/delivery-rider')) {
    return false
  }
  
  return authStore.user && authStore.user.role === 'customer'
})

const openCart = () => {
  console.log('Cart button clicked!')
  // Direct access to parent component
  const event = new CustomEvent('open-cart')
  window.dispatchEvent(event)
  emit('open-cart')
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    ready: 'bg-green-100 text-green-700',
    picked_up: 'bg-purple-100 text-purple-700',
    delivered: 'bg-emerald-100 text-emerald-700',
    cancelled: 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const formatStatus = (status) => {
  return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
}

let orderPollingInterval = null

onMounted(async () => {
  if (isLoggedIn.value) {
    await cartStore.fetchCurrentOrder()
    orderPollingInterval = setInterval(() => {
      cartStore.fetchCurrentOrder()
    }, 5000)
  }
})

onUnmounted(() => {
  if (orderPollingInterval) {
    clearInterval(orderPollingInterval)
  }
})
</script>