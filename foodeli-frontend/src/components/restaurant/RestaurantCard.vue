<template>
  <div 
    @click="handleClick"
    class="food-card bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer hover:shadow-xl transition-shadow duration-300"
  >
    <div class="relative">
      <img 
        :src="restaurant.image" 
        :alt="restaurant.name" 
        class="w-full h-48 object-cover"
        @error="$event.target.src = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop'"
      >
      <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full shadow-md">
        <span class="text-yellow-500 font-semibold">
          <i class="fas fa-star"></i> {{ restaurant.rating }}
        </span>
      </div>
      

      
      <!-- Featured Badge -->
      <div 
        v-if="restaurant.isFeatured" 
        class="absolute top-4 right-16 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold"
      >
        Featured
      </div>
    </div>
    
    <div class="p-6">
      <h3 class="text-xl font-bold text-gray-800 mb-2">{{ restaurant.name }}</h3>
      <p class="text-gray-600 mb-2">
        <i class="fas fa-map-marker-alt text-blue-600"></i> 
        {{ restaurant.location || restaurant.address }}
      </p>
      <p class="text-gray-500 mb-4 text-sm">
        <i class="fas fa-clock text-green-600"></i> 
        {{ restaurant.openingHours || formatTime(restaurant.opening_time) + ' - ' + formatTime(restaurant.closing_time) }}
      </p>
      
      <!-- Cuisine Tags -->
      <div v-if="restaurant.cuisines" class="mt-4 flex flex-wrap gap-2">
        <span 
          v-for="cuisine in restaurant.cuisines.slice(0, 3)" 
          :key="cuisine"
          class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full"
        >
          {{ cuisine }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
// Props
const props = defineProps({
  restaurant: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.name && value.image && value.rating
    }
  }
})

// Emits
const emit = defineEmits(['click'])

// Methods
const formatTime = (time) => {
  if (!time) return ''
  const [hours, minutes] = time.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const displayHour = hour % 12 || 12
  return `${displayHour}:${minutes} ${ampm}`
}

const getLogoUrl = (logo) => {
  if (!logo) return null
  if (logo.startsWith('http')) return logo
  return `http://127.0.0.1:8000/storage/uploads/restaurants/logos/${logo}`
}

const handleClick = () => {
  console.log('Restaurant data:', props.restaurant)
  emit('click', props.restaurant)
}
</script>

<style scoped>
.food-card {
  transition: all 0.3s ease;
}

.food-card:hover {
  transform: translateY(-5px);
}
</style>