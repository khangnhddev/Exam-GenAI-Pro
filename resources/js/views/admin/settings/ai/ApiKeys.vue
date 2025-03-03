<template>
    <div class="bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">AI Provider API Keys</h3>
            <p class="mt-1 text-sm text-gray-500">Manage API keys for different AI providers.</p>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form @submit.prevent="saveApiKeys">
                <div class="space-y-6">
                    <!-- OpenAI API Key -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-base font-medium text-gray-900">OpenAI</h4>
                                <p class="text-sm text-gray-500">API key for ChatGPT and other OpenAI services</p>
                            </div>
                            <div class="flex items-center">
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    apiKeys.openai ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ apiKeys.openai ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                        </div>
                        <div class="relative">
                            <input 
                                :type="showOpenAI ? 'text' : 'password'"
                                v-model="apiKeys.openai"
                                placeholder="Enter OpenAI API Key"
                                class="block w-full pr-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <button 
                                type="button"
                                @click="showOpenAI = !showOpenAI"
                                class="absolute inset-y-0 right-0 px-3 flex items-center"
                            >
                                <EyeIcon v-if="!showOpenAI" class="h-5 w-5 text-gray-400" />
                                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                            </button>
                        </div>
                    </div>

                    <!-- Google API Key -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-base font-medium text-gray-900">Google AI</h4>
                                <p class="text-sm text-gray-500">API key for Gemini and other Google AI services</p>
                            </div>
                            <div class="flex items-center">
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    apiKeys.google ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ apiKeys.google ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                        </div>
                        <div class="relative">
                            <input 
                                :type="showGoogle ? 'text' : 'password'"
                                v-model="apiKeys.google"
                                placeholder="Enter Google AI API Key"
                                class="block w-full pr-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <button 
                                type="button"
                                @click="showGoogle = !showGoogle"
                                class="absolute inset-y-0 right-0 px-3 flex items-center"
                            >
                                <EyeIcon v-if="!showGoogle" class="h-5 w-5 text-gray-400" />
                                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                            </button>
                        </div>
                    </div>

                    <!-- Deepseek API Key -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-base font-medium text-gray-900">Deepseek</h4>
                                <p class="text-sm text-gray-500">API key for Deepseek services</p>
                            </div>
                            <div class="flex items-center">
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    apiKeys.deepseek ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ apiKeys.deepseek ? 'Configured' : 'Not Configured' }}
                                </span>
                            </div>
                        </div>
                        <div class="relative">
                            <input 
                                :type="showDeepseek ? 'text' : 'password'"
                                v-model="apiKeys.deepseek"
                                placeholder="Enter Deepseek API Key"
                                class="block w-full pr-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <button 
                                type="button"
                                @click="showDeepseek = !showDeepseek"
                                class="absolute inset-y-0 right-0 px-3 flex items-center"
                            >
                                <EyeIcon v-if="!showDeepseek" class="h-5 w-5 text-gray-400" />
                                <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save API Keys
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const toast = useToast()

const apiKeys = ref({
    openai: '',
    google: '',
    deepseek: ''
})

const showOpenAI = ref(false)
const showGoogle = ref(false)
const showDeepseek = ref(false)

const saveApiKeys = async () => {
    try {
        // Call your API to save API keys
        await fetch('/api/settings/ai/api-keys', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(apiKeys.value)
        })
        
        toast.success('API keys saved successfully')
    } catch (error) {
        console.error('Error saving API keys:', error)
        toast.error('Failed to save API keys')
    }
}
</script> 