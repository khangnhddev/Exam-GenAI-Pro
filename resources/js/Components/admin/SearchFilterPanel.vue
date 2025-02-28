<template>
  <div class="mb-8 bg-white shadow rounded-lg">
    <div class="p-6">
      <div class="flex flex-col sm:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1">
          <label for="search" class="block text-sm font-medium text-gray-700">{{ searchLabel }}</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="search"
              :id="searchId"
              v-model="searchValue"
              class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
              :placeholder="searchPlaceholder"
            >
          </div>
        </div>

        <!-- Filter Select -->
        <div v-if="filters.length" class="sm:w-48">
          <label :for="filterId" class="block text-sm font-medium text-gray-700">{{ filterLabel }}</label>
          <select
            :id="filterId"
            v-model="filterValue"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="">{{ filterPlaceholder }}</option>
            <option 
              v-for="option in filters" 
              :key="option.value" 
              :value="option.value"
            >
              {{ option.label }}
            </option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  searchId: {
    type: String,
    default: 'search'
  },
  searchLabel: {
    type: String,
    default: 'Search'
  },
  searchPlaceholder: {
    type: String,
    default: 'Search...'
  },
  filterId: {
    type: String,
    default: 'filter'
  },
  filterLabel: {
    type: String,
    default: 'Filter'
  },
  filterPlaceholder: {
    type: String,
    default: 'All'
  },
  filters: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:search', 'update:filter'])

const searchValue = ref('')
const filterValue = ref('')

watch(searchValue, (newValue) => {
  emit('update:search', newValue)
})

watch(filterValue, (newValue) => {
  emit('update:filter', newValue)
})
</script>