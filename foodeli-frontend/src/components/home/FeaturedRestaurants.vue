<template>
  <section id="restaurants" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ title }}</h2>
        <p class="text-gray-600 text-lg">{{ subtitle }}</p>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="i in 3" 
          :key="i"
          class="bg-white rounded-2xl overflow-hidden shadow-lg animate-pulse"
        >
          <div class="w-full h-48 bg-gray-300"></div>
          <div class="p-6 space-y-3">
            <div class="h-6 bg-gray-300 rounded w-3/4"></div>
            <div class="h-4 bg-gray-300 rounded w-1/2"></div>
            <div class="h-4 bg-gray-300 rounded w-full"></div>
          </div>
        </div>
      </div>
      
      <!-- Restaurant Grid -->
      <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <RestaurantCard
          v-for="restaurant in restaurants"
          :key="restaurant.id"
          :restaurant="restaurant"
          @click="handleRestaurantClick"
        />
      </div>
      
      <!-- Empty State -->
      <div v-if="!loading && restaurants.length === 0" class="text-center py-12">
        <i class="fas fa-store-slash text-gray-300 text-6xl mb-4"></i>
        <p class="text-gray-500 text-lg">No restaurants found</p>
      </div>
      
      <!-- View All Button -->
      <div v-if="showViewAll && restaurants.length > 0" class="text-center mt-12">
        <button 
          @click="handleViewAll"
          class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200"
        >
          View All Restaurants
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
// CORRECTED IMPORT PATH - Using @ alias
import RestaurantCard from '@/components/restaurant/RestaurantCard.vue'

// Props
const props = defineProps({
  title: {
    type: String,
    default: 'Featured Restaurants'
  },
  subtitle: {
    type: String,
    default: 'Popular restaurants near you'
  },
  restaurants: {
    type: Array,
    default: () => [
      {
        id: 1,
        name: 'The Gourmet Kitchen',
        image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop',
        rating: 4.8,
        location: 'Downtown',
        distance: '2.5 km',
        deliveryTime: '25-30 min',
        deliveryFee: 'Free',
        isFeatured: true,
        cuisines: ['Italian', 'Continental', 'Asian']
      },
      {
        id: 2,
        name: 'Burger Paradise',
        image: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500&h=300&fit=crop',
        rating: 4.9,
        location: 'Uptown',
        distance: '1.8 km',
        deliveryTime: '20-25 min',
        deliveryFee: '$2',
        isFeatured: false,
        cuisines: ['Fast Food', 'American', 'Burgers']
      },
      {
        id: 3,
        name: 'Sushi Heaven',
        image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500&h=300&fit=crop',
        rating: 4.7,
        location: 'Midtown',
        distance: '3.2 km',
        deliveryTime: '30-35 min',
        deliveryFee: 'Free',
        isFeatured: false,
        cuisines: ['Japanese', 'Sushi', 'Asian']
      }
    ]
  },
  loading: {
    type: Boolean,
    default: false
  },
  showViewAll: {
    type: Boolean,
    default: true
  }
})

// Emits
const emit = defineEmits(['restaurant-click', 'view-all'])

// Methods
const handleRestaurantClick = (restaurant) => {
  emit('restaurant-click', restaurant)
}

const handleViewAll = () => {
  emit('view-all')
}
</script>

<style scoped>
/* Add any additional styles here */
</style>