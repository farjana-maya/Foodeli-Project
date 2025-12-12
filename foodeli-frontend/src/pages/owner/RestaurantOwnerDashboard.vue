<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div :class="['fixed left-0 top-0 h-full bg-white shadow-lg border-r border-gray-200 transition-all duration-500 z-50', sidebarOpen ? 'w-80' : 'w-20']">
      <!-- Logo -->
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
            <i class="fas fa-store text-white text-xl"></i>
          </div>
          <div v-if="sidebarOpen" class="text-gray-900">
            <h1 class="text-xl font-bold">{{ restaurant?.name || 'Restaurant' }}</h1>
            <p class="text-gray-600 text-sm">{{ restaurant?.owner_name || authStore.user?.name }}</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-2">
        <button
          v-for="item in sidebarMenuItems"
          :key="item.key"
          @click="activeTab = item.key"
          :class="[
            'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300',
            activeTab === item.key
              ? 'bg-blue-600 text-white shadow-sm'
              : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'
          ]"
        >
          <i :class="[item.icon, 'text-lg']"></i>
          <span v-if="sidebarOpen" class="font-medium">{{ item.name }}</span>
        </button>
      </nav>

      <!-- Logout -->
      <div class="absolute bottom-6 left-4 right-4">
        <button
          @click="handleLogout"
          class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300"
        >
          <i class="fas fa-sign-out-alt text-lg"></i>
          <span v-if="sidebarOpen" class="font-medium">Logout</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div :class="['transition-all duration-500', sidebarOpen ? 'ml-80' : 'ml-20']">
      <!-- Header -->
      <header class="bg-white shadow-sm border-b border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
            >
              <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">{{ getCurrentTabName() }}</h2>
              <p class="text-gray-600">Manage your restaurant efficiently</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="text-right">
              <p class="text-gray-900 font-semibold">{{ restaurant?.owner_name || authStore.user?.name }}</p>
              <p class="text-gray-600 text-sm">{{ restaurant?.name || 'Restaurant Owner' }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white"></i>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6">
        <!-- Dashboard Overview -->
        <div v-if="activeTab === 'dashboard'" class="space-y-6">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-600 text-sm">Total Orders</p>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.totalOrders }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                  <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-600 text-sm">Revenue</p>
                  <p class="text-3xl font-bold text-gray-900">৳{{ stats.revenue }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                  <span class="text-white text-xl font-bold">৳</span>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-600 text-sm">Menu Items</p>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.menuItems }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center">
                  <i class="fas fa-utensils text-white text-xl"></i>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-600 text-sm">Rating</p>
                  <p class="text-3xl font-bold text-gray-900">{{ restaurant?.rating || '0.0' }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center">
                  <i class="fas fa-star text-white text-xl"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Orders</h3>
            <div class="space-y-3">
              <div v-for="order in orders.slice(0, 5)" :key="order.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                  <p class="text-gray-900 font-semibold">#{{ order.order_number || order.id }}</p>
                  <p class="text-gray-600 text-sm">{{ order.customer?.name || 'Unknown' }}</p>
                </div>
                <div class="text-right">
                  <p class="text-gray-900 font-semibold">৳{{ parseFloat(order.total_amount || 0).toFixed(2) }}</p>
                  <span :class="['px-2 py-1 rounded-full text-xs', getOrderStatusClass(order.status)]">
                    {{ formatStatus(order.status) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Menu Management -->
        <div v-if="activeTab === 'menu'" class="space-y-6">
          <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-900">Menu Management</h3>
            <button
              @click="showAddMenuModal = true"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300"
            >
              <i class="fas fa-plus mr-2"></i>
              Add Menu Item
            </button>
          </div>

          <div v-if="menuItems.length === 0" class="text-center py-12">
            <i class="fas fa-utensils text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No menu items yet</h3>
            <p class="text-gray-500 mb-6">Start building your menu by adding your first item</p>
            <button
              @click="showAddMenuModal = true"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300"
            >
              <i class="fas fa-plus mr-2"></i>
              Add Your First Item
            </button>
          </div>
          
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in menuItems" :key="item.id" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
              <div class="relative">
                <img 
                  :src="getImageUrl(item.image)" 
                  :alt="item.name" 
                  class="w-full h-48 object-cover"
                  @error="handleImageError"
                >
                <div class="absolute top-2 right-2">
                  <span :class="[
                    'px-2 py-1 rounded-full text-xs font-medium',
                    item.is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                  ]">
                    {{ item.is_available ? 'Available' : 'Out of Stock' }}
                  </span>
                </div>
              </div>
              <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                  <h4 class="text-gray-900 font-semibold text-lg">{{ item.name }}</h4>
                  <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">{{ item.category }}</span>
                </div>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ item.description }}</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-2xl font-bold text-gray-900">৳{{ parseFloat(item.price).toFixed(2) }}</span>
                  <div v-if="item.preparation_time" class="text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    {{ item.preparation_time }} min
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="editMenuItem(item)"
                    class="flex-1 p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300 text-sm font-medium"
                  >
                    <i class="fas fa-edit mr-1"></i>
                    Edit
                  </button>
                  <button
                    @click="toggleAvailability(item)"
                    :class="[
                      'flex-1 p-2 rounded-lg transition-all duration-300 text-sm font-medium',
                      item.is_available 
                        ? 'text-orange-600 hover:bg-orange-50' 
                        : 'text-green-600 hover:bg-green-50'
                    ]"
                  >
                    <i :class="[item.is_available ? 'fas fa-eye-slash' : 'fas fa-eye', 'mr-1']"></i>
                    {{ item.is_available ? 'Hide' : 'Show' }}
                  </button>
                  <button
                    @click="deleteMenuItem(item.id)"
                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Orders Management -->
        <div v-if="activeTab === 'orders'" class="space-y-6">
          <h3 class="text-2xl font-bold text-gray-900">Orders Management</h3>
          
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Order #</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Customer</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Items</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Amount</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Phone</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Status</th>
                    <th class="px-6 py-4 text-left text-gray-700 font-semibold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="order in orders" :key="order.id" class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900 font-medium">#{{ order.order_number || order.id }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ order.customer?.name || 'Unknown' }}</td>
                    <td class="px-6 py-4 text-gray-600">
                      <div class="space-y-1">
                        <div v-for="item in order.items" :key="item.menu_item_id" class="text-sm">
                          {{ getMenuItemName(item.menu_item_id) }} × {{ item.quantity }}
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-gray-900 font-semibold">৳{{ parseFloat(order.total_amount || 0).toFixed(2) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ order.customer_phone }}</td>
                    <td class="px-6 py-4">
                      <span :class="['px-3 py-1 rounded-full text-xs font-medium', getOrderStatusClass(order.status)]">
                        {{ formatStatus(order.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <select
                        v-model="order.status"
                        @change="updateOrderStatus(order.id, order.status)"
                        :disabled="order.status === 'delivered'"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                      >
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="preparing">Preparing</option>
                        <option value="ready">Ready</option>
                        <option value="picked_up">Picked Up</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Analytics -->
        <div v-if="activeTab === 'analytics'" class="space-y-6">
          <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-900">Analytics & Reports</h3>
            <button
              @click="$router.push('/restaurant/analytics')"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300"
            >
              <i class="fas fa-chart-line mr-2"></i>
              View Detailed Analytics
            </button>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <h4 class="text-gray-900 font-semibold mb-4">Sales Overview</h4>
              <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <span class="text-gray-600">Total Orders</span>
                  <span class="font-bold text-gray-900">{{ stats.totalOrders }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <span class="text-gray-600">Total Revenue</span>
                  <span class="font-bold text-gray-900">৳{{ stats.revenue }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <span class="text-gray-600">Menu Items</span>
                  <span class="font-bold text-gray-900">{{ stats.menuItems }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <span class="text-gray-600">Rating</span>
                  <span class="font-bold text-gray-900">{{ restaurant?.rating || '0.0' }}</span>
                </div>
              </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
              <h4 class="text-gray-900 font-semibold mb-4">Popular Items</h4>
              <div v-if="popularItems.length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-utensils text-3xl mb-2"></i>
                <p>No popular items data yet</p>
              </div>
              <div v-else class="space-y-3">
                <div v-for="(item, index) in popularItems" :key="index" class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                  <div class="flex items-center space-x-3">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">{{ index + 1 }}</span>
                    <span class="text-gray-900 font-medium">{{ item.name }}</span>
                  </div>
                  <span class="text-gray-600">{{ item.orders }} orders</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div v-if="activeTab === 'settings'" class="space-y-6">
          <h3 class="text-2xl font-bold text-gray-900">Restaurant Settings</h3>
          
          <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <form @submit.prevent="updateRestaurantSettings" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-gray-700 font-semibold mb-2">Restaurant Name</label>
                  <input
                    v-model="restaurantSettings.name"
                    type="text"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                  <input
                    v-model="restaurantSettings.phone"
                    type="tel"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-gray-700 font-semibold mb-2">Opening Time</label>
                  <input
                    v-model="restaurantSettings.opening_time"
                    type="time"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-gray-700 font-semibold mb-2">Closing Time</label>
                  <input
                    v-model="restaurantSettings.closing_time"
                    type="time"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                </div>
              </div>
              
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea
                  v-model="restaurantSettings.description"
                  rows="4"
                  class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
              </div>
              
              <button
                type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300"
              >
                Update Settings
              </button>
            </form>
          </div>
        </div>
      </main>
    </div>

    <!-- Add/Edit Menu Item Modal -->
    <div v-if="showAddMenuModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeModal">
      <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4 shadow-xl max-h-[90vh] overflow-y-auto" @click.stop>
        <h3 class="text-xl font-bold text-gray-900 mb-4">
          {{ editingItemId ? 'Edit Menu Item' : 'Add Menu Item' }}
        </h3>
        <form @submit.prevent="editingItemId ? updateMenuItem() : addMenuItem()" class="space-y-4">
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Name *</label>
            <input
              v-model="newMenuItem.name"
              type="text"
              required
              placeholder="e.g., Chicken Burger"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea
              v-model="newMenuItem.description"
              rows="3"
              placeholder="Describe your dish..."
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Price *</label>
              <input
                v-model="newMenuItem.price"
                type="number"
                step="0.01"
                min="0"
                required
                placeholder="0.00"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Category</label>
              <input
                v-model="newMenuItem.category"
                type="text"
                placeholder="e.g., Italian, Chinese, Desserts, Beverages, etc."
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
              <p class="text-sm text-gray-500 mt-1">Enter any category - no restrictions!</p>
            </div>
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Preparation Time (minutes)</label>
            <input
              v-model="newMenuItem.preparation_time"
              type="number"
              min="1"
              placeholder="15"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Calories</label>
              <input
                v-model="newMenuItem.calories"
                type="number"
                min="0"
                placeholder="250"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Spice Level</label>
              <input
                v-model="newMenuItem.spice_level"
                type="text"
                placeholder="Mild, Medium, Hot, etc."
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Portion Size</label>
              <input
                v-model="newMenuItem.portion_size"
                type="text"
                placeholder="Small, Medium, Large, etc."
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-2">Origin Country</label>
              <input
                v-model="newMenuItem.origin_country"
                type="text"
                placeholder="Italy, China, Mexico, etc."
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Cooking Method</label>
            <input
              v-model="newMenuItem.cooking_method"
              type="text"
              placeholder="Grilled, Fried, Baked, Steamed, etc."
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          <div class="flex space-x-4">
            <label class="flex items-center space-x-2">
              <input
                v-model="newMenuItem.is_available"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="text-gray-700">Available</span>
            </label>
            <label class="flex items-center space-x-2">
              <input
                v-model="newMenuItem.is_featured"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="text-gray-700">Featured Item</span>
            </label>
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Image</label>
            <input
              ref="imageInput"
              type="file"
              accept="image/*"
              @change="handleImageUpload"
              class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <p class="text-sm text-gray-500 mt-1">Upload any image format (JPG, PNG, GIF, WebP, etc.) up to 10MB</p>
          </div>
          <div class="flex space-x-4 pt-4">
            <button
              type="submit"
              class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium"
            >
              {{ editingItemId ? 'Update Item' : 'Add Item' }}
            </button>
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-300 font-medium"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/authStore'

const router = useRouter()
const authStore = useAuthStore()

// State
const sidebarOpen = ref(true)
const activeTab = ref('dashboard')
const showAddMenuModal = ref(false)

// Data
const restaurant = ref(null)
const stats = ref({
  totalOrders: 0,
  revenue: 0,
  menuItems: 0
})

const sidebarMenuItems = [
  { key: 'dashboard', name: 'Dashboard', icon: 'fas fa-tachometer-alt' },
  { key: 'menu', name: 'Menu Management', icon: 'fas fa-utensils' },
  { key: 'orders', name: 'Orders', icon: 'fas fa-shopping-bag' },
  { key: 'analytics', name: 'Analytics', icon: 'fas fa-chart-line' },
  { key: 'settings', name: 'Settings', icon: 'fas fa-cog' }
]

const menuItems = ref([])
const recentOrders = ref([])
const orders = ref([])
const popularItems = ref([])

const newMenuItem = reactive({
  name: '',
  description: '',
  price: '',
  category: '',
  preparation_time: '',
  ingredients: [],
  allergens: [],
  calories: '',
  dietary_info: [],
  spice_level: '',
  portion_size: '',
  cooking_method: '',
  origin_country: '',
  nutritional_info: {},
  tags: [],
  is_available: true,
  is_featured: false
})

const editingItemId = ref(null)
const selectedImage = ref(null)
const imageInput = ref(null)


const restaurantSettings = reactive({
  name: '',
  phone: '',
  opening_time: '',
  closing_time: '',
  description: ''
})

// Methods
const getCurrentTabName = () => {
  return sidebarMenuItems.find(item => item.key === activeTab.value)?.name || 'Dashboard'
}

const getOrderStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    ready: 'bg-green-100 text-green-700',
    picked_up: 'bg-purple-100 text-purple-700',
    delivered: 'bg-emerald-100 text-emerald-700',
    cancelled: 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const formatStatus = (status) => {
  return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const getMenuItemName = (menuItemId) => {
  const item = menuItems.value.find(item => item.id === menuItemId)
  return item ? item.name : `Menu Item #${menuItemId}`
}

const getItemsCount = (items) => {
  if (!items || !Array.isArray(items)) return 0
  return items.reduce((total, item) => total + (item.quantity || 0), 0)
}

const addMenuItem = async () => {
  try {
    console.log('Token:', authStore.token)
    console.log('User:', authStore.user)
    
    const formData = new FormData()
    formData.append('name', newMenuItem.name)
    formData.append('description', newMenuItem.description)
    formData.append('price', newMenuItem.price)
    formData.append('category', newMenuItem.category)
    formData.append('preparation_time', newMenuItem.preparation_time || 0)
    formData.append('calories', newMenuItem.calories || 0)
    formData.append('spice_level', newMenuItem.spice_level)
    formData.append('portion_size', newMenuItem.portion_size)
    formData.append('cooking_method', newMenuItem.cooking_method)
    formData.append('origin_country', newMenuItem.origin_country)
    formData.append('is_available', newMenuItem.is_available)
    formData.append('is_featured', newMenuItem.is_featured)
    if (selectedImage.value) {
      formData.append('image', selectedImage.value)
    }
    
    console.log('Making request to:', 'http://127.0.0.1:8000/api/owner/menu-items')
    
    const response = await fetch('http://127.0.0.1:8000/api/owner/menu-items', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json'
      },
      body: formData
    })
    
    console.log('Response status:', response.status)
    console.log('Response headers:', response.headers)
    
    if (response.ok) {
      const data = await response.json()
      console.log('Success response:', data)
      await loadMenuItems()
      closeModal()
      alert('Menu item added successfully!')
    } else {
      const errorText = await response.text()
      console.log('Error response:', errorText)
      try {
        const error = JSON.parse(errorText)
        alert(error.message || 'Error adding menu item')
      } catch {
        alert('Error adding menu item: ' + errorText)
      }
    }
  } catch (error) {
    console.error('Error adding menu item:', error)
    alert('Error adding menu item: ' + error.message)
  }
}

const editMenuItem = (item) => {
  // Populate form with item data
  newMenuItem.name = item.name
  newMenuItem.description = item.description
  newMenuItem.price = item.price
  newMenuItem.category = item.category
  newMenuItem.preparation_time = item.preparation_time || ''
  editingItemId.value = item.id
  showAddMenuModal.value = true
}

const deleteMenuItem = async (id) => {
  if (confirm('Are you sure you want to delete this menu item?')) {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/owner/menu-items/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${authStore.token}`
        }
      })
      
      if (response.ok) {
        await loadMenuItems()
        alert('Menu item deleted successfully!')
      } else {
        alert('Error deleting menu item')
      }
    } catch (error) {
      console.error('Error deleting menu item:', error)
      alert('Error deleting menu item')
    }
  }
}



const updateMenuItem = async () => {
  try {
    const formData = new FormData()
    formData.append('name', newMenuItem.name)
    formData.append('description', newMenuItem.description)
    formData.append('price', newMenuItem.price)
    formData.append('category', newMenuItem.category)
    formData.append('preparation_time', newMenuItem.preparation_time || 0)
    formData.append('calories', newMenuItem.calories || 0)
    formData.append('spice_level', newMenuItem.spice_level)
    formData.append('portion_size', newMenuItem.portion_size)
    formData.append('cooking_method', newMenuItem.cooking_method)
    formData.append('origin_country', newMenuItem.origin_country)
    formData.append('is_available', newMenuItem.is_available)
    formData.append('is_featured', newMenuItem.is_featured)
    if (selectedImage.value) {
      formData.append('image', selectedImage.value)
    }
    formData.append('_method', 'PUT')
    
    const response = await fetch(`http://127.0.0.1:8000/api/owner/menu-items/${editingItemId.value}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        name: newMenuItem.name,
        description: newMenuItem.description,
        price: newMenuItem.price,
        category: newMenuItem.category,
        preparation_time: newMenuItem.preparation_time || 0,
        calories: newMenuItem.calories || 0,
        spice_level: newMenuItem.spice_level,
        portion_size: newMenuItem.portion_size,
        cooking_method: newMenuItem.cooking_method,
        origin_country: newMenuItem.origin_country,
        is_available: newMenuItem.is_available,
        is_featured: newMenuItem.is_featured
      })
    })
    
    if (response.ok) {
      await loadMenuItems()
      closeModal()
      alert('Menu item updated successfully!')
    } else {
      const error = await response.json()
      alert(error.message || 'Error updating menu item')
    }
  } catch (error) {
    console.error('Error updating menu item:', error)
    alert('Error updating menu item')
  }
}

const toggleAvailability = async (item) => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/owner/menu-items/${item.id}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        ...item,
        is_available: !item.is_available
      })
    })
    
    if (response.ok) {
      await loadMenuItems()
    } else {
      alert('Error updating item availability')
    }
  } catch (error) {
    console.error('Error toggling availability:', error)
    alert('Error updating item availability')
  }
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedImage.value = file
  }
}

