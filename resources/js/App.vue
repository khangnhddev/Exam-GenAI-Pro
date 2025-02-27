<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Main Content -->
    <main>
      <router-view></router-view>
    </main>
    
    <!-- AI Assistant -->
    <AIAssistant />
  </div>
</template>

<script setup>
import AIAssistant from '@/components/AIAssistant.vue'
import AppNavigation from '@/components/AppNavigation.vue'
</script>