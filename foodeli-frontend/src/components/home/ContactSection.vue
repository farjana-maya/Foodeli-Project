<template>
  <section id="contact" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 gap-12">
        <!-- Contact Info -->
        <div>
          <h2 class="text-4xl font-bold text-gray-800 mb-6">Get in Touch</h2>
          <p class="text-gray-600 mb-8">
            Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
          </p>
          
          <div class="space-y-4">
            <div 
              v-for="info in contactInfo" 
              :key="info.id"
              class="flex items-center"
            >
              <div :class="['w-12 h-12 rounded-full flex items-center justify-center mr-4', info.bgColor]">
                <i :class="[info.icon, info.iconColor]"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800">{{ info.title }}</h4>
                <p class="text-gray-600">{{ info.value }}</p>
              </div>
            </div>
          </div>
          
          <!-- Social Media Links -->
          <div v-if="showSocialMedia" class="mt-8">
            <h4 class="font-semibold text-gray-800 mb-4">Follow Us</h4>
            <div class="flex space-x-3">
              <a 
                v-for="social in socialMedia" 
                :key="social.name"
                :href="social.url"
                target="_blank"
                rel="noopener noreferrer"
                :class="['w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center transition', social.hoverColor]"
              >
                <i :class="[social.icon, 'text-gray-700']"></i>
              </a>
            </div>
          </div>
        </div>
        
        <!-- Contact Form -->
        <div class="bg-white p-8 rounded-2xl shadow-lg">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
              <label class="block text-gray-700 font-medium mb-2">Name</label>
              <input 
                v-model="formData.name"
                type="text" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition" 
                placeholder="Your name"
              >
            </div>
            
            <div>
              <label class="block text-gray-700 font-medium mb-2">Email</label>
              <input 
                v-model="formData.email"
                type="email" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition" 
                placeholder="your@email.com"
              >
            </div>
            
            <div>
              <label class="block text-gray-700 font-medium mb-2">Phone (Optional)</label>
              <input 
                v-model="formData.phone"
                type="tel" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition" 
                placeholder="+880 1234-567890"
              >
            </div>
            
            <div>
              <label class="block text-gray-700 font-medium mb-2">Message</label>
              <textarea 
                v-model="formData.message"
                rows="5" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition" 
                placeholder="Your message..."
              ></textarea>
            </div>
            
            <button 
              type="submit"
              :disabled="isSubmitting"
              class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-lg hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubmitting ? 'Sending...' : 'Send Message' }}
            </button>
          </form>
          
          <!-- Success Message -->
          <div v-if="showSuccessMessage" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700 text-sm">
              <i class="fas fa-check-circle"></i> Thank you! Your message has been sent successfully.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'

// Props
const props = defineProps({
  contactInfo: {
    type: Array,
    default: () => [
      {
        id: 1,
        icon: 'fas fa-map-marker-alt',
        title: 'Address',
        value: '123 Food Street, Dhaka, Bangladesh',
        bgColor: 'bg-purple-100',
        iconColor: 'text-purple-600'
      },
      {
        id: 2,
        icon: 'fas fa-phone',
        title: 'Phone',
        value: '+880 1234-567890',
        bgColor: 'bg-purple-100',
        iconColor: 'text-purple-600'
      },
      {
        id: 3,
        icon: 'fas fa-envelope',
        title: 'Email',
        value: 'support@foodeli.com',
        bgColor: 'bg-purple-100',
        iconColor: 'text-purple-600'
      }
    ]
  },
  showSocialMedia: {
    type: Boolean,
    default: true
  },
  socialMedia: {
    type: Array,
    default: () => [
      { name: 'Facebook', icon: 'fab fa-facebook-f', url: '#', hoverColor: 'hover:bg-blue-600' },
      { name: 'Twitter', icon: 'fab fa-twitter', url: '#', hoverColor: 'hover:bg-sky-500' },
      { name: 'Instagram', icon: 'fab fa-instagram', url: '#', hoverColor: 'hover:bg-pink-600' },
      { name: 'LinkedIn', icon: 'fab fa-linkedin-in', url: '#', hoverColor: 'hover:bg-blue-700' }
    ]
  }
})

// Emits
const emit = defineEmits(['submit'])

// State
const formData = reactive({
  name: '',
  email: '',
  phone: '',
  message: ''
})

const isSubmitting = ref(false)
const showSuccessMessage = ref(false)

// Methods
const handleSubmit = async () => {
  isSubmitting.value = true
  showSuccessMessage.value = false
  
  try {
    // Emit the form data to parent
    emit('submit', { ...formData })
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Show success message
    showSuccessMessage.value = true
    
    // Reset form
    Object.keys(formData).forEach(key => formData[key] = '')
    
    // Hide success message after 5 seconds
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 5000)
    
  } catch (error) {
    console.error('Error submitting form:', error)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
/* Add any additional styles here */
</style>