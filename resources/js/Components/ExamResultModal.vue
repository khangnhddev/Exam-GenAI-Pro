<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="$emit('close')" class="relative z-50">
      <!-- Improved blur backdrop -->
      <TransitionChild
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-md" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 scale-95"
            enter-to="opacity-100 translate-y-0 scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 scale-100"
            leave-to="opacity-0 translate-y-4 scale-95"
          >
            <DialogPanel 
              class="relative w-full max-w-2xl transform rounded-2xl bg-white shadow-2xl transition-all"
            >
              <!-- Confetti effect for passed results -->
              <div v-if="results.passed" class="absolute -top-20 left-0 right-0 h-32 overflow-hidden pointer-events-none">
                <div class="animate-confetti">
                  <div v-for="n in 50" :key="n" 
                       class="confetti-piece"
                       :style="{
                         '--delay': `${Math.random() * 3}s`,
                         '--rotation': `${Math.random() * 360}deg`,
                         '--position': `${Math.random() * 100}%`,
                         backgroundColor: getRandomColor()
                       }"
                  ></div>
                </div>
              </div>

              <!-- Enhanced Header with 3D effect -->
              <div class="relative overflow-hidden rounded-t-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 opacity-90"></div>
                <div class="absolute inset-0 bg-[url('/path/to/pattern.svg')] opacity-10 animate-subtle-drift"></div>
                <div class="relative px-6 py-8 text-center">
                  <h2 class="text-3xl font-bold text-white mb-2">
                    {{ results.passed ? 'Congratulations!' : 'Assessment Complete' }}
                  </h2>
                  <p class="text-indigo-100 text-lg">
                    {{ results.exam_title }}
                  </p>
                  
                  <!-- Animated score circle -->
                  <div class="mt-8 mb-4 relative">
                    <div class="relative inline-flex">
                      <svg class="w-32 h-32">
                        <circle
                          class="text-gray-300/20"
                          stroke-width="8"
                          stroke="currentColor"
                          fill="transparent"
                          r="58"
                          cx="64"
                          cy="64"
                        />
                        <circle
                          class="text-white transition-all duration-1000 ease-out"
                          stroke-width="8"
                          :stroke-dasharray="circumference"
                          :stroke-dashoffset="circumference - (results.score / 100) * circumference"
                          stroke-linecap="round"
                          stroke="currentColor"
                          fill="transparent"
                          r="58"
                          cx="64"
                          cy="64"
                        />
                      </svg>
                      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                        <div class="text-4xl font-bold text-white">
                          {{ results.score }}%
                        </div>
                        <div class="text-sm text-indigo-100">Score</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Enhanced Content Section -->
              <div class="p-6 bg-gradient-to-b from-gray-50 to-white">
                <!-- Stats Cards with Hover Effects -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                  <div 
                    v-for="(stat, index) in stats" 
                    :key="index"
                    class="relative group p-4 rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300"
                  >
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 rounded-xl transition-opacity"></div>
                    <div class="relative">
                      <div class="text-2xl font-bold text-gray-900 group-hover:scale-110 transition-transform">
                        {{ stat.value }}
                      </div>
                      <div class="text-sm font-medium text-gray-500">{{ stat.label }}</div>
                    </div>
                  </div>
                </div>

                <!-- Enhanced Category Performance -->
                <div class="space-y-6">
                  <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <ChartBarIcon class="w-5 h-5 mr-2 text-indigo-500" />
                    Performance by Category
                  </h3>
                  <div class="space-y-4">
                    <transition-group
                      enter-active-class="transition-all duration-300 ease-out"
                      enter-from-class="opacity-0 -translate-x-4"
                      enter-to-class="opacity-100 translate-x-0"
                      leave-active-class="transition-all duration-300 ease-in"
                      leave-from-class="opacity-100 translate-x-0"
                      leave-to-class="opacity-0 translate-x-4"
                    >
                      <div 
                        v-for="category in results.categories" 
                        :key="category.name"
                        class="group rounded-lg p-4 hover:bg-gray-50 transition-colors"
                      >
                        <div class="flex justify-between items-center mb-2">
                          <span class="font-medium text-gray-700">{{ category.name }}</span>
                          <span 
                            class="text-sm font-semibold"
                            :class="getCategoryScoreColor(category.score)"
                          >
                            {{ category.score }}%
                          </span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                          <div 
                            class="h-full rounded-full transition-all duration-1000 ease-out transform origin-left"
                            :class="getCategoryProgressColor(category.score)"
                            :style="{ 
                              width: `${category.score}%`,
                              transform: `scaleX(${isAnimating ? 1 : 0})`
                            }"
                          />
                        </div>
                      </div>
                    </transition-group>
                  </div>
                </div>

                <!-- Action Buttons with Enhanced Styling -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                  <button
                    @click="$emit('close')"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                  >
                    Close
                  </button>
                  <button
                    v-if="results.passed"
                    @click="$emit('download-certificate')"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105"
                  >
                    <ArrowDownTrayIcon class="w-4 h-4 inline-block mr-2" />
                    Download Certificate
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { 
  ChartBarIcon, 
  ArrowDownTrayIcon, // Changed from DownloadIcon
  XMarkIcon 
} from '@heroicons/vue/24/outline'

