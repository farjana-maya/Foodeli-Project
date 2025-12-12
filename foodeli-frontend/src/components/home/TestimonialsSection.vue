<template>
  <section id="reviews" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ title }}</h2>
        <p class="text-gray-600 text-lg">{{ subtitle }}</p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div 
          v-for="review in reviews" 
          :key="review.id"
          class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition"
        >
          <!-- User Info -->
          <div class="flex items-center mb-4">
            <img 
              :src="review.avatar" 
              :alt="review.name" 
              class="w-16 h-16 rounded-full mr-4 object-cover"
            >
            <div>
              <h4 class="font-bold text-gray-800">{{ review.name }}</h4>
              <div class="text-yellow-500">
                <i 
                  v-for="star in 5" 
                  :key="star"
                  :class="[
                    'fas fa-star',
                    star <= review.rating ? 'text-yellow-500' : 'text-gray-300'
                  ]"
                ></i>
              </div>
              <p v-if="review.date" class="text-xs text-gray-500 mt-1">{{ review.date }}</p>
            </div>
          </div>
          
          <!-- Review Text -->
          <p class="text-gray-600 leading-relaxed">{{ review.comment }}</p>
          
          <!-- Optional: Order Info -->
          <div v-if="review.orderInfo" class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500">
              <i class="fas fa-utensils text-purple-600"></i> 
              {{ review.orderInfo }}
            </p>
          </div>
        </div>
      </div>
      
      <!-- Show More Button -->
      <div v-if="showMoreButton" class="text-center mt-12">
        <button 
          @click="handleShowMore"
          class="px-8 py-3 border-2 border-purple-600 text-purple-600 rounded-xl font-medium hover:bg-purple-50 transition"
        >
          Read More Reviews
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
// Props
const props = defineProps({
  title: {
    type: String,
    default: 'What Our Customers Say'
  },
  subtitle: {
    type: String,
    default: 'Real reviews from real people'
  },
  reviews: {
    type: Array,
    default: () => [
      {
        id: 1,
        name: 'John Doe',
        avatar: 'https://i.pravatar.cc/100?img=1',
        rating: 5,
        comment: 'Amazing service! Food arrived hot and fresh. The app is super easy to use. Highly recommended!',
        date: '2 days ago',
        orderInfo: 'Ordered from The Gourmet Kitchen'
      },
      {
        id: 2,
        name: 'Sarah Smith',
        avatar: 'https://i.pravatar.cc/100?img=5',
        rating: 5,
        comment: 'Best food delivery app! Wide variety of restaurants and the delivery is always on time.',
        date: '1 week ago',
        orderInfo: 'Ordered from Burger Paradise'
      },
      {
        id: 3,
        name: 'Mike Johnson',
        avatar: 'https://i.pravatar.cc/100?img=8',
        rating: 5,
        comment: 'Love the tracking feature! I can see exactly where my order is. Great customer support too!',
        date: '3 days ago',
        orderInfo: 'Ordered from Sushi Heaven'
      }
    ]
  },
  showMoreButton: {
    type: Boolean,
    default: true
  }
})

// Emits
const emit = defineEmits(['show-more'])

// Methods
const handleShowMore = () => {
  emit('show-more')
}
</script>

<style scoped>
/* Add any additional styles here */
</style>