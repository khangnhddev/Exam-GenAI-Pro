<template>
  <div>
    <!-- Floating Button -->
    <button
      @click="toggleAssistant"
      class="fixed bottom-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-3 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 z-30"
      title="AI Assistant"
    >
      <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Chat Panel -->
    <div
      v-if="isOpen"
      class="fixed bottom-20 right-6 w-96 h-96 bg-white rounded-lg shadow-xl overflow-hidden flex flex-col z-20"
    >
      <!-- Header -->
      <div class="bg-indigo-600 text-white px-4 py-3 flex justify-between items-center">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <h3 class="font-medium">AI Learning Assistant</h3>
        </div>
        <button
          @click="toggleAssistant"
          class="text-white hover:text-gray-200 focus:outline-none"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <!-- Chat Messages -->
      <div class="flex-grow overflow-y-auto p-4" ref="messagesContainer">
        <div v-for="(message, index) in messages" :key="index" class="mb-4">
          <!-- AI Message -->
          <div v-if="message.type === 'assistant'" class="flex items-start">
            <div class="flex-shrink-0 bg-indigo-500 rounded-full p-2">
              <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="ml-3 bg-gray-100 rounded-lg py-2 px-3 max-w-[80%]">
              <p class="text-sm text-gray-800" v-html="formatMessage(message.content)"></p>
            </div>
          </div>

          <!-- User Message -->
          <div v-else class="flex items-start justify-end">
            <div class="mr-3 bg-indigo-600 rounded-lg py-2 px-3 max-w-[80%]">
              <p class="text-sm text-white">{{ message.content }}</p>
            </div>
            <div class="flex-shrink-0 bg-gray-300 rounded-full p-2">
              <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
        </div>
        <div v-if="loading" class="flex justify-center py-2">
          <div class="typing-indicator">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>

      <!-- Input Area -->
      <div class="border-t p-3 bg-gray-50">
        <form @submit.prevent="sendMessage" class="flex">
          <input
            v-model="userInput"
            type="text"
            placeholder="Ask me anything..."
            class="flex-grow mr-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :disabled="loading"
          />
          <button
            type="submit"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="!userInput.trim() || loading"
          >
            <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            <svg v-else class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </button>
        </form>
        <p class="text-xs text-gray-500 mt-1">I can help with exam tips, learning resources, and more.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

const authStore = useAuthStore()
const route = useRoute()

const isOpen = ref(false)
const userInput = ref('')
const messages = ref([])
const loading = ref(false)
const messagesContainer = ref(null)

// Initialize with welcome message
onMounted(() => {
  messages.value.push({
    type: 'assistant',
    content: 'Hi there! I\'m your personal learning assistant. How can I help you today?'
  })

  // Check if user has preferences and exams history
  if (authStore.user) {
    fetchUserContext()
  }
})

// Listen for route changes to provide contextual help
watch(() => route.path, (newPath) => {
  if (isOpen.value) {
    provideContextualHelp(newPath)
  }
})

// Scroll to bottom when new messages arrive
watch(() => messages.value.length, () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
})

// Fetch user context for personalization
async function fetchUserContext() {
  try {
    const { data } = await axios.get('/api/v1/user/learning-profile')
    // We'll use this data to personalize responses
  } catch (error) {
    console.error('Error fetching user context:', error)
  }
}

// Provide contextual help based on current route
function provideContextualHelp(path) {
  loading.value = true
  
  setTimeout(() => {
    loading.value = false
    
    if (path.includes('/exams') && !path.includes('/attempt')) {
      messages.value.push({
        type: 'assistant',
        content: 'I see you\'re browsing exams. Need help finding the right one for your skill level?'
      })
    } else if (path.includes('/attempt')) {
      messages.value.push({
        type: 'assistant',
        content: 'Good luck on your exam! Remember to read each question carefully. I\'ll be here if you need study tips afterward.'
      })
    } else if (path.includes('/certificates')) {
      messages.value.push({
        type: 'assistant',
        content: 'Congratulations on your certificates! These validate your skills and can be shared with employers.'
      })
    } else if (path.includes('/my-learning')) {
      messages.value.push({
        type: 'assistant',
        content: 'Here\'s your learning journey. Need recommendations on what to study next?'
      })
    }
  }, 800)
}

// Toggle assistant visibility
function toggleAssistant() {
  isOpen.value = !isOpen.value
  
  if (isOpen.value && messages.value.length === 1) {
    // Provide contextual help based on current route
    provideContextualHelp(route.path)
  }
}

// Format message text with basic markdown-like features
function formatMessage(text) {
  // Convert URLs to clickable links
  const urlRegex = /(https?:\/\/[^\s]+)/g
  let formattedText = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="text-blue-600 hover:underline">${url}</a>`)
  
  // Convert **bold** to <strong>
  formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
  
  // Convert *italic* to <em>
  formattedText = formattedText.replace(/\*(.*?)\*/g, '<em>$1</em>')
  
  // Handle line breaks
  formattedText = formattedText.replace(/\n/g, '<br>')
  
  return formattedText
}

// Send message to AI assistant
async function sendMessage() {
  if (!userInput.value.trim() || loading.value) return
  
  // Add user message to chat
  const userMessage = userInput.value
  messages.value.push({
    type: 'user',
    content: userMessage
  })
  
  // Clear input
  userInput.value = ''
  loading.value = true
  
  try {
    // Make API request to your backend
    const { data } = await axios.post('/api/v1/assistant/chat', {
      message: userMessage,
      context: {
        path: route.path,
        userId: authStore.user?.id
      }
    })
    
    // Add AI response
    messages.value.push({
      type: 'assistant',
      content: data.response
    })
  } catch (error) {
    console.error('Error sending message:', error)
    messages.value.push({
      type: 'assistant',
      content: 'Sorry, I encountered an error. Please try again later.'
    })
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Typing indicator animation */
.typing-indicator {
  display: flex;
  align-items: center;
}

.typing-indicator span {
  height: 8px;
  width: 8px;
  background: #3730A3;
  border-radius: 50%;
  display: inline-block;
  margin: 0 2px;
  animation: bounce 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
  animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
  animation-delay: -0.16s;
}

@keyframes bounce {
  0%, 80%, 100% { 
    transform: scale(0);
  }
  40% { 
    transform: scale(1.0);
  }
}
</style>