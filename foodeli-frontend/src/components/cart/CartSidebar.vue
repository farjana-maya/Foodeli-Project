<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden">
    <div class="absolute inset-0 bg-black/50" @click="$emit('close')"></div>
    
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl">
      <div class="flex h-full flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
          <h2 class="text-lg font-semibold">Your Cart</h2>
          <button @click="$emit('close')" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4">
          <div v-if="cartStore.isEmpty" class="text-center py-12">
            <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-500">Your cart is empty</p>
          </div>

          <div v-else class="space-y-4">
            <!-- Restaurant Info -->
            <div v-if="cartStore.restaurant" class="bg-blue-50 p-3 rounded-lg">
              <h3 class="font-semibold text-blue-900">{{ cartStore.restaurant.name }}</h3>
              <p class="text-blue-700 text-sm">{{ cartStore.restaurant.address }}</p>
            </div>

            <!-- Cart Items -->
            <div v-for="item in cartStore.items" :key="item.id" class="bg-gray-50 p-4 rounded-lg">
              <div class="flex justify-between items-start mb-2">
                <h4 class="font-medium">{{ item.name }}</h4>
                <button @click="cartStore.removeItem(item.id)" class="text-red-500 hover:text-red-700">
                  <i class="fas fa-trash text-sm"></i>
                </button>
              </div>
              
              <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                  <button @click="cartStore.updateQuantity(item.id, item.quantity - 1)" 
                          class="w-8 h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                    <i class="fas fa-minus text-xs"></i>
                  </button>
                  <span class="w-8 text-center font-medium">{{ item.quantity }}</span>
                  <button @click="cartStore.updateQuantity(item.id, item.quantity + 1)"
                          class="w-8 h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                    <i class="fas fa-plus text-xs"></i>
                  </button>
                </div>
                <span class="font-semibold">৳{{ (item.price * item.quantity).toFixed(2) }}</span>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t pt-4 space-y-2">
              <div class="flex justify-between">
                <span>Subtotal:</span>
                <span>৳{{ Number(cartStore.subtotal || 0).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span>Delivery Fee:</span>
                <span>৳{{ Number(cartStore.deliveryFee || 0).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between font-bold text-lg border-t pt-2">
                <span>Total:</span>
                <span>৳{{ Number(cartStore.grandTotal || 0).toFixed(2) }}</span>
              </div>
            </div>

            <!-- Delivery Info Form -->
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium mb-1">Delivery Address</label>
                <textarea v-model="deliveryAddress" 
                         class="w-full p-2 border rounded-lg" 
                         rows="2" 
                         placeholder="Enter your delivery address"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Phone Number</label>
                <input v-model="customerPhone" 
                       type="tel" 
                       class="w-full p-2 border rounded-lg" 
                       placeholder="Enter your phone number">
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div v-if="!cartStore.isEmpty" class="p-4 border-t">
          <button @click="placeOrder" 
                  :disabled="!deliveryAddress || !customerPhone || loading"
                  class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
            {{ loading ? 'Placing Order...' : 'Place Order' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useCartStore } from '@/store/cartStore'

const props = defineProps({
  isOpen: Boolean
})

const emit = defineEmits(['close'])

const cartStore = useCartStore()
const deliveryAddress = ref(cartStore.deliveryAddress)
const customerPhone = ref(cartStore.customerPhone)
const loading = ref(false)

watch([deliveryAddress, customerPhone], () => {
  cartStore.setDeliveryInfo(deliveryAddress.value, customerPhone.value)
})

const placeOrder = async () => {
  try {
    loading.value = true
    
    const orderData = {
      restaurant_id: cartStore.restaurant.id,
      items: cartStore.items.map(item => ({
        menu_item_id: item.id,
        quantity: item.quantity,
        price: item.price
      })),
      delivery_address: deliveryAddress.value,
      customer_phone: customerPhone.value,
      subtotal: cartStore.subtotal,
      delivery_fee: cartStore.deliveryFee,
      total_amount: cartStore.grandTotal
    }

    const token = localStorage.getItem('token')
    const response = await fetch('http://127.0.0.1:8000/api/orders', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(orderData)
    })

    if (response.ok) {
      const result = await response.json()
      cartStore.setCurrentOrder(result.order)
      cartStore.clearCart()
      alert('Order placed successfully!')
      emit('close')
    } else {
      alert('Failed to place order')
    }
  } catch (error) {
    console.error('Order error:', error)
    alert('Failed to place order')
  } finally {
    loading.value = false
  }
}
</script>