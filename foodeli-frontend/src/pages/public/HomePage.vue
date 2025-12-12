<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Modern Header Navigation -->
    <HeaderNavbar 
      :notification-count="notificationCount"
      :cart-count="0"
      @sign-in="handleSignIn"
      @sign-up="handleSignUp"
      @notification-click="handleNotificationClick"
      @cart-click="handleCartClick"
    />

    <!-- Hero Section with Modern Design -->
    <HeroSection 
      :stats="stats"
      @search="handleSearch"
    />

    <!-- Categories Section -->
    <CategoryGrid 
      :categories="categories"
      @category-click="handleCategoryClick"
    />

    <!-- Featured Restaurants -->
    <FeaturedRestaurants 
      :restaurants="restaurants.slice(0, 3)"
      :loading="loadingRestaurants"
      @restaurant-click="handleRestaurantClick"
      @view-all="handleViewAllRestaurants"
    />
    
    <!-- Additional Restaurants (shown after View All is clicked) -->
    <section v-if="showAllRestaurants" class="py-10 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-2xl font-bold mb-6">More Restaurants</h3>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <RestaurantCard
            v-for="restaurant in restaurants.slice(3)"
            :key="'extra-' + restaurant.id"
            :restaurant="restaurant"
            @click="handleRestaurantClick"
          />
        </div>
        <p class="mt-4 text-gray-600">Showing {{ restaurants.slice(3).length }} additional restaurants</p>
      </div>
    </section>

    <!-- How It Works -->
    <HowItWorks />

    <!-- Customer Reviews -->
    <TestimonialsSection 
      :reviews="reviews"
      @show-more="handleShowMoreReviews"
    />

    <!-- Contact Section -->
    <ContactSection 
      @submit="handleContactSubmit"
    />

    <!-- Download App Section -->
    <DownloadAppSection />

    <!-- Footer -->
    <Footer 
      @link-click="handleFooterLinkClick"
    />

    <!-- Scroll to Top Button -->
    <ScrollToTop />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import HeaderNavbar from '@/components/layout/HeaderNavbar.vue'
import Footer from '@/components/layout/Footer.vue'
import HeroSection from '@/components/home/HeroSection.vue'
import CategoryGrid from '@/components/home/CategoryGrid.vue'
import FeaturedRestaurants from '@/components/home/FeaturedRestaurants.vue'
import HowItWorks from '@/components/home/HowItWorks.vue'
import TestimonialsSection from '@/components/home/TestimonialsSection.vue'
import ContactSection from '@/components/home/ContactSection.vue'
import DownloadAppSection from '@/components/home/DownloadAppSection.vue'
import ScrollToTop from '@/components/shared/ScrollToTop.vue'
import RestaurantCard from '@/components/restaurant/RestaurantCard.vue'

// State
const notificationCount = ref(3)
const loadingRestaurants = ref(false)

const stats = ref([
  { value: '500+', label: 'Restaurants' },
  { value: '50k+', label: 'Happy Users' },
  { value: '100k+', label: 'Orders' }
])

const categories = ref([
  { id: 1, name: 'Pizza', icon: 'fas fa-pizza-slice', bgColor: 'bg-orange-100', iconColor: 'text-orange-600' },
  { id: 2, name: 'Burger', icon: 'fas fa-hamburger', bgColor: 'bg-red-100', iconColor: 'text-red-600' },
  { id: 3, name: 'Healthy', icon: 'fas fa-leaf', bgColor: 'bg-green-100', iconColor: 'text-green-600' },
  { id: 4, name: 'Dessert', icon: 'fas fa-ice-cream', bgColor: 'bg-yellow-100', iconColor: 'text-yellow-600' },
  { id: 5, name: 'Asian', icon: 'fas fa-drumstick-bite', bgColor: 'bg-purple-100', iconColor: 'text-purple-600' },
  { id: 6, name: 'Seafood', icon: 'fas fa-fish', bgColor: 'bg-blue-100', iconColor: 'text-blue-600' }
])

const restaurants = ref([])
const showAllRestaurants = ref(false)

const reviews = ref([
  {
    id: 1,
    name: 'John Doe',
    avatar: 'https://i.pravatar.cc/100?img=1',
    rating: 5,
    comment: 'Amazing service! Food arrived hot and fresh. The app is super easy to use. Highly recommended!',
    date: '2 days ago',
    orderInfo: 'Ordered from The Gourmet Kitchen'
  },
  {
    id: 2,
    name: 'Sarah Smith',
    avatar: 'https://i.pravatar.cc/100?img=5',
    rating: 5,
    comment: 'Best food delivery app! Wide variety of restaurants and the delivery is always on time.',
    date: '1 week ago',
    orderInfo: 'Ordered from Burger Paradise'
  },
  {
    id: 3,
    name: 'Mike Johnson',
    avatar: 'https://i.pravatar.cc/100?img=8',
    rating: 5,
    comment: 'Love the tracking feature! I can see exactly where my order is. Great customer support too!',
    date: '3 days ago',
    orderInfo: 'Ordered from Sushi Heaven'
  }
])

// Methods
const handleSignIn = () => {
  console.log('Sign In clicked')
}

const handleSignUp = () => {
  console.log('Sign Up clicked')
}

const handleNotificationClick = () => {
  console.log('Notifications clicked')
}

const handleCartClick = () => {
  // Cart is now handled globally
  console.log('Cart clicked')
}

const handleSearch = (query) => {
  console.log('Search:', query)
}

const handleCategoryClick = (category) => {
  console.log('Category clicked:', category)
}

const handleRestaurantClick = (restaurant) => {
  window.location.href = `/restaurant/${restaurant.id}/menu`
}

const loadRestaurants = async () => {
  try {
    loadingRestaurants.value = true
    const response = await fetch('http://127.0.0.1:8000/api/restaurants')
    const data = await response.json()
    
    if (response.ok) {
      restaurants.value = data.restaurants
    }
  } catch (error) {
    console.error('Error loading restaurants:', error)
  } finally {
    loadingRestaurants.value = false
  }
}

const handleViewAllRestaurants = () => {
  console.log('View All clicked, restaurants:', restaurants.value.length)
  showAllRestaurants.value = true
  console.log('showAllRestaurants:', showAllRestaurants.value)
}

const handleShowMoreReviews = () => {
  console.log('Show more reviews clicked')
}

const handleContactSubmit = (formData) => {
  console.log('Contact form submitted:', formData)
}

const handleFooterLinkClick = (link) => {
  console.log('Footer link clicked:', link)
}

// Lifecycle
onMounted(() => {
  loadRestaurants()
})
</script>

<style scoped>
/* Add any page-specific styles here */
</style>