<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <!-- Background overlay -->
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <DialogOverlay class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" />
      </TransitionChild>

      <!-- Modal content -->
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95">
            <DialogPanel
              class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
              <!-- Header -->
              <div class="relative bg-[#6C2BD9] px-6 py-4">
                <DialogTitle class="text-xl font-semibold text-white">Result</DialogTitle>
                <button @click="closeModal"
                  class="absolute right-4 top-4 rounded-lg p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                  <XMarkIcon class="h-5 w-5" />
                </button>
              </div>

              <!-- Content -->
              <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                  <!-- Score Circle -->
                  <div class="flex flex-col items-center justify-center p-4">
                    <div class="relative">
                      <svg class="w-48 h-48">
                        <circle cx="96" cy="96" r="88" fill="none" stroke="#E5E7EB" stroke-width="12" />
                        <circle cx="96" cy="96" r="88" fill="none"
                          :stroke="results.passed ? '#22C55E' : '#EF4444'" stroke-width="12"
                          :stroke-dasharray="circumference" :stroke-dashoffset="dashOffset"
                          transform="rotate(-90 96 96)" class="transition-all duration-1000 ease-out" />
                      </svg>
                      <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-bold">{{ results.score }}%</span>
                        <span class="text-gray-500 text-sm">Final Score</span>
                      </div>
                    </div>
                    <div :class="[
                      'mt-4 px-4 py-1.5 rounded-full text-sm font-medium',
                      results.passed
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'
                    ]">
                      {{ results.passed ? 'PASSED' : 'FAILED' }}
                    </div>
                  </div>

                  <!-- Details -->
                  <div class="bg-gray-50 rounded-xl p-6 h-full">
                    <h3 class="text-lg font-semibold mb-4">Details</h3>
                    <div class="space-y-4">
                      <!-- Time Taken -->
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <ClockIcon class="w-5 h-5 text-gray-500" />
                          <span class="text-gray-600">Time Taken</span>
                        </div>
                        <span class="font-medium">{{ formattedTimeTaken }}</span>
                      </div>

                      <!-- Questions -->
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <QuestionMarkCircleIcon class="w-5 h-5 text-gray-500" />
                          <span class="text-gray-600">Questions</span>
                        </div>
                        <span class="font-medium">{{ results.details.total_questions }}</span>
                      </div>

                      <!-- Passing Score -->
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <CheckBadgeIcon class="w-5 h-5 text-gray-500" />
                          <span class="text-gray-600">Passing Score</span>
                        </div>
                        <span class="font-medium">{{ results.passing_score }}%</span>
                      </div>

                      <!-- Skill Level -->
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <AcademicCapIcon class="w-5 h-5 text-gray-500" />
                          <span class="text-gray-600">Skill Level</span>
                        </div>
                        <span 
                          class="font-medium"
                          :class="{
                            'text-green-600': results.details.skill_level === 'Expert',
                            'text-blue-600': results.details.skill_level === 'Advanced',
                            'text-yellow-600': results.details.skill_level === 'Intermediate',
                            'text-gray-600': results.details.skill_level === 'Beginner'
                          }"
                        >
                          {{ results.details.skill_level }}
                        </span>
                      </div>

                      <!-- Completed At -->
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <CalendarIcon class="w-5 h-5 text-gray-500" />
                          <span class="text-gray-600">Completed</span>
                        </div>
                        <span class="font-medium">{{ formattedCompletedAt }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Performance Stats -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="bg-purple-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                      <ChartBarIcon class="w-5 h-5 text-[#6C2BD9]" />
                      <h4 class="font-medium">Performance</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                      {{ results.score >= 90 ? 'Outstanding' : results.score >= results.passing_score ? 'Good' : 'Needs Improvement' }}
                    </p>
                  </div>

                  <div class="bg-purple-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                      <AcademicCapIcon class="w-5 h-5 text-[#6C2BD9]" />
                      <h4 class="font-medium">Knowledge Level</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                      {{ results.score >= 90 ? 'Expert' : results.score >= results.passing_score ? 'Proficient' : 'Basic' }}
                    </p>
                  </div>

                  <div class="bg-purple-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                      <TrophyIcon class="w-5 h-5 text-[#6C2BD9]" />
                      <h4 class="font-medium">Achievement</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                      {{ results.passed ? 'Certification Earned' : 'Not Yet Certified' }}
                    </p>
                  </div>
                </div>

                <!-- AI Analysis -->
                <div class="mt-8 bg-indigo-50 rounded-xl p-6">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#6C2BD9] rounded-lg flex items-center justify-center">
                      <SparklesIcon class="w-6 h-6 text-white" />
                    </div>
                    <h3 class="text-lg font-semibold">AI Assessment Analysis</h3>
                  </div>
                  <div class="space-y-4">
                    <p class="text-gray-600 leading-relaxed">
                      {{ getDetailedAnalysis }}
                    </p>
                    <div v-if="results.passed" class="mt-4 p-4 bg-green-50 rounded-lg">
                      <p class="text-green-700 text-sm">
                        🎉 Congratulations! You've demonstrated strong understanding of the material.
                        Your score of {{ results.score }}% shows excellent comprehension and application of concepts.
                      </p>
                    </div>
                    <div v-else class="mt-4 p-4 bg-orange-50 rounded-lg">
                      <p class="text-orange-700 text-sm">
                        Keep learning! While you haven't passed this time, your effort shows commitment.
                        Focus on reviewing the material and try again - you're on the right path!
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-between gap-6">
                  <template v-if="results.passed">
                    <button @click="viewCertificate"
                      class="w-full bg-[#6C2BD9] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-[#5925B5] transition-colors flex items-center justify-center gap-2">
                      <EyeIcon class="w-5 h-5" />
                      View Certificate
                    </button>
                  </template>
                  <template v-else>
                    <button @click="closeModal"
                      class="flex-1 bg-[#6C2BD9] text-white px-4 py-2.5 rounded-lg font-medium hover:bg-[#5925B5] transition-colors">
                      Try Again
                    </button>
                    <button @click="closeModal"
                      class="flex-1 bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                      Close
                    </button>
                  </template>
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
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';

import {
  Dialog,
  DialogOverlay,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {
  XMarkIcon,
  DocumentArrowDownIcon,
  SparklesIcon,
  ClockIcon,
  QuestionMarkCircleIcon,
  CheckBadgeIcon,
  CalendarIcon,
  ChartBarIcon,
  AcademicCapIcon,
  TrophyIcon,
  EyeIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  isOpen: Boolean,
  results: {
    type: Object,
    required: true,
    default: () => ({
      score: 0,
      passing_score: 75,
      passed: false,
      details: {
        time_taken: 0,
        total_questions: 0,
        completed_at: null,
        skill_level: 'Beginner',
        exam_title: ''
      }
    })
  }
})

// Format completion date
const formattedCompletedAt = computed(() => {
  if (!props.results?.details?.completed_at) return '-'
  return new Date(props.results.details.completed_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
})

// Add this computed property for formatted time
const formattedTimeTaken = computed(() => {
  const time = props.results?.details?.time_taken || 0
  return time.toFixed(1) + ' mins'
})

const router = useRouter();

const emit = defineEmits(['close', 'view-certificate'])

const closeModal = () => {
  emit('close')
}

const viewCertificate = async () => {
  if (props.results?.certificate_id) {
    try {
      // Wait for navigation to complete before closing modal
      await router.push(`/certificates/${props.results.certificate_id}`);
      // Only close modal after successful navigation
      closeModal();
    } catch (error) {
      console.error('Navigation error:', error);
      toast.error('Failed to view certificate');
    }
  } else {
    console.warn('No certificate ID found in results');
    toast.warning('Certificate not available');
  }
}

const getDetailedAnalysis = computed(() => {
  if (props.results.score >= 90) {
    return `Outstanding performance! You've demonstrated exceptional understanding of the material, completing the assessment in ${props.timeTaken} minutes. Your quick and accurate responses show mastery of the subject matter. Consider exploring advanced topics or sharing your knowledge with others.`
  } else if (props.results.score >= props.results.passing_score) {
    return `Good job! You've shown solid comprehension of the material, finishing in ${props.timeTaken} minutes. Your performance meets certification standards, though there's room for deeper understanding in some areas. Review any missed questions to strengthen your knowledge.`
  } else {
    return `Thank you for completing the assessment in ${props.timeTaken} minutes. While you haven't reached the passing threshold yet, this is a great learning opportunity. Focus on reviewing the core concepts and try again - many successful learners needed multiple attempts to master this material.`
  }
})

// Circle progress calculation
const circumference = computed(() => 2 * Math.PI * 88)
const dashOffset = computed(() => circumference.value * (1 - props.results.score / 100))

// Animation on mount
onMounted(() => {
  // Add any mount animations if needed
  console.log('Result assessment modal mounted results', props.results);
})
</script>

<style scoped>
/* Add any additional styles here */
</style>