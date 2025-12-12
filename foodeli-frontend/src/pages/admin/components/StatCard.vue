<template>
  <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-200/60 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02] cursor-pointer group animate-slide-up">
    <div class="flex items-center justify-between mb-6">
      <div class="flex-1">
        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-2 group-hover:text-slate-600 transition-colors">{{ title }}</p>
        <p class="text-4xl font-bold bg-gradient-to-r from-slate-800 via-slate-700 to-slate-600 bg-clip-text text-transparent group-hover:from-indigo-600 group-hover:via-purple-600 group-hover:to-blue-600 transition-all duration-300 animate-pulse-slow">{{ value }}</p>
      </div>
      <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-all duration-300 animate-bounce-slow', iconBgClass]">
        <component :is="iconComponents[icon]" class="w-8 h-8 text-white animate-pulse" />
      </div>
    </div>
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <div :class="['flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold animate-pulse-slow', changeClass]">
          <TrendingUp v-if="change > 0" class="w-3 h-3 animate-bounce" />
          <TrendingDown v-else class="w-3 h-3 animate-bounce" />
          <span>{{ Math.abs(change) }}%</span>
        </div>
        <span class="text-xs text-slate-400 font-medium">vs yesterday</span>
      </div>
      <div class="w-2 h-2 bg-slate-300 rounded-full animate-pulse"></div>
    </div>
  </div>
</template>

<script setup>
import { computed, h } from 'vue'
import { TrendingUp, TrendingDown } from 'lucide-vue-next'
import {
  Users, Store, ShoppingBag
} from 'lucide-vue-next'

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  color: {
    type: String,
    default: 'indigo'
  },
  change: {
    type: [String, Number],
    default: 0
  }
})

// Custom Taka icon component
const TakaIcon = {
  render() {
    return h('span', { class: 'text-2xl font-bold' }, '৳')
  }
}

const iconComponents = {
  Users,
  Store,
  ShoppingBag,
  DollarSign: TakaIcon
}

const iconBgClass = computed(() => {
  const colors = {
    indigo: 'bg-gradient-to-br from-indigo-500 to-blue-600',
    emerald: 'bg-gradient-to-br from-emerald-500 to-teal-600',
    amber: 'bg-gradient-to-br from-amber-500 to-orange-600',
    rose: 'bg-gradient-to-br from-rose-500 to-pink-600'
  }
  return colors[props.color] || colors.indigo
})

const changeClass = computed(() => {
  const change = Number(props.change)
  if (change > 0) {
    return 'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800'
  } else if (change < 0) {
    return 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800'
  } else {
    return 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-600'
  }
})
</script>
