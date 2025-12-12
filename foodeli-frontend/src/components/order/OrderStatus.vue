<template>
  <div v-if="currentOrder" class="fixed bottom-4 right-4 bg-white rounded-xl shadow-lg border p-4 max-w-sm z-50">
    <div class="mb-3">
      <h3 class="font-semibold text-gray-900">Order Status</h3>
    </div>

    <div class="space-y-3">
      <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
          <i class="fas fa-receipt text-blue-600 text-sm"></i>
        </div>
        <div>
          <p class="font-medium text-sm">{{ currentOrder.order_number }}</p>
          <p class="text-xs text-gray-500">{{ currentOrder.restaurant?.name }}</p>
        </div>
      </div>

      <!-- Menu Items -->
      <div class="space-y-2 max-h-32 overflow-y-auto">
        <div v-for="item in currentOrder.items" :key="item.menu_item_id" class="flex items-center space-x-2 bg-gray-50 p-2 rounded">
          <img :src="getMenuItemImage(item)" :alt="item.name" class="w-8 h-8 rounded object-cover">
          <div class="flex-1 min-w-0">
            <p class="text-xs font-medium truncate">{{ item.name || 'Menu Item' }}</p>
            <p class="text-xs text-gray-500">Qty: {{ item.quantity }} × ৳{{ item.price }}</p>
          </div>
        </div>
      </div>

      <!-- Status Animation -->
      <div class="flex items-center space-x-2 p-3 rounded-lg" :class="statusClass">
        <div class="animate-spin" v-if="isProcessing">
          <i class="fas fa-spinner"></i>
        </div>
        <div class="animate-bounce" v-else-if="currentOrder.status === 'preparing'">
          <i class="fas fa-fire"></i>
        </div>
        <div class="animate-pulse" v-else-if="currentOrder.status === 'ready'">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="animate-bounce" v-else-if="currentOrder.status === 'picked_up'">
          <i class="fas fa-motorcycle"></i>
        </div>
        <div v-else>
          <i class="fas fa-clock"></i>
        </div>
        
        <span class="font-medium text-sm">{{ statusText }}</span>
      </div>

      <div class="text-xs text-gray-500">
        Total: ৳{{ currentOrder.total_amount }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentOrder: Object
})

const emit = defineEmits(['close'])

const statusText = computed(() => {
  switch (props.currentOrder?.status) {
    case 'pending': return 'Order received, waiting for confirmation'
    case 'confirmed': return 'Order confirmed by restaurant'
    case 'preparing': return 'Food is being prepared'
    case 'ready': return 'Food is ready for pickup'
    case 'picked_up': return 'Rider is on the way'
    case 'delivered': return 'Order delivered'
    case 'cancelled': return 'Order cancelled'
    default: return 'Processing order...'
  }
})

const statusClass = computed(() => {
  switch (props.currentOrder?.status) {
    case 'preparing': return 'bg-orange-50 text-orange-700'
    case 'ready': return 'bg-green-50 text-green-700'
    case 'picked_up': return 'bg-blue-50 text-blue-700'
    case 'delivered': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-50 text-red-700'
    default: return 'bg-gray-50 text-gray-700'
  }
})

const isProcessing = computed(() => {
  return ['pending', 'confirmed'].includes(props.currentOrder?.status)
})

const getMenuItemImage = (item) => {
  if (item.image && item.image.startsWith('http')) {
    return item.image
  }
  if (item.image) {
    return `http://127.0.0.1:8000/storage/menu_items/${item.image}`
  }
  return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&h=100&fit=crop'
}
</script>