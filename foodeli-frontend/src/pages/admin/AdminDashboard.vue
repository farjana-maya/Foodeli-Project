<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/20">
    <!-- Sidebar -->
    <div
      :class="['fixed left-0 top-0 h-full bg-white/98 backdrop-blur-xl shadow-lg transition-all duration-500 z-50 flex flex-col border-r border-gray-200/40', sidebarOpen ? 'w-80' : 'w-20']"
    >
      <!-- Logo Section -->
      <div class="p-8 border-b border-gray-200/40">
        <div class="flex items-center space-x-4">
          <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-2xl flex items-center justify-center shadow-lg animate-pulse-slow">
            <UtensilsCrossed class="w-8 h-8 text-white animate-bounce-slow" />
          </div>
          <div v-if="sidebarOpen" class="animate-fade-in">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-700 to-gray-600 bg-clip-text text-transparent">Foodeli</h1>
            <p class="text-sm text-gray-500 font-medium">Admin Portal</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-6 space-y-3 overflow-y-auto scrollbar-thin">
        <button
          v-for="(item, index) in menuItems"
          :key="item.key"
          @click="activeTab = item.key"
          :class="[
            'w-full flex items-center space-x-4 px-6 py-5 rounded-xl transition-all duration-300 relative group transform hover:scale-[1.02]',
            activeTab === item.key
              ? 'bg-gradient-to-r from-blue-400 to-cyan-400 text-white shadow-lg shadow-blue-300/25'
              : 'text-gray-600 hover:bg-gray-50/80 hover:text-gray-800 hover:shadow-md'
          ]"
          :style="{ animationDelay: `${index * 50}ms` }"
          class="animate-slide-in-left"
        >
          <div class="relative">
            <component :is="item.icon" class="w-6 h-6 flex-shrink-0 transition-transform duration-200 group-hover:scale-110" />
            <div v-if="activeTab === item.key" class="absolute -inset-1 bg-white/20 rounded-full blur-sm animate-pulse"></div>
          </div>
          <span v-if="sidebarOpen" class="font-medium text-base">{{ item.name }}</span>
          <div v-if="activeTab === item.key" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full animate-pulse"></div>
        </button>
      </nav>

      <!-- Logout Section -->
      <div class="p-6 border-t border-slate-200/60">
        <button
          @click="handleLogout"
          class="w-full flex items-center space-x-4 px-6 py-4 text-gray-600 hover:bg-red-50/60 hover:text-red-500 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-md group"
        >
          <div class="relative">
            <LogOut class="w-6 h-6 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-12" />
          </div>
          <span v-if="sidebarOpen" class="font-medium text-base">Sign Out</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div :class="['transition-all duration-500', sidebarOpen ? 'ml-80' : 'ml-20']">
      <!-- Header -->
      <header class="bg-white/95 backdrop-blur-xl shadow-md border-b border-gray-200/40 sticky top-0 z-40">
        <div class="px-8 py-6 flex items-center justify-between">
          <div class="flex items-center space-x-6">
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="p-3 hover:bg-gray-100/60 rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-180"
            >
              <MenuIcon class="w-6 h-6 text-gray-600 transition-transform duration-300" />
            </button>
            <div class="animate-fade-in">
              <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-700 via-gray-600 to-gray-500 bg-clip-text text-transparent">
                {{ menuItems.find(item => item.key === activeTab)?.name }}
              </h2>
              <p class="text-sm text-gray-500 font-medium mt-1 animate-pulse-slow">Manage your {{ activeTab }} efficiently</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button class="p-3 hover:bg-gray-100/60 rounded-xl relative transition-all duration-300 transform hover:scale-110 group">
              <Bell class="w-6 h-6 text-gray-600 group-hover:animate-bounce" />
              <span class="absolute top-2 right-2 w-3 h-3 bg-gradient-to-r from-red-500 to-pink-500 rounded-full animate-ping"></span>
              <span class="absolute top-2 right-2 w-3 h-3 bg-gradient-to-r from-red-500 to-pink-500 rounded-full"></span>
            </button>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold shadow-md transform hover:scale-110 transition-all duration-300 animate-pulse-slow">
              A
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="pt-4 pb-8 px-8 animate-fade-in">
        <div v-if="activeTab === 'dashboard'" class="space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard
              title="Total Users"
              :value="stats.totalUsers"
              icon="Users"
              color="indigo"
              :change="stats.userChange"
              class="animate-slide-up"
              :style="{ animationDelay: '0ms' }"
            />
            <StatCard
              title="Restaurants"
              :value="stats.totalRestaurants"
              icon="Store"
              color="emerald"
              :change="stats.restaurantChange"
              class="animate-slide-up"
              :style="{ animationDelay: '100ms' }"
            />
            <StatCard
              title="Total Orders"
              :value="stats.totalOrders"
              icon="ShoppingBag"
              color="amber"
              :change="stats.orderChange"
              class="animate-slide-up"
              :style="{ animationDelay: '200ms' }"
            />
            <StatCard
              title="Revenue"
              :value="stats.revenue"
              icon="DollarSign"
              color="rose"
              :change="stats.revenueChange"
              class="animate-slide-up"
              :style="{ animationDelay: '300ms' }"
            />
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-lg shadow-gray-200/30 p-8 border border-gray-200/40 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1 animate-slide-in-right">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-2xl flex items-center justify-center shadow-md animate-pulse-slow">
                  <ShoppingBag class="w-6 h-6 text-white animate-bounce-slow" />
                </div>
                <h3 class="text-xl font-bold bg-gradient-to-r from-gray-700 to-gray-600 bg-clip-text text-transparent">Recent Orders</h3>
              </div>
              <div class="space-y-4">
                <div
                  v-for="(order, index) in orders"
                  :key="order.id"
                  class="flex items-center justify-between p-5 bg-gradient-to-r from-slate-50 to-blue-50/50 rounded-2xl hover:from-blue-50 hover:to-indigo-50/50 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer border border-slate-200/40 animate-slide-in-right"
                  :style="{ animationDelay: `${index * 100}ms` }"
                >
                  <div class="flex-1">
                    <p class="font-bold text-slate-800 text-sm">{{ order.orderNumber }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ order.customer }} • {{ order.restaurant }}</p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-slate-800 text-lg animate-pulse-slow">৳{{ order.amount }}</p>
                    <span
                      :class="['inline-block px-3 py-1 text-xs font-semibold rounded-full shadow-sm animate-pulse-slow',
                        order.status === 'delivered'
                          ? 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800'
                          : order.status === 'preparing'
                          ? 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800'
                          : 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800']"
                    >
                      {{ order.status }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-200/60 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 animate-slide-in-left">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse-slow">
                  <Store class="w-6 h-6 text-white animate-bounce-slow" />
                </div>
                <h3 class="text-xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Top Restaurants</h3>
              </div>
              <div class="space-y-4">
                <div
                  v-for="(restaurant, index) in restaurants"
                  :key="restaurant.id"
                  class="flex items-center justify-between p-5 bg-gradient-to-r from-slate-50 to-emerald-50/50 rounded-2xl hover:from-emerald-50 hover:to-teal-50/50 transition-all duration-300 transform hover:scale-[1.02] border border-slate-200/40 animate-slide-in-left"
                  :style="{ animationDelay: `${index * 100}ms` }"
                >
                  <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg transform hover:scale-110 transition-transform duration-200 animate-pulse-slow">
                      #{{ index + 1 }}
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 text-sm">{{ restaurant.name }}</p>
                      <p class="text-xs text-slate-600 mt-1">{{ restaurant.cuisine }} • {{ restaurant.orders }} orders</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <Star class="w-5 h-5 text-amber-400 fill-amber-400 animate-spin-slow" />
                    <span class="font-bold text-slate-800 text-lg animate-pulse-slow">{{ restaurant.rating }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Tables -->
        <div v-else class="space-y-8 animate-fade-in">
          <!-- Content Header -->
          <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 p-6 border border-slate-200/60">
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-slate-800">{{ menuItems.find(item => item.key === activeTab)?.name }}</h3>
              <p class="text-slate-600">Manage your {{ activeTab }} efficiently</p>
            </div>


          </div>

          <!-- Restaurant Applications Tab -->
          <div v-if="activeTab === 'applications'" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-slate-200/60">
              <h3 class="text-xl font-bold text-slate-800">Pending Restaurant Applications</h3>
              <p class="text-slate-600">Review and approve restaurant owner applications</p>
            </div>

            <div v-if="pendingApplications.length === 0" class="p-12 text-center">
              <div class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-slate-400 text-2xl"></i>
              </div>
              <h4 class="text-lg font-semibold text-slate-700 mb-2">No Pending Applications</h4>
              <p class="text-slate-500">All restaurant applications have been reviewed.</p>
            </div>

            <div v-else class="divide-y divide-slate-200/60">
              <div v-for="application in pendingApplications" :key="application.id"
                   class="p-6 hover:bg-slate-50/50 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between space-y-4 lg:space-y-0">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-store text-white"></i>
                      </div>
                      <div class="min-w-0">
                        <h4 class="text-lg font-semibold text-slate-800 truncate">{{ application.name }}</h4>
                        <p class="text-slate-600 text-sm">{{ application.cuisine_type }} • {{ application.city }}</p>
                      </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm mb-3">
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Owner:</span>
                        <span class="text-slate-600">{{ application.owner?.name || 'N/A' }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Phone:</span>
                        <span class="text-slate-600">{{ application.phone }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Email:</span>
                        <span class="text-slate-600 truncate">{{ application.email }}</span>
                      </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-lg">
                      <p class="text-slate-600 text-sm line-clamp-2">{{ application.description }}</p>
                    </div>
                  </div>

                  <div class="flex flex-row lg:flex-col space-x-2 lg:space-x-0 lg:space-y-2 lg:ml-6 flex-shrink-0">
                    <button
                      @click="viewApplicationDetails(application)"
                      class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-sm flex items-center justify-center min-w-[100px]"
                    >
                      <i class="fas fa-eye mr-1"></i>
                      <span class="hidden sm:inline">View Details</span>
                      <span class="sm:hidden">View</span>
                    </button>
                    <button
                      @click="approveApplication(application.id)"
                      class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors text-sm flex items-center justify-center min-w-[100px]"
                    >
                      <i class="fas fa-check mr-1"></i>
                      <span class="hidden sm:inline">Approve</span>
                      <span class="sm:hidden">Yes</span>
                    </button>
                    <button
                      @click="rejectApplication(application.id)"
                      class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-sm flex items-center justify-center min-w-[100px]"
                    >
                      <i class="fas fa-times mr-1"></i>
                      <span class="hidden sm:inline">Reject</span>
                      <span class="sm:hidden">No</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Delivery Riders Tab -->
          <div v-else-if="activeTab === 'riders'" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-slate-200/60">
              <h3 class="text-xl font-bold text-slate-800">Delivery Rider Applications</h3>
              <p class="text-slate-600">Manage delivery rider registrations and approvals</p>
            </div>

            <div v-if="loading" class="flex items-center justify-center h-96">
              <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-600 border-t-transparent animate-pulse"></div>
              </div>
            </div>

            <div v-else-if="riders.length === 0" class="p-12 text-center">
              <div class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-motorcycle text-slate-400 text-2xl"></i>
              </div>
              <h4 class="text-lg font-semibold text-slate-700 mb-2">No Rider Applications</h4>
              <p class="text-slate-500">No delivery rider applications found.</p>
            </div>

            <div v-else class="divide-y divide-slate-200/60">
              <div v-for="rider in riders" :key="rider.id"
                   class="p-6 hover:bg-slate-50/50 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-motorcycle text-white"></i>
                      </div>
                      <div class="min-w-0">
                        <h4 class="text-lg font-semibold text-slate-800">{{ rider.name }}</h4>
                        <p class="text-slate-600 text-sm">{{ rider.vehicle_type }} • {{ rider.phone }}</p>
                      </div>
                      <div class="flex-shrink-0">
                        <span :class="[
                          'px-3 py-1 text-xs font-semibold rounded-full',
                          rider.status === 'approved' ? 'bg-green-100 text-green-800' :
                          rider.status === 'rejected' ? 'bg-red-100 text-red-800' :
                          'bg-yellow-100 text-yellow-800'
                        ]">
                          {{ rider.status.charAt(0).toUpperCase() + rider.status.slice(1) }}
                        </span>
                      </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Email:</span>
                        <span class="text-slate-600 truncate">{{ rider.email }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Vehicle Model:</span>
                        <span class="text-slate-600">{{ rider.vehicle_model || 'N/A' }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Vehicle Number:</span>
                        <span class="text-slate-600">{{ rider.vehicle_number || 'N/A' }}</span>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-medium text-slate-700">Applied:</span>
                        <span class="text-slate-600">{{ rider.created_at }}</span>
                      </div>
                    </div>
                  </div>

                  <div v-if="rider.status === 'pending'" class="flex flex-row space-x-2 flex-shrink-0 lg:ml-6">
                    <button
                      @click="approveRider(rider.id)"
                      class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors text-sm flex items-center justify-center min-w-[100px]"
                    >
                      <i class="fas fa-check mr-2"></i>
                      Approve
                    </button>
                    <button
                      @click="rejectRider(rider.id)"
                      class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-sm flex items-center justify-center min-w-[100px]"
                    >
                      <i class="fas fa-times mr-2"></i>
                      Reject
                    </button>
                  </div>
                  <div v-else class="flex-shrink-0 lg:ml-6">
                    <span class="text-sm text-slate-500 italic">
                      {{ rider.status === 'approved' ? 'Approved' : 'Rejected' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Other Tabs -->
          <div v-else class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden animate-fade-in">
            <div v-if="loading" class="flex items-center justify-center h-96">
              <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-600 border-t-transparent animate-pulse"></div>
                <div class="absolute inset-0 rounded-full border-4 border-cyan-400 border-t-transparent animate-spin animation-delay-300 animate-pulse"></div>
              </div>
            </div>
            <DataTable v-else :columns="getColumns()" :data="getCurrentData()" class="animate-fade-in" />
          </div>
        </div>
      </main>
    </div>

    <!-- Modals -->
    <AddEditModal
      v-if="showModal"
      :mode="modalMode"
      :item="selectedItem"
      @close="showModal = false"
      @submit="handleSubmit"
    />

    <!-- Application Details Modal -->
    <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="showDetailsModal = false">
      <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">Restaurant Application Details</h3>
            <button @click="showDetailsModal = false" class="p-2 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-times text-gray-500"></i>
            </button>
          </div>
        </div>
        
        <div v-if="selectedApplication" class="p-6 space-y-6">
          <div class="space-y-3">
            <div><strong>Restaurant Name:</strong> {{ selectedApplication.name }}</div>
            <div><strong>Cuisine Type:</strong> {{ selectedApplication.cuisine_type }}</div>
            <div><strong>City:</strong> {{ selectedApplication.city }}</div>
            <div><strong>Phone:</strong> {{ selectedApplication.phone }}</div>
            <div><strong>Email:</strong> {{ selectedApplication.email }}</div>
            <div><strong>Description:</strong> {{ selectedApplication.description }}</div>
            <div><strong>Address:</strong> {{ selectedApplication.address }}</div>
            <div><strong>Delivery Time:</strong> {{ selectedApplication.delivery_time }} mins</div>
            <div><strong>Delivery Fee:</strong> ৳{{ selectedApplication.delivery_fee }}</div>
            <div><strong>Min Order:</strong> ৳{{ selectedApplication.min_order_amount }}</div>
            <div><strong>Opening Time:</strong> {{ selectedApplication.opening_time }}</div>
            <div><strong>Closing Time:</strong> {{ selectedApplication.closing_time }}</div>
          </div>

          <div class="flex justify-end space-x-4 pt-6 border-t">
            <button
              @click="approveApplication(selectedApplication.id)"
              class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-colors"
            >
              <i class="fas fa-check mr-2"></i>
              Approve
            </button>
            <button
              @click="rejectApplication(selectedApplication.id)"
              class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl transition-colors"
            >
              <i class="fas fa-times mr-2"></i>
              Reject
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useAuthStore } from '@/store/authStore'
import { adminService } from '@/services/adminService'
import { useRouter } from 'vue-router'
import {
  LayoutDashboard, Store, UtensilsCrossed, Tag, ShoppingBag,
  Users, Bike, Ticket, Star, Settings, LogOut, Plus, Edit2,
  Trash2, Search, Filter, TrendingUp, DollarSign, Menu as MenuIcon, Bell,
  ClipboardList
} from 'lucide-vue-next'

import DataTable from '../../components/DataTable.vue'
import StatCard from './components/StatCard.vue'
import AddEditModal from './components/AddEditModal.vue'

const router = useRouter()
const authStore = useAuthStore()

// Sidebar state
const sidebarOpen = ref(true)
const activeTab = ref('dashboard')

// Search
const searchTerm = ref('')
const showModal = ref(false)
const modalMode = ref('add')
const selectedItem = ref(null)
const showDetailsModal = ref(false)
const selectedApplication = ref(null)

// Loading & Data
const loading = ref(false)
const stats = ref({
  totalUsers: 0,
  totalRestaurants: 0,
  totalOrders: 0,
  revenue: 0
})

const menuItems = [
  { key: 'dashboard', name: 'Dashboard', icon: LayoutDashboard },
  { key: 'restaurants', name: 'Restaurants', icon: Store },
  { key: 'applications', name: 'Applications', icon: ClipboardList },


  { key: 'orders', name: 'Orders', icon: ShoppingBag },
  { key: 'users', name: 'Users', icon: Users },
  { key: 'riders', name: 'Riders', icon: Bike },
  { key: 'coupons', name: 'Coupons', icon: Ticket },
  { key: 'reviews', name: 'Reviews', icon: Star },
  { key: 'settings', name: 'Settings', icon: Settings }
]



const pendingApplications = ref([])

// Dynamic data
const restaurants = ref([])
const orders = ref([])
const users = ref([])
const riders = ref([])

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    loading.value = true

    // Fetch stats
    const statsResponse = await adminService.getStats()
    stats.value = statsResponse.data
    
    // Update restaurants and orders from stats response
    if (statsResponse.data.topRestaurants) {
      restaurants.value = statsResponse.data.topRestaurants
    }
    if (statsResponse.data.recentOrders) {
      orders.value = statsResponse.data.recentOrders
    }

    // Fetch data based on active tab
    await fetchCurrentTabData()

    // Fetch pending applications
    await fetchPendingApplications()
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  } finally {
    loading.value = false
  }
}

const fetchPendingApplications = async () => {
  try {
    const response = await adminService.getPendingApplications()
    pendingApplications.value = response.data.applications || []
  } catch (error) {
    console.error('Error fetching pending applications:', error)
    pendingApplications.value = []
  }
}

const viewApplicationDetails = (application) => {
  console.log('Selected application:', application)
  selectedApplication.value = application
  showDetailsModal.value = true
}

const approveApplication = async (id) => {
  if (confirm('Are you sure you want to approve this restaurant application?')) {
    try {
      await adminService.approveApplication(id)
      alert('Restaurant application approved successfully!')
      showDetailsModal.value = false
      await fetchPendingApplications()
    } catch (error) {
      alert('Error approving application: ' + error.response?.data?.message || error.message)
    }
  }
}

const rejectApplication = async (id) => {
  if (confirm('Are you sure you want to reject this restaurant application?')) {
    try {
      await adminService.rejectApplication(id)
      alert('Restaurant application rejected successfully!')
      showDetailsModal.value = false
      await fetchPendingApplications()
    } catch (error) {
      alert('Error rejecting application: ' + error.response?.data?.message || error.message)
    }
  }
}

const approveRider = async (id) => {
  if (confirm('Are you sure you want to approve this delivery rider?')) {
    try {
      const token = localStorage.getItem('token')
      const response = await fetch(`http://127.0.0.1:8000/api/admin/riders/${id}/approve`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      })
      
      if (response.ok) {
        alert('Delivery rider approved successfully!')
        await fetchCurrentTabData()
      } else {
        alert('Error approving rider')
      }
    } catch (error) {
      alert('Error approving rider: ' + error.message)
    }
  }
}

const rejectRider = async (id) => {
  if (confirm('Are you sure you want to reject this delivery rider?')) {
    try {
      const token = localStorage.getItem('token')
      const response = await fetch(`http://127.0.0.1:8000/api/admin/riders/${id}/reject`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      })
      
      if (response.ok) {
        alert('Delivery rider rejected successfully!')
        await fetchCurrentTabData()
      } else {
        alert('Error rejecting rider')
      }
    } catch (error) {
      alert('Error rejecting rider: ' + error.message)
    }
  }
}

const fetchCurrentTabData = async () => {
  try {
    switch (activeTab.value) {
      case 'restaurants':
        const restaurantsResponse = await adminService.getRestaurants()
        restaurants.value = restaurantsResponse.data
        break
      case 'orders':
        const ordersResponse = await adminService.getOrders()
        orders.value = ordersResponse.data
        break
      case 'users':
        const usersResponse = await adminService.getUsers()
        users.value = usersResponse.data
        break
      case 'riders':
        const ridersResponse = await adminService.getRiders()
        riders.value = ridersResponse.data
        break
    }
  } catch (error) {
    console.error('Error fetching tab data:', error)
  }
}

// Handle logout
const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/')
  } catch (error) {
    console.error('Logout error:', error)
  }
}

