<template>
  <footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <!-- Brand Section -->
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
              <i class="fas fa-utensils text-white"></i>
            </div>
            <span class="text-2xl font-bold">{{ brandName }}</span>
          </div>
          <p class="text-gray-400">{{ brandDescription }}</p>
          
          <!-- Social Media Icons -->
          <div class="flex space-x-4 mt-6">
            <a 
              v-for="social in socialLinks" 
              :key="social.name"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition"
            >
              <i :class="social.icon"></i>
            </a>
          </div>
        </div>
        
        <!-- Company Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">{{ sections[0].title }}</h3>
          <ul class="space-y-2">
            <li v-for="link in sections[0].links" :key="link.name">
              <a 
                :href="link.url" 
                @click.prevent="handleLinkClick(link)"
                class="text-gray-400 hover:text-white transition cursor-pointer"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
        
        <!-- Support Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">{{ sections[1].title }}</h3>
          <ul class="space-y-2">
            <li v-for="link in sections[1].links" :key="link.name">
              <a 
                :href="link.url"
                @click.prevent="handleLinkClick(link)"
                class="text-gray-400 hover:text-white transition cursor-pointer"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
        
        <!-- Partner Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">{{ sections[2].title }}</h3>
          <ul class="space-y-2">
            <li v-for="link in sections[2].links" :key="link.name">
              <a 
                :href="link.url"
                @click.prevent="handleLinkClick(link)"
                class="text-gray-400 hover:text-white transition cursor-pointer"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- Copyright -->
      <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
        <p>
          &copy; {{ currentYear }} {{ brandName }}. All rights reserved. 
          Made with <i class="fas fa-heart text-red-500"></i> for food lovers.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'

// Props
const props = defineProps({
  brandName: {
    type: String,
    default: 'Foodeli'
  },
  brandDescription: {
    type: String,
    default: 'Delivering happiness to your doorstep, one meal at a time.'
  },
  socialLinks: {
    type: Array,
    default: () => [
      { name: 'Facebook', icon: 'fab fa-facebook-f', url: 'https://facebook.com' },
      { name: 'Twitter', icon: 'fab fa-twitter', url: 'https://twitter.com' },
      { name: 'Instagram', icon: 'fab fa-instagram', url: 'https://instagram.com' },
      { name: 'LinkedIn', icon: 'fab fa-linkedin-in', url: 'https://linkedin.com' }
    ]
  },
  sections: {
    type: Array,
    default: () => [
      {
        title: 'Company',
        links: [
          { name: 'About Us', url: '#about', action: 'scroll' },
          { name: 'Careers', url: '/careers', action: 'navigate' },
          { name: 'Blog', url: '/blog', action: 'navigate' },
          { name: 'Contact', url: '#contact', action: 'scroll' }
        ]
      },
      {
        title: 'Support',
        links: [
          { name: 'Help Center', url: '/help', action: 'navigate' },
          { name: 'Safety', url: '/safety', action: 'navigate' },
          { name: 'Terms of Service', url: '/terms', action: 'navigate' },
          { name: 'Privacy Policy', url: '/privacy', action: 'navigate' }
        ]
      },
      {
        title: 'Partner With Us',
        links: [
          { name: 'Restaurant Partner', url: '/restaurant-partner', action: 'navigate' },
          { name: 'Become a Rider', url: '/become-rider', action: 'navigate' },
          { name: 'Corporate Orders', url: '/corporate', action: 'navigate' }
        ]
      }
    ]
  }
})

// Emits
const emit = defineEmits(['link-click'])

// Computed
const currentYear = computed(() => new Date().getFullYear())

// Methods
const handleLinkClick = (link) => {
  if (link.action === 'scroll') {
    // Scroll to section
    const sectionId = link.url.replace('#', '')
    const element = document.getElementById(sectionId)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  } else {
    // Emit for navigation
    emit('link-click', link)
  }
}
</script>

<style scoped>
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>