<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">
            {{ mode === 'add' ? 'Add New' : 'Edit' }} {{ getEntityName() }}
          </h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
            <X class="w-6 h-6" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div v-for="field in getFields()" :key="field.key">
            <label :for="field.key" class="block text-sm font-medium text-gray-700 mb-1">
              {{ field.label }}
            </label>
            <input
              v-if="field.type === 'text' || field.type === 'email'"
              :id="field.key"
              :type="field.type"
              v-model="formData[field.key]"
              :placeholder="field.placeholder"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
            <select
              v-else-if="field.type === 'select'"
              :id="field.key"
              v-model="formData[field.key]"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option v-for="option in field.options" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>

          <div class="flex space-x-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all"
            >
              {{ mode === 'add' ? 'Add' : 'Update' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { X } from 'lucide-vue-next'

const props = defineProps({
  mode: String,
  item: Object
})

const emit = defineEmits(['close', 'submit'])

const formData = ref({})

const getEntityName = () => {
  // This would be dynamic based on activeTab, but for simplicity we'll return a generic name
  return 'Item'
}

const getFields = () => {
  // This would be dynamic based on activeTab, but for simplicity we'll return basic fields
  return [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Enter name' },
    { key: 'email', label: 'Email', type: 'email', placeholder: 'Enter email' },
    { key: 'status', label: 'Status', type: 'select', options: [
      { value: 'active', label: 'Active' },
      { value: 'inactive', label: 'Inactive' }
    ]}
  ]
}

const handleSubmit = () => {
  emit('submit', formData.value)
}

watch(() => props.item, (newItem) => {
  if (newItem) {
    formData.value = { ...newItem }
  } else {
    formData.value = {}
  }
}, { immediate: true })
</script>