const handleAdd = () => {
  modalMode.value = 'add'
  selectedItem.value = null
  showModal.value = true
}

const handleSubmit = (formData) => {
  console.log('Submit data:', formData)
  showModal.value = false
  // Refresh data after submit
  fetchCurrentTabData()
}

// Watch for tab changes
const activeTabWatcher = computed(() => activeTab.value)
watch(activeTabWatcher, () => {
  fetchCurrentTabData()
})

onMounted(() => {
  fetchDashboardData()
})

const getColumns = () => {
  switch (activeTab.value) {
    case 'restaurants':
      return [
        { key: 'name', label: 'Name' },
        { key: 'owner', label: 'Owner' },
        { key: 'cuisine', label: 'Cuisine' },
        { key: 'status', label: 'Status' },
        { key: 'rating', label: 'Rating' },
        { key: 'orders', label: 'Orders' }
      ]
    case 'users':
      return [
        { key: 'name', label: 'Name' },
        { key: 'email', label: 'Email' },
        { key: 'role', label: 'Role' },
        { key: 'status', label: 'Status' },
        { key: 'orders', label: 'Orders' },
        { key: 'joined', label: 'Joined' }
      ]
    case 'riders':
      return [
        { key: 'name', label: 'Name' },
        { key: 'phone', label: 'Phone' },
        { key: 'vehicle_type', label: 'Vehicle Type' },
        { key: 'vehicle_model', label: 'Vehicle Model' },
        { key: 'status', label: 'Status' },
        { key: 'actions', label: 'Actions' }
      ]
    case 'orders':
      return [
        { key: 'orderNumber', label: 'Order #' },
        { key: 'customer', label: 'Customer' },
        { key: 'restaurant', label: 'Restaurant' },
        { key: 'amount', label: 'Amount' },
        { key: 'status', label: 'Status' }
      ]
    default:
      return []
  }
}

