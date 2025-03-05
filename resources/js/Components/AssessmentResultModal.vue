<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" class="relative z-50" @close="onClose">
      <!-- Backdrop -->
      <TransitionChild
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-900/75" />
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
            <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white shadow-xl transition-all">
              <!-- Header with gradient -->
              <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center justify-between">
                  <DialogTitle as="h3" class="text-xl font-medium text-white">
                    Assessment Results
                  </DialogTitle>
                  <button 
                    @click="onClose"
                    class="rounded-lg p-1 hover:bg-white/10 transition-colors"
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
                    <!-- Score Circle -->
                    <div class="flex justify-center">
                      <div class="relative w-40 h-40">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                          <path
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none"
                            stroke="#E2E8F0"
                            stroke-width="3"
                          />
                          <path
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none"
                            :stroke="results.passed ? '#48BB78' : '#F56565'"
                            stroke-width="3"
                            :stroke-dasharray="`${results.score}, 100`"
                          />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                          <div class="text-center">
                            <div class="text-4xl font-bold" :class="results.passed ? 'text-green-600' : 'text-red-600'">
                              {{ results.score }}%
                            </div>
                            <div class="text-sm text-gray-500">Final Score</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex justify-center">
                      <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium"
                        :class="results.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ results.passed ? 'PASSED' : 'FAILED' }}
                      </span>
                    </div>
                  </div>

                  <!-- Right Column -->
                  <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                      <h4 class="text-sm font-medium text-gray-900 mb-4">Assessment Details</h4>
                      <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-500">Time Taken</span>
                          <span class="font-medium">{{ results.duration }} mins</span>
                        </div>
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-500">Questions</span>
                          <span class="font-medium">{{ results.total_questions }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-500">Passing Score</span>
                          <span class="font-medium">{{ results.passing_score }}%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                          <span class="text-gray-500">Completed</span>
                          <span class="font-medium">{{ new Date(results.completed_at).toLocaleString() }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mt-8">
                  <button
                    v-if="results.passed"
                    @click="downloadCertificate"
                    class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all"
                  >
                    <DocumentArrowDownIcon class="h-5 w-5 inline mr-2" />
                    Download Certificate
                  </button>
                  <button
                    @click="onClose"
                    class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
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
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, DocumentArrowDownIcon } from '@heroicons/vue/24/outline'

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
</script>