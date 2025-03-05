<template>
  <footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <!-- Gradient Line -->
      <div class="h-1 w-24 mx-auto mb-12 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600"></div>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand Section with hover effects -->
        <div class="col-span-1 md:col-span-2 group">
          <div class="flex items-center gap-2 mb-4 transform transition-transform group-hover:scale-105">
            <div class="flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 rounded-lg">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" 
                />
              </svg>
            </div>
            <div class="flex items-center gap-1">
              <span class="text-lg font-medium text-indigo-600">AI</span>
              <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Pro</span>
            </div>
          </div>
          <!-- Social Links with hover animations -->
          <div class="flex space-x-4 mt-6">
            <a href="#" class="transform transition-all hover:scale-110 hover:-translate-y-1">
              <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-600/10 to-purple-600/10 flex items-center justify-center">
                <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </div>
            </a>
            <a href="#" class="transform transition-all hover:scale-110 hover:-translate-y-1">
              <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-600/10 to-purple-600/10 flex items-center justify-center">
                <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                </svg>
              </div>
            </a>
          </div>
        </div>

        <!-- Quick Links with hover effects -->
        <div class="space-y-6">
          <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
            Quick Links
          </h3>
          <ul class="space-y-4">
            <li v-for="link in quickLinks" :key="link.name">
              <router-link :to="link.to" 
                class="group flex items-center text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                <span class="w-1.5 h-1.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 mr-2 transition-opacity duration-200"></span>
                {{ link.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Support Links -->
        <div class="space-y-6">
          <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
            Support
          </h3>
          <ul class="space-y-4">
            <li v-for="link in supportLinks" :key="link.name">
              <a :href="link.href" 
                class="group flex items-center text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                <span class="w-1.5 h-1.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 mr-2 transition-opacity duration-200"></span>
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar with gradient border -->
      <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p class="text-sm text-gray-400">
            &copy; {{ new Date().getFullYear() }} AI Pro. All rights reserved.
          </p>
          <p class="text-sm text-gray-400 mt-4 md:mt-0">
            Made with ❤️ by AI Pro Team
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
const quickLinks = [
  { name: 'Assessments', to: '/exams' },
  { name: 'Resources', to: '/resources' },
  { name: 'Certificates', to: '/certificates' }
]

const supportLinks = [
  { name: 'Help Center', href: '#' },
  { name: 'Terms of Service', href: '#' },
  { name: 'Privacy Policy', href: '#' }
]
</script>