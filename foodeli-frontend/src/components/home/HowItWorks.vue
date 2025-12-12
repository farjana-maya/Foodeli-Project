<template>
  <section id="about" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ title }}</h2>
        <p class="text-gray-600 text-lg">{{ subtitle }}</p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-12">
        <div 
          v-for="(step, index) in steps" 
          :key="step.id"
          class="text-center"
        >
          <!-- Step Badge -->
          <div class="relative inline-block mb-6">
            <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
              <i :class="['text-white text-3xl', step.icon]"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-gray-800 font-bold text-sm shadow">
              {{ index + 1 }}
            </div>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ step.title }}</h3>
          <p class="text-gray-600">{{ step.description }}</p>
          
          <!-- Extra Info -->
          <div v-if="step.extraInfo" class="mt-4 inline-block px-4 py-2 bg-white rounded-lg shadow-sm">
            <span class="text-blue-600 font-semibold text-sm">{{ step.extraInfo }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Props
const props = defineProps({
  title: {
    type: String,
    default: 'How It Works'
  },
  subtitle: {
    type: String,
    default: 'Get your food in 3 easy steps'
  },
  steps: {
    type: Array,
    default: () => [
      {
        id: 1,
        icon: 'fas fa-search',
        title: 'Choose Your Food',
        description: 'Browse hundreds of restaurants and thousands of dishes',
        extraInfo: '500+ Restaurants'
      },
      {
        id: 2,
        icon: 'fas fa-shopping-bag',
        title: 'Place Your Order',
        description: 'Add items to cart and checkout with secure payment',
        extraInfo: 'Secure Payment'
      },
      {
        id: 3,
        icon: 'fas fa-motorcycle',
        title: 'Fast Delivery',
        description: 'Track your order and get it delivered to your door',
        extraInfo: 'Within 30 mins'
      }
    ]
  }
})
</script>

<style scoped>
/* Add any additional styles here */
</style>
