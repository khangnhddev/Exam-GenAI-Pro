<template>
  <div class="fixed bottom-4 right-4 w-96 z-50">
    <div v-if="isOpen" class="bg-white rounded-lg shadow-xl border">
      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center bg-indigo-600">
        <h3 class="text-lg font-medium text-white">Learning Assistant</h3>
        <button @click="isOpen = false" class="text-white hover:text-gray-200">
          <span class="sr-only">Close</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Messages -->
      <div ref="messagesContainer" class="h-96 overflow-y-auto p-4 space-y-4">
        <div v-if="messages.length === 0" class="text-center text-gray-500">
          <p>👋 Hi! I'm your learning assistant.</p>
          <p class="text-sm mt-2">Ask me anything about your learning journey!</p>
        </div>

        <div v-for="(message, index) in messages" 
             :key="index"
             :class="[
               'p-3 rounded-lg max-w-[80%]',
               message.role === 'user' 
                 ? 'bg-indigo-100 ml-auto' 
                 : 'bg-gray-100'
             ]"
        >
          <div class="prose prose-sm" v-html="formatMessage(message.content)"></div>
        </div>

        <div v-if="loading" class="flex items-center space-x-2">
          <div class="typing-indicator">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>

      <!-- Input -->
      <div class="p-4 border-t">
        <form @submit.prevent="sendMessage" class="flex space-x-2">
          <input
            v-model="query"
            type="text"
            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Ask a question..."
            :disabled="loading"
          >
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Send
          </button>
        </form>
      </div>
    </div>

    <!-- Float Button -->
    <button
      v-else
      @click="isOpen = true"
      class="bg-indigo-600 text-white rounded-full p-3 shadow-lg hover:bg-indigo-700"
    >
      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { marked } from 'marked'
import DOMPurify from 'dompurify'
import axios from 'axios'

const isOpen = ref(false)
const query = ref('')
const loading = ref(false)
const messages = ref([])
const messagesContainer = ref(null)

function formatMessage(content) {
  return DOMPurify.sanitize(marked(content))
}

async function scrollToBottom() {
  await nextTick()
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

watch(messages, scrollToBottom)

async function sendMessage() {
  if (!query.value.trim() || loading.value) return

  const userQuery = query.value
  messages.value.push({ role: 'user', content: userQuery })
  query.value = ''
  loading.value = true

  try {
    const { data } = await axios.post('/ai-assistant/query', { query: userQuery })
    messages.value.push({ role: 'assistant', content: data.response })
  } catch (error) {
    messages.value.push({
      role: 'assistant',
      content: 'Sorry, I encountered an error. Please try again.'
    })
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.typing-indicator {
  display: flex;
  gap: 4px;
}

.typing-indicator span {
  width: 8px;
  height: 8px;
  background: #90909090;
  border-radius: 50%;
  animation: bounce 1.5s infinite;
}

.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-4px); }
}
</style>