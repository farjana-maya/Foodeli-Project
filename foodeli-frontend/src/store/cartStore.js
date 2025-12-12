import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    restaurant: null,
    deliveryAddress: '',
    customerPhone: '',
    currentOrder: null
  }),

  getters: {
    itemCount: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
    
    subtotal: (state) => state.items.reduce((total, item) => total + (Number(item.price) * item.quantity), 0),
    
    deliveryFee: (state) => Number(state.restaurant?.delivery_fee || 0),
    
    grandTotal: (state) => {
      const subtotal = state.items.reduce((total, item) => total + (Number(item.price) * item.quantity), 0)
      const deliveryFee = Number(state.restaurant?.delivery_fee || 0)
      return subtotal + deliveryFee
    },

    isEmpty: (state) => state.items.length === 0
  },

  actions: {
    addItem(menuItem, restaurant) {
      // If adding from different restaurant, clear cart
      if (this.restaurant && this.restaurant.id !== restaurant.id) {
        if (confirm('Adding items from different restaurant will clear your current cart. Continue?')) {
          this.clearCart()
        } else {
          return false
        }
      }

      this.restaurant = restaurant
      
      const existingItem = this.items.find(item => item.id === menuItem.id)
      
      if (existingItem) {
        existingItem.quantity += 1
      } else {
        this.items.push({
          ...menuItem,
          quantity: 1
        })
      }
      return true
    },

    removeItem(itemId) {
      const index = this.items.findIndex(item => item.id === itemId)
      if (index > -1) {
        this.items.splice(index, 1)
      }
      
      if (this.items.length === 0) {
        this.restaurant = null
      }
    },

    updateQuantity(itemId, quantity) {
      const item = this.items.find(item => item.id === itemId)
      if (item) {
        if (quantity <= 0) {
          this.removeItem(itemId)
        } else {
          item.quantity = quantity
        }
      }
    },

    clearCart() {
      this.items = []
      this.restaurant = null
      this.deliveryAddress = ''
      this.customerPhone = ''
    },

    setDeliveryInfo(address, phone) {
      this.deliveryAddress = address
      this.customerPhone = phone
    },

    setCurrentOrder(order) {
      this.currentOrder = order
    },

    async fetchCurrentOrder() {
      try {
        const token = localStorage.getItem('token')
        if (!token) return

        const response = await fetch('http://127.0.0.1:8000/api/orders', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        })

        if (response.ok) {
          const data = await response.json()
          const activeOrder = data.orders?.find(order => 
            !['delivered', 'cancelled'].includes(order.status)
          )
          this.currentOrder = activeOrder || null
        }
      } catch (error) {
        console.error('Error fetching current order:', error)
      }
    }
  },

  persist: {
    key: 'foodeli-cart',
    storage: localStorage,
    paths: ['items', 'restaurant', 'deliveryAddress', 'customerPhone']
  }
})