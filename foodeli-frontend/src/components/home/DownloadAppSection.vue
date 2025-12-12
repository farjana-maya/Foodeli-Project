<template>
  <section class="py-20 gradient-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="text-white">
          <h2 class="text-4xl font-bold mb-6">{{ title }}</h2>
          <p class="text-xl mb-8 text-purple-100">{{ description }}</p>
          
          <div class="flex flex-col sm:flex-row gap-4">
            <button 
              @click="handleAppStoreClick"
              class="flex items-center justify-center space-x-3 bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition"
            >
              <i class="fab fa-apple text-3xl"></i>
              <div class="text-left">
                <p class="text-xs">Download on the</p>
                <p class="text-lg font-semibold">App Store</p>
              </div>
            </button>
            
            <button 
              @click="handlePlayStoreClick"
              class="flex items-center justify-center space-x-3 bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition"
            >
              <i class="fab fa-google-play text-3xl"></i>
              <div class="text-left">
                <p class="text-xs">Get it on</p>
                <p class="text-lg font-semibold">Google Play</p>
              </div>
            </button>
          </div>
          
          <!-- Features List -->
          <div v-if="showFeatures" class="mt-8 space-y-3">
            <div 
              v-for="feature in features" 
              :key="feature"
              class="flex items-center space-x-2"
            >
              <i class="fas fa-check-circle text-green-400"></i>
              <span class="text-purple-100">{{ feature }}</span>
            </div>
          </div>
        </div>
        
        <div class="hidden md:block text-center">
          <div class="relative inline-block">
            <img 
              :src="appImage" 
              alt="Mobile App" 
              class="rounded-3xl shadow-2xl"
            >
            <!-- Optional: QR Code -->
            <div v-if="showQRCode" class="absolute -bottom-4 -right-4 bg-white p-4 rounded-2xl shadow-xl">
              <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-qrcode text-4xl text-gray-600"></i>
              </div>
              <p class="text-xs text-gray-600 mt-2 text-center">Scan to Download</p>
            </div>
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
    default: 'Download Our Mobile App'
  },
  description: {
    type: String,
    default: 'Get exclusive deals and faster ordering with our mobile app. Available on iOS and Android.'
  },
  appImage: {
    type: String,
    default: 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=600&fit=crop'
  },
  showFeatures: {
    type: Boolean,
    default: true
  },
  features: {
    type: Array,
    default: () => [
      'Exclusive app-only deals',
      'Faster checkout process',
      'Real-time order tracking',
      'Save your favorite restaurants'
    ]
  },
  showQRCode: {
    type: Boolean,
    default: false
  },
  appStoreUrl: {
    type: String,
    default: 'https://apps.apple.com'
  },
  playStoreUrl: {
    type: String,
    default: 'https://play.google.com'
  }
})

// Emits
const emit = defineEmits(['app-store-click', 'play-store-click'])

// Methods
const handleAppStoreClick = () => {
  emit('app-store-click')
  window.open(props.appStoreUrl, '_blank')
}

const handlePlayStoreClick = () => {
  emit('play-store-click')
  window.open(props.playStoreUrl, '_blank')
}
</script>

<style scoped>
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>