// Remove TransitionGroup from @headlessui/vue import and use Vue's built-in TransitionGroup

const props = defineProps({
  isOpen: Boolean,
  results: Object
})

const circumference = computed(() => 2 * Math.PI * 58)
const isAnimating = ref(false)

const stats = computed(() => [
  { label: 'Correct', value: props.results.correct_answers },
  { label: 'Questions', value: props.results.total_questions },
  { label: 'Time (min)', value: props.results.time_taken }
])

function getCategoryScoreColor(score) {
  if (score >= 80) return 'text-green-600'
  if (score >= 60) return 'text-yellow-600'
  return 'text-red-600'
}

function getCategoryProgressColor(score) {
  if (score >= 80) return 'bg-gradient-to-r from-green-500 to-emerald-500'
  if (score >= 60) return 'bg-gradient-to-r from-yellow-500 to-orange-500'
  return 'bg-gradient-to-r from-red-500 to-pink-500'
}

onMounted(() => {
  setTimeout(() => {
    isAnimating.value = true
  }, 100)
})

// Add random color function for confetti
function getRandomColor() {
  const colors = [
    '#FF6B6B', // Red
    '#4ECDC4', // Teal
    '#45B7D1', // Blue
    '#96CEB4', // Green
    '#FFEEAD', // Yellow
    '#D4A5A5', // Pink
    '#9B59B6'  // Purple
  ]
  return colors[Math.floor(Math.random() * colors.length)]
}
</script>

<style scoped>
.animate-confetti {
  animation: rain 1.5s ease-out forwards;
}

.animate-subtle-drift {
  animation: drift 20s linear infinite;
}

@keyframes rain {
  0% { transform: translateY(-100%); }
  100% { transform: translateY(100%); }
}

@keyframes drift {
  0% { transform: translate(0, 0); }
  50% { transform: translate(-5%, -5%); }
  100% { transform: translate(0, 0); }
}

.confetti-piece {
  position: absolute;
  width: 10px;
  height: 10px;
  top: 0;
  opacity: 0;
  left: var(--position);
  transform-origin: center;
  transform: rotate(var(--rotation));
  animation: confetti-fall 3s ease-in-out var(--delay) forwards;
}

@keyframes confetti-fall {
  0% {
    opacity: 1;
    transform: translateY(-100%) rotate(var(--rotation));
  }
  75% {
    opacity: 1;
  }
  100% {
    opacity: 0;
    transform: translateY(1000%) rotate(calc(var(--rotation) + 360deg));
  }
}

.animate-confetti {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Add shimmer effect to the score circle */
.score-circle-shimmer {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}
</style>