const getImageUrl = (imagePath) => {
  if (!imagePath) {
    return 'data:image/svg+xml;base64,' + btoa('<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" font-family="Arial" font-size="16" fill="#9ca3af" text-anchor="middle" dy=".3em">No Image</text></svg>')
  }
  if (imagePath.includes('menu_items/')) {
    return `http://127.0.0.1:8000/storage/${imagePath}`
  }
  return `http://127.0.0.1:8000/storage/menu_items/${imagePath}`
}

const handleImageError = (event) => {
  event.target.src = 'data:image/svg+xml;base64,' + btoa('<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" font-family="Arial" font-size="16" fill="#9ca3af" text-anchor="middle" dy=".3em">No Image</text></svg>')
}

const closeModal = () => {
  showAddMenuModal.value = false
  editingItemId.value = null
  selectedImage.value = null
  if (imageInput.value) {
    imageInput.value.value = ''
  }
  // Reset form
  Object.assign(newMenuItem, {
    name: '',
    description: '',
    price: '',
    category: '',
    preparation_time: '',
    ingredients: [],
    allergens: [],
    calories: '',
    dietary_info: [],
    spice_level: '',
    portion_size: '',
    cooking_method: '',
    origin_country: '',
    nutritional_info: {},
    tags: [],
    is_available: true,
    is_featured: false
  })
}

