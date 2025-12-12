<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8 text-center">
      <div class="mb-6">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-clock text-yellow-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Application Submitted</h1>
        <p class="text-gray-600">Your delivery rider application is under review</p>
      </div>

      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <p class="text-yellow-800 text-sm">
          <i class="fas fa-info-circle mr-2"></i>
          Our admin team will review your application within 24-48 hours. You'll receive an email notification once approved.
        </p>
      </div>

      <div class="space-y-3">
        <button @click="checkStatus" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          Check Status
        </button>
        <button @click="goHome" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
          Go to Homepage
        </button>
      </div>

      <div v-if="status" class="mt-6 p-4 rounded-lg" :class="statusClass">
        <p class="font-medium">Status: {{ status.charAt(0).toUpperCase() + status.slice(1) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const status = ref(null)

const statusClass = computed(() => {
  if (status.value === 'approved') return 'bg-green-50 border border-green-200 text-green-800'
  if (status.value === 'rejected') return 'bg-red-50 border border-red-200 text-red-800'
  return 'bg-yellow-50 border border-yellow-200 text-yellow-800'
})

const checkStatus = async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await fetch('http://127.0.0.1:8000/api/delivery-rider/dashboard', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      status.value = data.rider.status
      
      if (data.rider.status === 'approved') {
        setTimeout(() => {
          router.push('/delivery-rider/dashboard')
        }, 2000)
      }
    }
  } catch (error) {
    console.error('Status check error:', error)
  }
}

const goHome = () => {
  router.push('/')
}
</script>