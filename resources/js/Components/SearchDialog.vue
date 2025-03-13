<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="close" class="relative z-50">
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
        <div class="flex min-h-full items-start justify-center p-4 sm:p-0">
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform rounded-xl bg-white shadow-xl transition-all w-full max-w-2xl mt-16">
              <!-- Search input -->
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  ref="searchInput"
                  v-model="searchQuery"
                  type="text"
                  class="block w-full pl-11 pr-4 py-4 text-gray-900 placeholder-gray-500 bg-white border-0 focus:ring-0 sm:text-sm"
                  placeholder="Search for exams, topics, or categories..."
                  @keyup="handleSearch"
                />
                <button 
                  @click="close"
                  class="absolute right-4 top-1/2 -translate-y-1/2 p-1 rounded-md text-gray-400 hover:text-gray-500"
                >
                  <XMarkIcon class="h-5 w-5" />
                </button>
              </div>

              <!-- Search results -->
              <div v-if="searchQuery" class="px-4 py-3 max-h-[60vh] overflow-y-auto">
                <div v-if="isLoading" class="py-4 text-center">
                  <svg class="animate-spin h-5 w-5 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </div>
                
                <div v-else-if="results.length > 0" class="space-y-4">
                  <router-link
                    v-for="result in results"
                    :key="result.id"
                    :to="{ name: 'exams.show', params: { slug: result.slug }}"
                    class="block p-4 rounded-lg hover:bg-gray-50"
                    @click="close"
                  >
                    <div class="flex items-center justify-between">
                      <div>
                        <h3 class="text-sm font-medium text-gray-900">
                          {{ result.title }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                          {{ result.description }}
                        </p>
                      </div>
                      <ChevronRightIcon class="h-5 w-5 text-gray-400" />
                    </div>
                  </router-link>
                </div>
                
                <div v-else class="py-4 text-center text-sm text-gray-500">
                  No results found for "{{ searchQuery }}"
                </div>
              </div>

              <!-- Quick links -->
              <div v-else class="px-4 py-3 border-t border-gray-100">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quick Links</h3>
                <div class="mt-2 grid grid-cols-2 gap-4">
                  <router-link
                    v-for="link in quickLinks"
                    :key="link.name"
                    :to="link.to"
                    class="flex items-center p-3 rounded-md hover:bg-gray-50"
                    @click="close"
                  >
                    <component :is="link.icon" class="h-5 w-5 text-gray-400" />
                    <span class="ml-3 text-sm text-gray-900">{{ link.name }}</span>
                  </router-link>
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
import { ref, onMounted, nextTick } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { MagnifyingGlassIcon, XMarkIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import { 
  AcademicCapIcon, 
  BookOpenIcon,
  ClipboardDocumentListIcon,
  RocketLaunchIcon 
} from '@heroicons/vue/24/outline'
import axios from 'axios'

// Create our own debounce function
function debounce(fn, delay) {
  let timeoutId
  return function (...args) {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => fn.apply(this, args), delay)
  }
}

const props = defineProps({
  isOpen: Boolean
})

const emit = defineEmits(['close'])

const searchInput = ref(null)
const searchQuery = ref('')
const isLoading = ref(false)
const results = ref([])

const quickLinks = [
  { name: 'All Exams', to: { name: 'exams.index' }, icon: ClipboardDocumentListIcon },
  { name: 'My Learning', to: { name: 'my-learning' }, icon: BookOpenIcon },
  { name: 'Certificates', to: { name: 'certificates' }, icon: AcademicCapIcon },
  { name: 'Getting Started', to: { name: 'resources' }, icon: RocketLaunchIcon }
]

onMounted(() => {
  if (props.isOpen) {
    nextTick(() => {
      searchInput.value?.focus()
    })
  }
})

// Use our custom debounce instead of useDebounceFn
const handleSearch = debounce(async () => {
  if (!searchQuery.value) {
    results.value = []
    return
  }

  isLoading.value = true
  try {
    const response = await axios.get(`/api/search`, {
      params: { query: searchQuery.value }
    })
    results.value = response.data.data
  } catch (error) {
    console.error('Search failed:', error)
    results.value = []
  } finally {
    isLoading.value = false
  }
}, 300)

function close() {
  emit('close')
  searchQuery.value = ''
  results.value = []
}
</script>