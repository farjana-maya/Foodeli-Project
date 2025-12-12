<template>
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ title }}</h2>
        <p class="text-gray-600 text-lg">{{ subtitle }}</p>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        <div 
          v-for="category in categories" 
          :key="category.id"
          @click="handleCategoryClick(category)"
          class="category-card bg-white p-6 rounded-2xl shadow-md text-center cursor-pointer hover:shadow-lg transition-shadow duration-300"
        >
          <div 
            :class="['w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4', category.bgColor]"
          >
            <i :class="['text-2xl', category.icon, category.iconColor]"></i>
          </div>
          <h3 class="font-semibold text-gray-800">{{ category.name }}</h3>
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
    default: 'Browse by Category'
  },
  subtitle: {
    type: String,
    default: 'What are you craving today?'
  },
  categories: {
    type: Array,
    default: () => [
      { 
        id: 1, 
        name: 'Pizza', 
        icon: 'fas fa-pizza-slice', 
        bgColor: 'bg-orange-100', 
        iconColor: 'text-orange-600' 
      },
      { 
        id: 2, 
        name: 'Burger', 
        icon: 'fas fa-hamburger', 
        bgColor: 'bg-red-100', 
        iconColor: 'text-red-600' 
      },
      { 
        id: 3, 
        name: 'Healthy', 
        icon: 'fas fa-leaf', 
        bgColor: 'bg-green-100', 
        iconColor: 'text-green-600' 
      },
      { 
        id: 4, 
        name: 'Dessert', 
        icon: 'fas fa-ice-cream', 
        bgColor: 'bg-yellow-100', 
        iconColor: 'text-yellow-600' 
      },
      { 
        id: 5, 
        name: 'Asian', 
        icon: 'fas fa-drumstick-bite', 
        bgColor: 'bg-purple-100', 
        iconColor: 'text-purple-600' 
      },
      { 
        id: 6, 
        name: 'Seafood', 
        icon: 'fas fa-fish', 
        bgColor: 'bg-blue-100', 
        iconColor: 'text-blue-600' 
      }
    ]
  }
})

// Emits
const emit = defineEmits(['category-click'])

// Methods
const handleCategoryClick = (category) => {
  emit('category-click', category)
}
</script>

<style scoped>
.category-card {
  transition: all 0.3s ease;
}

.category-card:hover {
  transform: translateY(-5px);
}
</style>