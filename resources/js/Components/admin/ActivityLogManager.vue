<template>
  <div class="p-6">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Activity Log Management</h2>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex gap-4">
      <div class="w-64">
        <label class="block text-sm font-medium text-gray-700">User</label>
        <select v-model="selectedUser" class="mt-1 block w-full rounded-md border-gray-300">
          <option value="">All Users</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>
      <div class="w-64">
        <label class="block text-sm font-medium text-gray-700">Date Range</label>
        <input type="date" v-model="dateRange.start" class="mt-1 block rounded-md border-gray-300">
      </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="activity in activities" :key="activity.id">
            <td class="px-6 py-4">{{ activity.causer?.name }}</td>
            <td class="px-6 py-4">{{ activity.event }}</td>
            <td class="px-6 py-4">{{ activity.description }}</td>
            <td class="px-6 py-4">{{ formatDate(activity.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const activities = ref([])
const users = ref([])
const selectedUser = ref('')
const dateRange = ref({ start: '', end: '' })

onMounted(async () => {
  await fetchActivities()
  await fetchUsers()
})

async function fetchActivities() {
  try {
    const { data } = await axios.get('/api/admin/activity-logs', {
      params: {
        user_id: selectedUser.value,
        date_start: dateRange.value.start,
        date_end: dateRange.value.end
      }
    })
    activities.value = data.activities
  } catch (error) {
    console.error('Error fetching activities:', error)
  }
}

async function fetchUsers() {
  try {
    const { data } = await axios.get('/api/admin/users')
    users.value = data.users
  } catch (error) {
    console.error('Error fetching users:', error)
  }
}

function formatDate(date) {
  return new Date(date).toLocaleString()
}
</script>