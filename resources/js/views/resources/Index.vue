<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
          <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div class="flex items-center gap-1">
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider">AI</span>
            <span class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent uppercase tracking-wider">Pro</span>
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider ml-1">Resources</span>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Learning Resources</h1>
        <p class="mt-2 text-lg text-gray-600">Access comprehensive learning materials to master AI and technology skills</p>
      </div>

      <!-- Search and Filter Section -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <input type="text" 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Search resources..."
                v-model="searchQuery">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>
          <div class="flex gap-4">
            <select v-model="selectedCategory" 
              class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
            <select v-model="selectedLevel" 
              class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">All Levels</option>
              <option v-for="level in levels" :key="level" :value="level">
                {{ level }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Resources Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="resource in filteredResources" :key="resource.id" 
          class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
          <!-- Resource Image -->
          <div class="relative h-48 bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="absolute inset-0 flex items-center justify-center">
              <component :is="resource.icon" class="w-16 h-16 text-white" />
            </div>
          </div>
          
          <!-- Resource Content -->
          <div class="p-6">
            <div class="flex items-center gap-2 mb-3">
              <span :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                resource.level === 'Beginner' ? 'bg-green-100 text-green-800' :
                resource.level === 'Intermediate' ? 'bg-yellow-100 text-yellow-800' :
                'bg-red-100 text-red-800'
              ]">
                {{ resource.level }}
              </span>
              <span class="text-sm text-gray-500">{{ resource.category }}</span>
            </div>
            
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ resource.title }}</h3>
            <p class="text-gray-600 mb-4 line-clamp-2">{{ resource.description }}</p>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-500">{{ resource.duration }} mins</span>
              </div>
              <button @click="openResource(resource)" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-indigo-600 hover:text-indigo-700">
                Learn More
                <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredResources.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No resources found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedLevel = ref('')

// Example data
const categories = ['Programming', 'Design', 'Data Science', 'DevOps', 'Machine Learning']
const levels = ['Beginner', 'Intermediate', 'Advanced']

const resources = ref([
  {
    id: 1,
    title: 'Introduction to Python Programming',
    description: 'Learn the basics of Python programming language including syntax, data types, control structures, and functions.',
    category: 'Programming',
    level: 'Beginner',
    duration: 120,
    icon: 'div',
  },
  {
    id: 2,
    title: 'Advanced Machine Learning Concepts',
    description: 'Deep dive into advanced machine learning algorithms, neural networks, and deep learning frameworks.',
    category: 'Machine Learning',
    level: 'Advanced',
    duration: 180,
    icon: 'div',
  },
  {
    id: 3,
    title: 'Web Development with React',
    description: 'Master modern web development using React, including components, state management, and routing.',
    category: 'Programming',
    level: 'Intermediate',
    duration: 150,
    icon: 'div',
  },
  {
    id: 4,
    title: 'DevOps Fundamentals',
    description: 'Learn the core concepts of DevOps including CI/CD, containerization, and cloud deployment.',
    category: 'DevOps',
    level: 'Beginner',
    duration: 90,
    icon: 'div',
  },
  {
    id: 5,
    title: 'UI/UX Design Principles',
    description: 'Understanding fundamental principles of user interface and user experience design.',
    category: 'Design',
    level: 'Intermediate',
    duration: 100,
    icon: 'div',
  },
  {
    id: 6,
    title: 'Data Analysis with Python',
    description: 'Learn data analysis using Python libraries like Pandas, NumPy, and Matplotlib.',
    category: 'Data Science',
    level: 'Intermediate',
    duration: 160,
    icon: 'div',
  }
])

const filteredResources = computed(() => {
  return resources.value.filter(resource => {
    const matchesSearch = resource.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         resource.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || resource.category === selectedCategory.value
    const matchesLevel = !selectedLevel.value || resource.level === selectedLevel.value
    
    return matchesSearch && matchesCategory && matchesLevel
  })
})

const openResource = (resource) => {
  router.push({ name: 'resources.show', params: { id: resource.id }})
}
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>