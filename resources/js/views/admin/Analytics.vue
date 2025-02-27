<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">System Analytics</h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <!-- Users Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <UserIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">{{ stats.users.total }}</div>
                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                      <span>{{ stats.users.newThisMonth }} new this month</span>
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <!-- Exams Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <DocumentIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Exams</dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">{{ stats.exams.total }}</div>
                    <div class="ml-2 flex items-baseline text-sm font-semibold text-blue-600">
                      <span>{{ stats.exams.published }} published</span>
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <!-- Certificates Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <AcademicCapIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Certificates Issued</dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">{{ stats.certificates.total }}</div>
                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                      <span>{{ stats.certificates.active }} active</span>
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Monthly Activity Chart -->
        <div class="bg-white p-6 shadow rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Activity</h3>
          <LineChart :data="monthlyStats" />
        </div>

        <!-- Success Rate Chart -->
        <div class="bg-white p-6 shadow rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Exam Success Rate</h3>
          <DoughnutChart :data="examStats" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { UserIcon, DocumentIcon, AcademicCapIcon } from '@heroicons/vue/outline'
import axios from 'axios'
import LineChart from '@/components/charts/LineChart.vue'
import DoughnutChart from '@/components/charts/DoughnutChart.vue'

const stats = ref({
  users: { total: 0, active: 0, newThisMonth: 0 },
  exams: { total: 0, published: 0, draft: 0 },
  questions: { total: 0, byType: { single: 0, multiple: 0 } },
  attempts: { total: 0, passed: 0, avgScore: 0 },
  certificates: { total: 0, active: 0, revoked: 0 }
})

const monthlyStats = ref([])

async function loadStats() {
  try {
    const [systemStats, monthlyData] = await Promise.all([
      axios.get('/api/admin/analytics/system'),
      axios.get('/api/admin/analytics/monthly')
    ])
    
    stats.value = systemStats.data
    monthlyStats.value = monthlyData.data
  } catch (error) {
    console.error('Error loading analytics:', error)
  }
}

onMounted(loadStats)
</script>