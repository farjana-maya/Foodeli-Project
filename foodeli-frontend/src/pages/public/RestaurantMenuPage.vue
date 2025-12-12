<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <button 
            @click="goBack" 
            class="inline-flex items-center px-4 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-gray-700 hover:text-gray-900 transition-all duration-200 font-medium shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Restaurants
          </button>
          <div class="flex items-center space-x-3">
            <button class="p-2.5 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <button class="p-2.5 text-gray-500 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="animate-pulse">
        <div class="h-64 bg-gray-200 rounded-xl mb-8"></div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="i in 6" :key="i" class="bg-white rounded-xl p-4 shadow-sm">
            <div class="h-48 bg-gray-200 rounded-lg mb-4"></div>
            <div class="h-4 bg-gray-200 rounded mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Restaurant Content -->
    <div v-else-if="restaurant" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Restaurant Hero Section -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="relative h-64 md:h-80">
          <img 
            :src="getCoverImageUrl(restaurant.cover_image)" 
            :alt="restaurant.name"
            class="w-full h-full object-cover"
            @error="$event.target.src = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop'"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
            <div class="flex items-end space-x-4">

              <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ restaurant.name }}</h1>
                <p v-if="restaurant.description" class="text-white/90 mb-4">{{ restaurant.description }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <div class="grid md:grid-cols-3 gap-6">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-blue-600"></i>
              </div>
              <div>
                <div class="text-sm text-gray-500">Location</div>
                <div class="font-medium">{{ restaurant.address }}</div>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-green-600"></i>
              </div>
              <div>
                <div class="text-sm text-gray-500">Hours</div>
                <div class="font-medium">{{ formatTime(restaurant.opening_time) }} - {{ formatTime(restaurant.closing_time) }}</div>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-utensils text-yellow-600"></i>
              </div>
              <div>
                <div class="text-sm text-gray-500">Cuisine</div>
                <div class="font-medium">{{ restaurant.cuisine_type }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Menu Section -->
      <div v-if="menuItems.length > 0">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-2xl font-bold text-gray-900">Our Menu</h2>
          <div class="flex items-center space-x-2">
            <span class="text-gray-500">{{ menuItems.length }} items</span>
          </div>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="item in menuItems" 
            :key="item.id"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100"
          >
            <div class="relative">
              <img 
                v-if="item.image" 
                :src="getImageUrl(item.image)" 
                :alt="item.name"
                class="w-full h-48 object-cover"
              >
              <div v-else class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <i class="fas fa-utensils text-gray-400 text-3xl"></i>
              </div>
              <div v-if="item.category" class="absolute top-3 left-3">
                <span class="bg-white/90 backdrop-blur-sm text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                  {{ item.category }}
                </span>
              </div>
            </div>
            
            <div class="p-5">
              <h3 class="font-semibold text-gray-900 text-lg mb-2">{{ item.name }}</h3>
              <p v-if="item.description" class="text-gray-600 text-sm mb-4 line-clamp-2">{{ item.description }}</p>
              
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-blue-600">৳{{ item.price }}</span>
                <button @click="addToCart(item)" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                  <i class="fas fa-plus text-sm"></i>
                  <span>Add to Cart</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- No Menu Items -->
      <div v-else class="text-center py-16">
        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto">
          <i class="fas fa-utensils text-gray-300 text-5xl mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No Menu Available</h3>
          <p class="text-gray-600">This restaurant hasn't added their menu yet.</p>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="max-w-md mx-auto px-4 py-16 text-center">
      <div class="bg-white rounded-2xl shadow-sm p-12">
        <i class="fas fa-exclamation-triangle text-red-400 text-5xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Restaurant Not Found</h3>
        <p class="text-gray-600 mb-6">The restaurant you're looking for doesn't exist.</p>
        <button @click="goBack" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          Go Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@/store/cartStore'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()

// State
const restaurant = ref(null)
const menuItems = ref([])
const loading = ref(true)

// Methods
const goBack = () => {
  router.push('/')
}

const formatTime = (time) => {
  if (!time) return ''
  const [hours, minutes] = time.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const displayHour = hour % 12 || 12
  return `${displayHour}:${minutes} ${ampm}`
}

const getImageUrl = (image) => {
  if (!image) return ''
  if (image.startsWith('http')) return image
  
  // Handle different storage path formats
  if (image.startsWith('menu_items/')) {
    return `http://127.0.0.1:8000/storage/${image}`
  } else {
    return `http://127.0.0.1:8000/storage/menu_items/${image}`
  }
}

const getCoverImageUrl = (image) => {
  return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop'
}

const getLogoUrl = (logo) => {
  return null
}

const addToCart = (menuItem) => {
  const success = cartStore.addItem(menuItem, restaurant.value)
  if (success) {
    alert('Item added to cart!')
  }
}

const loadRestaurantData = async () => {
  try {
    loading.value = true
    const restaurantId = route.params.id
    
    const response = await fetch(`http://127.0.0.1:8000/api/restaurants/${restaurantId}/menu`)
    const data = await response.json()
    
    if (response.ok) {
      restaurant.value = data.restaurant
      menuItems.value = data.menuItems
    } else {
      console.error('Restaurant not found')
    }
  } catch (error) {
    console.error('Error loading restaurant data:', error)
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadRestaurantData()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>