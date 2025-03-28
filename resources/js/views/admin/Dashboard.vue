<template>
  <div>
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    
    <div class="mt-6">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Stats Cards -->
        <!-- <StatsCard 
          v-for="(value, key) in stats" 
          :key="key"
          :title="formatTitle(key)"
          :value="value"
          :icon="getIcon(key)"
          :color="getColor(key)"
        /> -->
      </div>

      <!-- Recent Activities -->
      <div class="mt-8">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Activities</h3>
        <div class="mt-2 bg-white shadow overflow-hidden sm:rounded-md">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="activity in recentActivities" :key="activity.id">
              <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-medium text-indigo-600 truncate">
                    {{ activity.user.name }}
                  </p>
                  <div class="ml-2 flex-shrink-0 flex">
                    <p class="text-sm text-gray-500">
                      {{ activity.date }}
                    </p>
                  </div>
                </div>
                <div class="mt-2 sm:flex sm:justify-between">
                  <div class="sm:flex">
                    <p class="text-sm text-gray-500">
                      {{ activity.description }}
                    </p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { adminApi } from '@/services/api'
// import StatsCard from '@/components/StatsCard.vue'
import { 
  UserIcon,
  DocumentTextIcon,
  QuestionMarkCircleIcon,
  ClipboardDocumentCheckIcon 
} from '@heroicons/vue/24/solid' // Change from 'outline' to '24/solid' for v2

const stats = ref({})
const recentActivities = ref([])
const loading = ref(true)

const formatTitle = (key) => {
  return `Total ${key.charAt(0).toUpperCase() + key.slice(1)}`
}

const getIcon = (key) => {
  const icons = {
    users: UserIcon,
    exams: DocumentTextIcon,
    questions: QuestionMarkCircleIcon,
    attempts: ClipboardDocumentCheckIcon
  }
  return icons[key]
}

const getColor = (key) => {
  const colors = {
    users: 'blue',
    exams: 'green',
    questions: 'yellow',
    attempts: 'purple'
  }
  return colors[key]
}

onMounted(async () => {
  try {
    loading.value = true
    const { data } = await adminApi.getDashboardStats()
    stats.value = data.stats
    recentActivities.value = data.recentActivities
  } catch (error) {
    console.error('Error loading dashboard stats:', error);
  } finally {
    loading.value = false
  }
})
</script>