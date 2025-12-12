<template>
  <router-view />
  
  <!-- Global Floating Cart Button -->
  <FloatingCartButton @open-cart="handleOpenCart" />
  
  <!-- Global Cart Sidebar -->
  <CartSidebar :is-open="showCart" @close="showCart = false" />
  
  <!-- Global Order Status -->
  <OrderStatus v-if="cartStore.currentOrder" :current-order="cartStore.currentOrder" @close="cartStore.setCurrentOrder(null)" />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/store/authStore'
import { useCartStore } from '@/store/cartStore'
import FloatingCartButton from '@/components/cart/FloatingCartButton.vue'
import CartSidebar from '@/components/cart/CartSidebar.vue'
import OrderStatus from '@/components/order/OrderStatus.vue'

const authStore = useAuthStore()
const cartStore = useCartStore()
const showCart = ref(false)

const handleOpenCart = () => {
  console.log('Opening cart sidebar')
  showCart.value = true
}

onMounted(async () => {
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser()
  }
  
  // Listen for cart open events
  window.addEventListener('open-cart', handleOpenCart)
})

onUnmounted(() => {
  window.removeEventListener('open-cart', handleOpenCart)
})
</script>