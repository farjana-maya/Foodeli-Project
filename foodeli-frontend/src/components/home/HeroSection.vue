<template>
  <section id="home" class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-20 lg:py-32">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
      <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div class="text-white space-y-8" data-aos="fade-right">
          <!-- Badge -->
          <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
            <span class="text-sm font-medium">🎉 Over 500+ Restaurants Available</span>
          </div>

          <!-- Main Heading -->
          <h1 class="text-5xl lg:text-7xl font-black leading-tight">
            Order Your
            <span class="block bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
              Favorite Food
            </span>
            in Minutes
          </h1>

          <p class="text-xl lg:text-2xl text-white/90 leading-relaxed">
            Discover the best restaurants, order delicious meals, and enjoy lightning-fast delivery right to your doorstep!
          </p>
          
          <!-- Search Bar with Modern Design -->
          <div class="relative">
            <div class="bg-white rounded-2xl p-2 shadow-2xl flex items-center hover:shadow-3xl transition-all duration-300">
              <div class="flex items-center flex-1 px-4">
                <i class="fas fa-map-marker-alt text-indigo-600 text-xl mr-3"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Enter your delivery address..." 
                  class="flex-1 py-4 outline-none text-gray-700 text-lg placeholder-gray-400"
                  @keyup.enter="handleSearch"
                >
              </div>
              <button 
                @click="handleSearch"
                class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
              >
                <i class="fas fa-search mr-2"></i>
                Search
              </button>
            </div>
          </div>
          
          <!-- Stats with Modern Cards -->
          <div class="grid grid-cols-3 gap-6 pt-8">
            <div 
              v-for="(stat, index) in stats" 
              :key="stat.label"
              class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105"
              :style="{ animationDelay: `${index * 100}ms` }"
            >
              <h3 class="text-4xl font-bold mb-2">{{ stat.value }}</h3>
              <p class="text-white/80 text-sm font-medium">{{ stat.label }}</p>
            </div>
          </div>
        </div>
        
        <!-- Right Image -->
        <div class="relative hidden lg:block" data-aos="fade-left">
          <div class="relative">
            <!-- Main Image -->
            <img 
              src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=700&h=700&fit=crop" 
              alt="Delicious Food" 
              class="rounded-3xl shadow-2xl w-full floating"
            >
            
            <!-- Floating Card 1 - Fast Delivery -->
            <div class="absolute -left-8 top-24 bg-white p-5 rounded-2xl shadow-2xl max-w-xs animate-float">
              <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-motorcycle text-green-600 text-2xl"></i>
                </div>
                <div>
                  <p class="font-bold text-gray-800 text-lg">Fast Delivery</p>
                  <p class="text-sm text-gray-500">Within 30 minutes</p>
                </div>
              </div>
            </div>
            
            <!-- Floating Card 2 - Top Rated -->
            <div class="absolute -right-8 bottom-24 bg-white p-5 rounded-2xl shadow-2xl max-w-xs animate-float-delayed">
              <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                  <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
                <div>
                  <p class="font-bold text-gray-800 text-lg">Top Rated</p>
                  <p class="text-sm text-gray-500">4.8 Rating ⭐⭐⭐⭐⭐</p>
                </div>
              </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-2xl"></div>
            <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-pink-400 rounded-full opacity-20 blur-2xl"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Wave Separator -->
    <div class="absolute bottom-0 left-0 right-0">
      <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
      </svg>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

// Props
const props = defineProps({
  stats: {
    type: Array,
    default: () => [
      { value: '500+', label: 'Restaurants' },
      { value: '50k+', label: 'Happy Users' },
      { value: '100k+', label: 'Orders' }
    ]
  }
})

// Emits
const emit = defineEmits(['search'])

// State
const searchQuery = ref('')

// Methods
const handleSearch = () => {
  if (searchQuery.value.trim()) {
    emit('search', searchQuery.value)
  }
}
</script>

<style scoped>
.floating {
  animation: floating 4s ease-in-out infinite;
}

@keyframes floating {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(2deg); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float 3s ease-in-out infinite;
  animation-delay: 1.5s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

.floating {
  animation: floating 4s ease-in-out infinite;
}

@keyframes floating {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(2deg); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float 3s ease-in-out infinite;
  animation-delay: 1.5s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

.animate-blob {
  animation: blob 7s infinite;
}

@keyframes blob {
  0%, 100% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

[data-aos="fade-right"] {
  animation: fadeRight 1s ease-out;
}

[data-aos="fade-left"] {
  animation: fadeLeft 1s ease-out;
}

@keyframes fadeRight {
  from {
    opacity: 0;
    transform: translateX(-50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeLeft {
  from {
    opacity: 0;
    transform: translateX(50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>