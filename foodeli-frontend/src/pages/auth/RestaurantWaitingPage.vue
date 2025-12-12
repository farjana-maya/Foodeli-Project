<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
      <div v-if="status === 'pending'" class="space-y-6">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
          <i class="fas fa-clock text-blue-600 text-3xl animate-pulse"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Application Under Review</h1>
        <p class="text-gray-600">
          Your restaurant registration has been submitted successfully. 
          Please wait while our admin team reviews your application.
        </p>
        <div class="flex items-center justify-center space-x-2">
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
      </div>

      <div v-else-if="status === 'approved'" class="space-y-6">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
          <i class="fas fa-check text-green-600 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Application Approved!</h1>
        <p class="text-gray-600">
          Congratulations! Your restaurant has been approved. 
          Redirecting to your dashboard...
        </p>
      </div>

      <div v-else-if="status === 'rejected'" class="space-y-6">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
          <i class="fas fa-times text-red-600 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Application Rejected</h1>
        <p class="text-gray-600">
          Unfortunately, your restaurant application has been rejected. 
          Better luck next time! Redirecting to home page...
        </p>
      </div>

      <button 
        @click="goHome"
        class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        Go to Home
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()
const status = ref('pending')
let pollInterval = null

const checkApplicationStatus = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/restaurant-application-status', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    const newStatus = response.data.status
    if (newStatus !== status.value) {
      status.value = newStatus
      
      if (newStatus === 'approved') {
        setTimeout(() => {
          router.push('/restaurant-owner-dashboard')
        }, 2000)
      } else if (newStatus === 'rejected') {
        setTimeout(() => {
          router.push('/')
        }, 3000)
      }
    }
  } catch (error) {
    console.error('Error checking application status:', error)
  }
}

const goHome = () => {
  router.push('/')
}

onMounted(() => {
  checkApplicationStatus()
  pollInterval = setInterval(checkApplicationStatus, 5000) // Check every 5 seconds
})

onUnmounted(() => {
  if (pollInterval) {
    clearInterval(pollInterval)
  }
})
</script>