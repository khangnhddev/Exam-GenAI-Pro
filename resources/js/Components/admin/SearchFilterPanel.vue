<template>
  <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Search Input -->
      <div class="col-span-2">
        <label v-if="searchLabel" class="block text-sm font-medium text-gray-700 mb-1">
          {{ searchLabel }}
        </label>
        <div class="relative">
          <input
            type="text"
            :value="searchQuery"
            @input="$emit('update:search', $event.target.value)"
            :placeholder="searchPlaceholder"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
          </div>
        </div>
      </div>

      <!-- Status Filter -->
      <div>
        <label v-if="filterLabel" class="block text-sm font-medium text-gray-700 mb-1">
          {{ filterLabel }}
        </label>
        <select
          :value="selectedFilter"
          @change="$emit('update:filter', $event.target.value)"
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">All Status</option>
          <option v-for="filter in filters" :key="filter.value" :value="filter.value">
            {{ filter.label }}
          </option>
        </select>
      </div>

      <!-- Date Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Date Range
        </label>
        <select
          :value="selectedDateRange"
          @change="$emit('update:dateRange', $event.target.value)"
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">All Time</option>
          <option value="today">Today</option>
          <option value="yesterday">Yesterday</option>
          <option value="last7days">Last 7 Days</option>
          <option value="last30days">Last 30 Days</option>
          <option value="thisMonth">This Month</option>
          <option value="lastMonth">Last Month</option>
          <option value="custom">Custom Range</option>
        </select>
      </div>

      <!-- Custom Date Range (shows when 'custom' is selected) -->
      <div v-if="selectedDateRange === 'custom'" class="col-span-full grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
          <input
            type="date"
            :value="customStartDate"
            @input="$emit('update:startDate', $event.target.value)"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
          <input
            type="date"
            :value="customEndDate"
            @input="$emit('update:endDate', $event.target.value)"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

defineProps({
  searchQuery: {
    type: String,
    default: ''
  },
  searchLabel: {
    type: String,
    default: ''
  },
  searchPlaceholder: {
    type: String,
    default: 'Search...'
  },
  selectedFilter: {
    type: String,
    default: ''
  },
  filterLabel: {
    type: String,
    default: ''
  },
  filters: {
    type: Array,
    default: () => []
  },
  selectedDateRange: {
    type: String,
    default: ''
  },
  customStartDate: {
    type: String,
    default: ''
  },
  customEndDate: {
    type: String,
    default: ''
  }
})

defineEmits([
  'update:search',
  'update:filter',
  'update:dateRange',
  'update:startDate',
  'update:endDate'
])
</script>