const updateRestaurantSettings = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/owner/settings', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(restaurantSettings)
    })
    
    const data = await response.json()
    
    if (response.ok) {
      alert('Restaurant settings updated successfully!')
      // Update restaurant data
      if (data.restaurant) {
        restaurant.value = data.restaurant
      }
    } else {
      alert(data.message || 'Error updating settings')
    }
  } catch (error) {
    console.error('Error updating settings:', error)
    alert('Error updating settings')
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
}

const loadRestaurantData = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/owner/dashboard', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      restaurant.value = data.restaurant
      
      // Populate settings form with restaurant data
      if (data.restaurant) {
        restaurantSettings.name = data.restaurant.name || ''
        restaurantSettings.phone = data.restaurant.phone || ''
        restaurantSettings.opening_time = data.restaurant.opening_time || ''
        restaurantSettings.closing_time = data.restaurant.closing_time || ''
        restaurantSettings.description = data.restaurant.description || ''
      }
      
      // Update stats
      stats.value = {
        totalOrders: data.stats?.totalOrders || 0,
        revenue: data.restaurant?.total_earnings || 0,
        menuItems: data.stats?.menuItems || 0
      }
    }
  } catch (error) {
    console.error('Error loading restaurant data:', error)
  }
}

const loadPopularItems = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/restaurant/analytics', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      popularItems.value = data.top_items || []
    }
  } catch (error) {
    console.error('Error loading popular items:', error)
  }
}

const loadMenuItems = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/owner/menu-items', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      menuItems.value = data.menuItems || []
    }
  } catch (error) {
    console.error('Error loading menu items:', error)
  }
}

const loadOrders = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/owner/orders', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      orders.value = data.orders || []
    }
  } catch (error) {
    console.error('Error loading orders:', error)
  }
}

const updateOrderStatus = async (orderId, status) => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/owner/orders/${orderId}/status`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status })
    })
    
    if (response.ok) {
      await loadOrders()
    } else {
      alert('Error updating order status')
    }
  } catch (error) {
    console.error('Error updating order status:', error)
    alert('Error updating order status')
  }
}

onMounted(() => {
  loadRestaurantData()
  loadMenuItems()
  loadOrders()
  loadPopularItems()
  
  // Auto-refresh orders every 5 seconds
  setInterval(() => {
    loadOrders()
  }, 5000)
})
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}
::-webkit-scrollbar-thumb {
  background: rgba(59, 130, 246, 0.5);
  border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(59, 130, 246, 0.7);
}
</style>