const getCurrentData = () => {
  switch (activeTab.value) {
    case 'restaurants':
      return restaurants.value
    case 'users':
      return users.value
    case 'orders':
      return orders.value
    case 'riders':
      return riders.value
    default:
      return []
  }
}
</script>

<style scoped>
/* Custom scrollbar for sidebar */
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: rgba(148, 163, 184, 0.3);
  border-radius: 2px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: rgba(148, 163, 184, 0.5);
}

/* Animation delays */
.animation-delay-300 {
  animation-delay: 0.3s;
}

/* Custom animations */
@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slide-in-left {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slide-in-right {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

@keyframes bounce-slow {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-3px);
  }
  60% {
    transform: translateY(-1.5px);
  }
}

@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
}

.animate-slide-in-left {
  animation: slide-in-left 0.5s ease-out forwards;
}

.animate-slide-in-right {
  animation: slide-in-right 0.5s ease-out forwards;
}

.animate-slide-up {
  animation: slide-up 0.5s ease-out forwards;
}

.animate-pulse-slow {
  animation: pulse-slow 2s ease-in-out infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 2s ease-in-out infinite;
}

.animate-spin-slow {
  animation: spin-slow 3s linear infinite;
}

/* Glass morphism effect */
.backdrop-blur-xl {
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
}

/* Enhanced hover effects */
.hover-lift:hover {
  transform: translateY(-2px);
}

/* Gradient text */
.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Custom focus styles */
.focus-ring:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Loading spinner */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Scrollbar for table */
table::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}
table::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.2);
  border-radius: 3px;
}
</style>
