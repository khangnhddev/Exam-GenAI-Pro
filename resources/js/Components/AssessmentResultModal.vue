<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" class="relative z-50" @close="onClose">
      <!-- Backdrop with blur effect -->
      <TransitionChild
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white shadow-2xl transition-all">
              <!-- Header with gradient -->
              <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8">
                <div class="flex items-center justify-between">
                  <div>
                    <DialogTitle as="h3" class="text-2xl font-bold text-white mb-2">
                      Result
                    </DialogTitle>
                    <p class="text-indigo-100 text-sm">
                      {{ results.exam_title }}
                    </p>
                  </div>
                  <button 
                    @click="onClose"
                    class="rounded-xl p-2 hover:bg-white/10 transition-colors"
                  >
                    <XMarkIcon class="h-6 w-6 text-white" />
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="p-8">
                <div class="grid grid-cols-2 gap-8">
                  <!-- Left Column -->
                  <div class="space-y-6">
                    <!-- Score Circle with animations -->
                    <div class="flex justify-center">
                      <div class="relative w-48 h-48">
                        <svg class="w-full h-full transform -rotate-90">
                          <circle
                            cx="96"
                            cy="96"
                            r="88"
                            stroke="#EEF2FF"
                            stroke-width="12"
                            fill="none"
                          />
                          <circle
                            cx="96"
                            cy="96"
                            r="88"
                            :stroke="results.passed ? '#22C55E' : '#EF4444'"
                            stroke-width="12"
                            fill="none"
                            :stroke-dasharray="`${results.score * 5.52}, 552`"
                            class="transition-all duration-1000 ease-out"
                          />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                          <div class="text-center">
                            <div class="text-5xl font-bold" :class="results.passed ? 'text-green-500' : 'text-red-500'">
                              {{ results.score }}%
                            </div>
                            <div class="text-sm font-medium text-gray-500 mt-1">Final Score</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex justify-center">
                      <span
                        class="inline-flex items-center px-6 py-2.5 rounded-full text-sm font-semibold tracking-wide"
                        :class="results.passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                      >
                        {{ results.passed ? 'PASSED' : 'FAILED' }}
                      </span>
                    </div>
                  </div>

                  <!-- Right Column -->
                  <div class="space-y-6">
                    <div class="bg-gray-50 rounded-2xl p-6">
                      <h4 class="text-sm font-semibold text-gray-900 mb-4">Detail</h4>
                      <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm p-2 hover:bg-gray-100 rounded-lg transition-colors">
                          <span class="text-gray-600">Time Taken</span>
                          <span class="font-semibold">{{ results.duration }} mins</span>
                        </div>
                        <div class="flex items-center justify-between text-sm p-2 hover:bg-gray-100 rounded-lg transition-colors">
                          <span class="text-gray-600">Questions</span>
                          <span class="font-semibold">{{ results.total_questions }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm p-2 hover:bg-gray-100 rounded-lg transition-colors">
                          <span class="text-gray-600">Passing Score</span>
                          <span class="font-semibold">{{ results.passing_score }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm p-2 hover:bg-gray-100 rounded-lg transition-colors">
                          <span class="text-gray-600">Completed</span>
                          <span class="font-semibold">{{ new Date(results.completed_at).toLocaleString() }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- AI Assessment Analysis -->
                <div class="mt-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                  <div class="flex items-start space-x-4">
                    <div class="p-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl">
                      <SparklesIcon class="h-6 w-6 text-white" />
                    </div>
                    <div class="flex-1">
                      <h4 class="text-sm font-semibold text-gray-900 mb-2">AI Assessment Analysis</h4>
                      <p class="text-sm text-gray-600 leading-relaxed">
                        {{ getAIFeedback }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mt-8">
                  <button
                    v-if="results.passed"
                    @click="downloadCertificate"
                    class="flex-1 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] duration-200"
                  >
                    <DocumentArrowDownIcon class="h-5 w-5 inline mr-2" />
                    Download Certificate
                  </button>
                  <button
                    @click="onClose"
                    class="flex-1 px-6 py-3.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all transform hover:scale-[1.02] active:scale-[0.98] duration-200"
                  >
                    Close
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
import { computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, DocumentArrowDownIcon, SparklesIcon } from '@heroicons/vue/24/outline'
import { SparklesIcon as SolidSparklesIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  isOpen: Boolean,
  results: Object
})

const emit = defineEmits(['close', 'download-certificate'])

const onClose = () => {
  emit('close')
}

const downloadCertificate = () => {
  emit('download-certificate')
}

const getAIFeedback = computed(() => {
  const { score, passing_score, total_questions, duration } = props.results
  
  // Performance categories
  if (score >= 90) {
    return `Outstanding performance! You've demonstrated exceptional understanding by scoring ${score}%. Your quick completion time of ${duration} minutes shows strong confidence in the subject matter. Consider exploring more advanced topics or helping others learn this material.`
  } else if (score >= passing_score) {
    return `Good job passing with ${score}%! You've shown solid understanding of the material. There's still room for improvement - consider reviewing the questions you missed to strengthen your knowledge further.`
  } else if (score >= passing_score - 10) {
    return `You were close to passing with ${score}%! Focus on reviewing the core concepts and try again. Pay special attention to questions that took you longer to answer, as they might indicate areas needing more study.`
  } else {
    return `Keep practicing! Your score of ${score}% suggests you might benefit from additional study time. Consider breaking down the material into smaller sections and testing yourself regularly before attempting the full exam again.`
  }
})
</script>