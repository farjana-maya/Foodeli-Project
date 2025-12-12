<template>
  <div class="min-h-screen gradient-bg flex">
    <!-- Left Side - Branding -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
      <div class="absolute inset-0 bg-black/10"></div>
      <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
        <div class="w-24 h-24 glass-icon rounded-3xl flex items-center justify-center mb-8 float-animation">
          <i class="fas fa-store text-white text-4xl"></i>
        </div>
        <h1 class="text-6xl font-bold mb-6 text-center text-shimmer">Register Your Restaurant</h1>
        <p class="text-xl text-center text-white/90 max-w-md leading-relaxed">
          Join our platform and start serving delicious food to hungry customers in your area
        </p>
        <div class="mt-12 grid grid-cols-2 gap-8 text-center">
          <div class="stat-card p-4 rounded-2xl">
            <div class="text-2xl font-bold">Easy Setup</div>
            <div class="text-sm text-white/80">Quick registration process</div>
          </div>
          <div class="stat-card p-4 rounded-2xl">
            <div class="text-2xl font-bold">Admin Review</div>
            <div class="text-sm text-white/80">Verified by our team</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side - Form -->
    <div class="flex-1 flex items-center justify-center p-8">
      <div class="max-w-2xl w-full glass-card rounded-3xl p-8 mobile-optimized">
        <!-- Mobile Logo -->
        <div class="lg:hidden text-center mb-8">
          <div class="w-20 h-20 glass-icon rounded-2xl flex items-center justify-center mx-auto mb-4 float-animation">
            <i class="fas fa-store text-white text-3xl"></i>
          </div>
          <h2 class="text-3xl font-bold text-white mb-2">Restaurant Registration</h2>
          <p class="text-white/80 mt-2">Complete your restaurant details</p>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block mb-8">
          <h2 class="text-3xl font-bold text-white mb-2">Restaurant Registration Form</h2>
          <p class="text-white/80">Please provide detailed information about your restaurant</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Restaurant Basic Info -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-white border-b border-white/20 pb-2">Basic Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Restaurant Name *</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="Enter restaurant name"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Cuisine Type *</label>
                <select
                  v-model="form.cuisine_type"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input bg-white/95"
                >
                  <option value="">Select cuisine type</option>
                  <option value="Italian">🍝 Italian</option>
                  <option value="Chinese">🥢 Chinese</option>
                  <option value="Indian">🍛 Indian</option>
                  <option value="Mexican">🌮 Mexican</option>
                  <option value="Japanese">🍱 Japanese</option>
                  <option value="Thai">🍜 Thai</option>
                  <option value="American">🍔 American</option>
                  <option value="Mediterranean">🫒 Mediterranean</option>
                  <option value="French">🥖 French</option>
                  <option value="Korean">🍲 Korean</option>
                  <option value="Vietnamese">🥘 Vietnamese</option>
                  <option value="Middle Eastern">🥙 Middle Eastern</option>
                  <option value="Other">🍽️ Other</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-white mb-3">Description *</label>
              <textarea
                v-model="form.description"
                required
                rows="4"
                class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                placeholder="Describe your restaurant, specialties, atmosphere..."
              ></textarea>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-white border-b border-white/20 pb-2">Contact Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Phone Number *</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="+1 (555) 123-4567"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Email Address *</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="restaurant@example.com"
                >
              </div>
            </div>
          </div>

          <!-- Location Information -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-white border-b border-white/20 pb-2">Location Details</h3>

            <div>
              <label class="block text-sm font-semibold text-white mb-3">Full Address *</label>
              <textarea
                v-model="form.address"
                required
                rows="3"
                class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                placeholder="Street address, building, floor, etc."
              ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">City *</label>
                <input
                  v-model="form.city"
                  type="text"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="City name"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Postal Code</label>
                <input
                  v-model="form.postal_code"
                  type="text"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="12345"
                >
              </div>
            </div>
          </div>

          <!-- Business Information -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-white border-b border-white/20 pb-2">Business Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Delivery Time (mins) *</label>
                <input
                  v-model="form.delivery_time"
                  type="number"
                  required
                  min="5"
                  max="120"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="30"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Delivery Fee ($)</label>
                <input
                  v-model="form.delivery_fee"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="2.99"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Min Order ($)</label>
                <input
                  v-model="form.min_order_amount"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                  placeholder="15.00"
                >
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Opening Time *</label>
                <input
                  v-model="form.opening_time"
                  type="time"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                >
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Closing Time *</label>
                <input
                  v-model="form.closing_time"
                  type="time"
                  required
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input"
                >
              </div>
            </div>
          </div>

          <!-- Media Upload -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-white border-b border-white/20 pb-2">Restaurant Images</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Restaurant Logo</label>
                <input
                  type="file"
                  @change="handleLogoUpload"
                  accept="image/*"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                >
                <p class="text-xs text-white/60 mt-1">Max 2MB, JPG/PNG/GIF</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-white mb-3">Cover Image</label>
                <input
                  type="file"
                  @change="handleCoverUpload"
                  accept="image/*"
                  class="w-full px-4 py-4 modern-input rounded-xl focus-visible mobile-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                >
                <p class="text-xs text-white/60 mt-1">Max 5MB, JPG/PNG/GIF</p>
              </div>
            </div>
          </div>

          <!-- Terms and Submit -->
          <div class="space-y-6">
            <div class="flex items-start space-x-4 p-4 glass-icon rounded-xl">
              <input
                v-model="form.terms"
                type="checkbox"
                required
                class="modern-checkbox mt-1 focus-visible"
              >
              <label class="text-sm text-white leading-relaxed">
                I agree that all information provided is accurate and I understand that my restaurant application will be reviewed by Foodeli's admin team before approval.
              </label>
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="w-full px-6 py-4 gradient-btn text-white font-semibold rounded-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none focus-visible"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <svg class="spinner -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting Application...
              </span>
              <span v-else class="flex items-center justify-center">
                <i class="fas fa-paper-plane mr-3"></i>
                Submit Restaurant Application
              </span>
            </button>
          </div>
        </form>

        <!-- Footer -->
        <div class="text-center mt-8">
          <button
            @click="$router.push('/signin')"
            class="inline-flex items-center px-6 py-2 text-sm font-medium text-white/80 hover:text-white border border-white/30 hover:border-white/50 rounded-xl transition-all duration-200 hover:bg-white/10"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Sign In
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(false)

