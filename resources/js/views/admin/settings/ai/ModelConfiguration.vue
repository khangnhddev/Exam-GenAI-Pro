<template>
    <div class="bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">AI Model Configuration</h3>
            <p class="mt-1 text-sm text-gray-500">Configure AI models and their parameters for different features.</p>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form @submit.prevent="saveSettings">
                <!-- Default Model Selection -->
                <div class="space-y-6">
                    <div>
                        <label class="text-base font-medium text-gray-900">Default AI Model</label>
                        <p class="text-sm text-gray-500">Select the default AI model to use across the platform.</p>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center">
                                <input v-model="settings.defaultModel" 
                                    id="chatgpt" 
                                    name="default-model" 
                                    type="radio" 
                                    value="chatgpt"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <label for="chatgpt" class="ml-3 block text-sm font-medium text-gray-700">
                                    ChatGPT (OpenAI)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input v-model="settings.defaultModel" 
                                    id="gemini" 
                                    name="default-model" 
                                    type="radio" 
                                    value="gemini"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <label for="gemini" class="ml-3 block text-sm font-medium text-gray-700">
                                    Gemini (Google)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input v-model="settings.defaultModel" 
                                    id="deepseek" 
                                    name="default-model" 
                                    type="radio" 
                                    value="deepseek"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <label for="deepseek" class="ml-3 block text-sm font-medium text-gray-700">
                                    Deepseek
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Model Specific Settings -->
                    <div class="mt-6">
                        <h4 class="text-base font-medium text-gray-900 mb-4">Model Settings</h4>
                        
                        <!-- ChatGPT Settings -->
                        <div v-show="settings.defaultModel === 'chatgpt'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model Version</label>
                                <select v-model="settings.chatgpt.version"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-4-turbo">GPT-4 Turbo</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Temperature</label>
                                <input type="range" v-model="settings.chatgpt.temperature" min="0" max="2" step="0.1"
                                    class="mt-1 w-full">
                                <div class="mt-1 flex justify-between text-xs text-gray-500">
                                    <span>More Focused (0)</span>
                                    <span>More Creative (2)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Gemini Settings -->
                        <div v-show="settings.defaultModel === 'gemini'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model Version</label>
                                <select v-model="settings.gemini.version"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="gemini-pro">Gemini Pro</option>
                                    <option value="gemini-pro-vision">Gemini Pro Vision</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Temperature</label>
                                <input type="range" v-model="settings.gemini.temperature" min="0" max="1" step="0.1"
                                    class="mt-1 w-full">
                                <div class="mt-1 flex justify-between text-xs text-gray-500">
                                    <span>More Focused (0)</span>
                                    <span>More Creative (1)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Deepseek Settings -->
                        <div v-show="settings.defaultModel === 'deepseek'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model Version</label>
                                <select v-model="settings.deepseek.version"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="deepseek-coder">Deepseek Coder</option>
                                    <option value="deepseek-chat">Deepseek Chat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Temperature</label>
                                <input type="range" v-model="settings.deepseek.temperature" min="0" max="1" step="0.1"
                                    class="mt-1 w-full">
                                <div class="mt-1 flex justify-between text-xs text-gray-500">
                                    <span>More Focused (0)</span>
                                    <span>More Creative (1)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature-specific Model Override -->
                    <div class="mt-6">
                        <h4 class="text-base font-medium text-gray-900 mb-4">Feature-specific Model Settings</h4>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Question Generation</label>
                                    <select v-model="settings.features.questionGeneration"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="default">Use Default Model</option>
                                        <option value="chatgpt">ChatGPT</option>
                                        <option value="gemini">Gemini</option>
                                        <option value="deepseek">Deepseek</option>
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Answer Evaluation</label>
                                    <select v-model="settings.features.answerEvaluation"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="default">Use Default Model</option>
                                        <option value="chatgpt">ChatGPT</option>
                                        <option value="gemini">Gemini</option>
                                        <option value="deepseek">Deepseek</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

const settings = ref({
    defaultModel: 'chatgpt',
    chatgpt: {
        version: 'gpt-4',
        temperature: 0.7
    },
    gemini: {
        version: 'gemini-pro',
        temperature: 0.7
    },
    deepseek: {
        version: 'deepseek-coder',
        temperature: 0.7
    },
    features: {
        questionGeneration: 'default',
        answerEvaluation: 'default'
    }
})

const saveSettings = async () => {
    try {
        // Call your API to save settings
        await fetch('/api/settings/ai/models', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(settings.value)
        })
        
        toast.success('AI model settings saved successfully')
    } catch (error) {
        console.error('Error saving settings:', error)
        toast.error('Failed to save settings')
    }
}
</script> 