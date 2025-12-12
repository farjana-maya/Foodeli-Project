<template>
  <div class="min-h-screen gradient-bg flex">
    <!-- Left Side - Enhanced Branding -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
      <div class="absolute inset-0 bg-black/10"></div>
      <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
        <div class="w-24 h-24 glass-icon rounded-3xl flex items-center justify-center mb-8 float-animation">
          <i class="fas fa-utensils text-white text-4xl"></i>
        </div>
        <h1 class="text-6xl font-bold mb-6 text-center text-shimmer">Welcome Back!</h1>
        <p class="text-xl text-center text-white/90 max-w-md leading-relaxed">
          Sign in to your account and continue your culinary journey with exclusive features
        </p>
        <div class="mt-12 grid grid-cols-3 gap-8 text-center">
          <div class="stat-card p-4 rounded-2xl">
            <div class="text-2xl font-bold">500+</div>
            <div class="text-sm text-white/80">Premium Restaurants</div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="text-2xl font-bold">50k+</div>
            <div class="text-sm text-white/80">Food Lovers</div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="text-2xl font-bold">100k+</div>
            <div class="text-sm text-white/80">Happy Orders</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side - Enhanced Form -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="max-w-md w-full glass-card rounded-3xl p-8 mobile-optimized">
        <!-- Mobile Logo -->
        <div class="lg:hidden text-center mb-8">
          <div class="w-20 h-20 glass-icon rounded-2xl flex items-center justify-center mx-auto mb-4 float-animation">
            <i class="fas fa-utensils text-white text-3xl"></i>
          </div>
          <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
          <p class="text-white/80 mt-2">Sign in to your account</p>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block mb-8">
          <h2 class="text-3xl font-bold text-white mb-2">Sign in to your account</h2>
          <p class="text-white/80">Welcome back! Please enter your details</p>
        </div>

        <!-- Enhanced Form -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold text-white mb-3">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
              placeholder="Enter your email"
            >
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-semibold text-white mb-3">Password</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input pr-12"
                placeholder="Enter your password"
              >
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-white transition-colors duration-200"
              >
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                v-model="form.remember"
                type="checkbox"
                class="modern-checkbox focus-visible"
              >
              <label class="ml-3 block text-sm text-white">Remember me</label>
            </div>
            <a href="#" class="text-sm text-white hover:text-blue-200 enhanced-link transition-colors duration-200">
              Forgot password?
            </a>
          </div>

          <!-- Enhanced Submit Button -->
          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full px-6 py-4 gradient-btn text-white font-semibold rounded-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none focus-visible"
          >
            <span v-if="authStore.loading" class="flex items-center justify-center">
              <svg class="spinner -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Signing in...
            </span>
            <span v-else class="flex items-center justify-center">
              <i class="fas fa-sign-in-alt mr-3"></i>
              Sign In
            </span>
          </button>
        </form>

        <!-- Enhanced Divider -->
        <div class="my-8 flex items-center">
          <div class="flex-1 border-t border-white/30"></div>
          <span class="px-4 text-sm text-white/70 bg-transparent">or continue with</span>
          <div class="flex-1 border-t border-white/30"></div>
        </div>

        <!-- Enhanced Social Login -->
        <div class="grid grid-cols-2 gap-4 mb-8">
          <button class="social-btn flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-300">
            <i class="fab fa-google text-red-500 text-lg mr-3"></i>
            <span class="text-sm font-medium text-gray-700">Google</span>
          </button>
          <button class="social-btn flex items-center justify-center px-4 py-3 rounded-xl transition-all duration-300">
            <i class="fab fa-facebook text-blue-600 text-lg mr-3"></i>
            <span class="text-sm font-medium text-gray-700">Facebook</span>
          </button>
        </div>

        <!-- Enhanced Footer -->
        <div class="text-center space-y-4">
          <p class="text-white/80">
            Don't have an account?
            <a href="/signup" class="text-white font-semibold enhanced-link hover:text-blue-200 transition-colors duration-200">
              Sign up here
            </a>
          </p>
          <button
            @click="$router.push('/')"
            class="inline-flex items-center px-6 py-2 text-sm font-medium text-white/80 hover:text-white border border-white/30 hover:border-white/50 rounded-xl transition-all duration-200 hover:bg-white/10"
          >
            <i class="fas fa-home mr-2"></i>
            Back to Home
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()
const showPassword = ref(false)

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const handleSubmit = async () => {
  try {
    const response = await authStore.login(form)
    
    if (response.user.role === 'admin') {
      alert('Welcome Admin! Redirecting to dashboard...')
      router.push('/admin/dashboard')
    } else if (response.user.role === 'restaurant_owner') {
      // Check restaurant application status
      try {
        const statusResponse = await axios.get('http://localhost:8000/api/restaurant-application-status', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        
        const status = statusResponse.data.status
        if (status === 'approved') {
          alert('Welcome back! Redirecting to your restaurant dashboard...')
          router.push('/restaurant-owner-dashboard')
        } else if (status === 'pending') {
          alert('Your restaurant application is still under review. Please wait for admin approval.')
          router.push('/restaurant-waiting')
        } else if (status === 'rejected') {
          alert('Your restaurant application was rejected. Please contact support.')
          router.push('/')
        } else {
          alert('Please complete your restaurant registration.')
          router.push('/restaurant-owner-form')
        }
      } catch (error) {
        alert('Please complete your restaurant registration.')
        router.push('/restaurant-owner-form')
      }
    } else if (response.user.role === 'rider') {
      alert('Welcome Rider! Redirecting to your dashboard...')
      router.push('/delivery-rider/dashboard')
    } else {
      alert('Sign in successful! Welcome back!')
      router.push('/')
    }
  } catch (error) {
    const message = error.response?.data?.message || 'Login failed'
    alert(message)
  }
}
</script>