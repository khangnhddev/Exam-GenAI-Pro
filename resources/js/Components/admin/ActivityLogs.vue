<template>
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Activity Logs</h2>
      </div>

      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  User
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Action
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Description
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Date
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="activity in activities" :key="activity.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ activity.causer?.name || 'System' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ activity.event }}
                </td>
                <td class="px-6 py-4">
                  {{ activity.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ formatDate(activity.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const activities = ref([])

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/admin/activity-logs')
    activities.value = data.activities
  } catch (error) {
    console.error('Error fetching activity logs:', error)
  }
})

function formatDate(date) {
  return new Date(date).toLocaleString()
}
</script>