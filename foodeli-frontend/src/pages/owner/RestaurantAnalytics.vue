<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center py-6">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200">
              <i class="fas fa-arrow-left text-gray-600"></i>
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Restaurant Analytics</h1>
              <p class="text-gray-500 text-sm">Track your performance and insights</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Key Metrics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 font-medium">Total Orders</p>
              <p class="text-3xl font-bold text-gray-900">{{ analytics.total_orders || 0 }}</p>
              <p :class="['text-sm mt-1', analytics.orders_change >= 0 ? 'text-green-600' : 'text-red-600']">{{ analytics.orders_change >= 0 ? '+' : '' }}{{ analytics.orders_change || 0 }}% from yesterday</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-shopping-bag text-blue-600"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
              <p class="text-3xl font-bold text-gray-900">৳{{ analytics.total_revenue || 0 }}</p>
              <p :class="['text-sm mt-1', analytics.revenue_change >= 0 ? 'text-green-600' : 'text-red-600']">{{ analytics.revenue_change >= 0 ? '+' : '' }}{{ analytics.revenue_change || 0 }}% from yesterday</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <span class="text-green-600 text-xl font-bold">৳</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 font-medium">Average Order</p>
              <p class="text-3xl font-bold text-gray-900">৳{{ analytics.avg_order_value || 0 }}</p>
              <p :class="['text-sm mt-1', analytics.avg_change >= 0 ? 'text-green-600' : 'text-red-600']">{{ analytics.avg_change >= 0 ? '+' : '' }}{{ analytics.avg_change || 0 }}% from yesterday</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-chart-line text-purple-600"></i>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 font-medium">Customer Rating</p>
              <p class="text-3xl font-bold text-gray-900">{{ analytics.rating || '4.5' }}</p>
              <p :class="['text-sm mt-1', analytics.rating_change >= 0 ? 'text-green-600' : 'text-red-600']">{{ analytics.rating_change >= 0 ? '+' : '' }}{{ analytics.rating_change || 0 }} from yesterday</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-star text-yellow-600"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Orders Chart -->
        <div class="bg-white rounded-xl shadow-sm border p-4">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Orders Overview</h3>
          <div class="h-48">
            <canvas ref="ordersChart"></canvas>
          </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border p-4">
          <h3 class="text-lg font-bold text-gray-900 mb-3">Revenue Trends</h3>
          <div class="h-48">
            <canvas ref="revenueChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Top Menu Items -->
      <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Top Selling Items</h3>
        <div class="space-y-4">
          <div v-for="(item, index) in topItems" :key="index" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-4">
              <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                <span class="font-bold text-gray-600">#{{ index + 1 }}</span>
              </div>
              <div>
                <p class="font-semibold text-gray-900">{{ item.name }}</p>
                <p class="text-sm text-gray-500">{{ item.orders }} orders</p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-bold text-gray-900">৳{{ item.revenue }}</p>
              <p class="text-sm text-gray-500">Revenue</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Recent Orders</h3>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="text-left py-3 px-4 font-medium text-gray-500">Order #</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Customer</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Items</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Amount</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                <th class="text-left py-3 px-4 font-medium text-gray-500">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in recentOrders" :key="order.id" class="border-b hover:bg-gray-50">
                <td class="py-3 px-4 font-medium">#{{ order.order_number }}</td>
                <td class="py-3 px-4">{{ order.customer_name }}</td>
                <td class="py-3 px-4">{{ order.items_count }} items</td>
                <td class="py-3 px-4 font-semibold">৳{{ order.total_amount }}</td>
                <td class="py-3 px-4">
                  <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ formatStatus(order.status) }}
                  </span>
                </td>
                <td class="py-3 px-4 text-gray-500">{{ formatDate(order.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import Chart from 'chart.js/auto'

const analytics = ref({})
const topItems = ref([])
const recentOrders = ref([])
const ordersChart = ref(null)
const revenueChart = ref(null)
const chartData = ref({})
let ordersChartInstance = null
let revenueChartInstance = null

const loadAnalytics = async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await fetch('http://127.0.0.1:8000/api/restaurant/analytics', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      analytics.value = data.analytics || {}
      topItems.value = data.top_items || []
      recentOrders.value = data.recent_orders || []
      chartData.value = data.chart_data || {}
      
      await nextTick()
      createCharts()
    }
  } catch (error) {
    console.error('Analytics load error:', error)
  }
}

const createCharts = () => {
  // Destroy existing charts
  if (ordersChartInstance) {
    ordersChartInstance.destroy()
  }
  if (revenueChartInstance) {
    revenueChartInstance.destroy()
  }

  // Orders Chart
  if (ordersChart.value) {
    ordersChartInstance = new Chart(ordersChart.value, {
      type: 'bar',
      data: {
        labels: chartData.value.orders_labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Orders',
          data: chartData.value.orders_data || [12, 19, 8, 15, 22, 18, 25],
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { display: false }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    })
  }

  // Revenue Chart
  if (revenueChart.value) {
    revenueChartInstance = new Chart(revenueChart.value, {
      type: 'line',
      data: {
        labels: chartData.value.revenue_labels || ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Revenue (৳)',
          data: chartData.value.revenue_data || [1200, 1900, 800, 1500],
          borderColor: 'rgba(34, 197, 94, 1)',
          backgroundColor: 'rgba(34, 197, 94, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { display: false }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    })
  }
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-700',
    preparing: 'bg-blue-100 text-blue-700',
    ready: 'bg-purple-100 text-purple-700',
    picked_up: 'bg-orange-100 text-orange-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const formatStatus = (status) => {
  return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadAnalytics()
  // Auto-refresh every 3 seconds
  setInterval(loadAnalytics, 3000)
})
</script>