const form = reactive({
  owner_id: null,
  name: '',
  description: '',
  cuisine_type: '',
  address: '',
  city: '',
  postal_code: '',
  phone: '',
  email: '',
  delivery_time: 30,
  delivery_fee: 0.00,
  min_order_amount: 0.00,
  opening_time: '09:00',
  closing_time: '22:00',
  logo: null,
  cover_image: null,
  terms: false
})

onMounted(() => {
  // Get current user ID from auth store
  if (authStore.user) {
    form.owner_id = authStore.user.id
    form.email = authStore.user.email || ''
  }
})

const handleLogoUpload = (event) => {
  form.logo = event.target.files[0]
}

const handleCoverUpload = (event) => {
  form.cover_image = event.target.files[0]
}

const handleSubmit = async () => {
  loading.value = true

  try {
    const formData = new FormData()

    // Add all form fields
    Object.keys(form).forEach(key => {
      if (form[key] !== null && form[key] !== '') {
        if (key === 'logo' || key === 'cover_image') {
          if (form[key]) {
            formData.append(key, form[key])
          }
        } else {
          formData.append(key, form[key])
        }
      }
    })

    const response = await axios.post('http://127.0.0.1:8000/api/restaurant-applications', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.data.success) {
      alert('Restaurant application submitted successfully! Please wait for admin approval.')
      router.push('/restaurant-waiting')
    }
  } catch (error) {
    const message = error.response?.data?.message || 'Application submission failed'
    alert(message)
  } finally {
    loading.value = false
  }
}
</script>
