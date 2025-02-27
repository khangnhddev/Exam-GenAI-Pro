<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section with Background -->
    <div class="relative bg-white overflow-hidden">
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
          <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 lg:mt-16 lg:px-8 xl:mt-20">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block xl:inline">Learn GenAI with</span>
                <span class="block text-indigo-600 xl:inline">Expert Guidance</span>
              </h1>
              <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                Master Generative AI through interactive courses, hands-on projects, and real-world applications.
              </p>
              <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                <div class="rounded-md shadow">
                  <router-link 
                    to="/courses" 
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10"
                  >
                    Get Started
                  </router-link>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-3">
                  <router-link 
                    to="/learning-paths" 
                    class="w-full flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10"
                  >
                    View Learning Paths
                  </router-link>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
      <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img 
          :src="'/images/hero-image.jpg'" 
          alt="Hero Image"
          class="w-full h-full object-cover"
        />
      </div>
    </div>

    <!-- Popular Courses Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
          Popular Courses
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
          Start learning from our most popular GenAI courses
        </p>
      </div>

      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="course in popularCourses" 
          :key="course.id" 
          class="group relative bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow"
        >
          <div class="h-48 overflow-hidden rounded-t-lg">
            <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-4">
              <span :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                course.level === 'Beginner' ? 'bg-green-100 text-green-800' :
                course.level === 'Intermediate' ? 'bg-yellow-100 text-yellow-800' :
                'bg-red-100 text-red-800'
              ]">
                {{ course.level }}
              </span>
              <span class="text-sm text-gray-500">{{ course.duration }} hours</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ course.title }}</h3>
            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ course.description }}</p>
            <div class="mt-4 flex items-center justify-between">
              <div class="flex items-center">
                <img :src="course.instructor.avatar" :alt="course.instructor.name" class="h-8 w-8 rounded-full">
                <span class="ml-2 text-sm text-gray-600">{{ course.instructor.name }}</span>
              </div>
              <router-link 
                :to="{ name: 'courses.show', params: { id: course.id }}"
                class="text-indigo-600 hover:text-indigo-500"
              >
                Learn more →
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Why Choose GenAI Learning
          </h2>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            Everything you need to master Generative AI development
          </p>
        </div>

        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="feature in features" :key="feature.title" class="relative">
              <div class="flex items-center space-x-4">
                <div :class="`flex-shrink-0 h-12 w-12 rounded-md bg-${feature.color}-100 flex items-center justify-center`">
                  <component 
                    :is="feature.icon" 
                    class="h-6 w-6" 
                    :class="`text-${feature.color}-600`"
                  />
                </div>
                <div>
                  <h3 class="text-lg font-medium text-gray-900">{{ feature.title }}</h3>
                  <p class="mt-2 text-base text-gray-500">{{ feature.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-indigo-800">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="stat in stats" :key="stat.label" class="text-center">
            <div class="text-5xl font-extrabold text-white">{{ stat.value }}</div>
            <div class="mt-2 text-sm font-medium text-indigo-100">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const popularCourses = ref([])
const features = [
  {
    title: 'Interactive Learning',
    description: 'Learn by doing with hands-on exercises and real-world projects',
    icon: 'CodeIcon',
    color: 'indigo'
  },
  {
    title: 'Expert Instructors',
    description: 'Learn from industry professionals with years of experience',
    icon: 'UserGroupIcon',
    color: 'green'
  },
  {
    title: 'Flexible Schedule',
    description: 'Learn at your own pace with lifetime access to all courses',
    icon: 'ClockIcon',
    color: 'blue'
  },
  {
    title: 'Project-Based',
    description: 'Build a portfolio of projects using the latest GenAI tools',
    icon: 'CollectionIcon',
    color: 'purple'
  },
  {
    title: 'Career Support',
    description: 'Get guidance on building your GenAI career path',
    icon: 'ChartBarIcon',
    color: 'pink'
  },
  {
    title: 'Community',
    description: 'Join a community of GenAI developers and learners',
    icon: 'UsersIcon',
    color: 'yellow'
  }
]

const stats = [
  { label: 'Active Learners', value: '10K+' },
  { label: 'Course Hours', value: '500+' },
  { label: 'Completion Rate', value: '94%' },
  { label: 'Expert Instructors', value: '25+' }
]

const fetchPopularCourses = async () => {
  try {
    const response = await axios.get('/api/v1/courses/popular')
    popularCourses.value = response.data
  } catch (error) {
    console.error('Error fetching popular courses:', error)
  }
}

onMounted(() => {
  fetchPopularCourses()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>