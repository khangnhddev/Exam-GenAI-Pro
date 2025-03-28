<template>
  <div class="space-y-4">
    <!-- Challenge Description -->
    <div class="bg-purple-50 rounded-lg p-4">
      <h2 class="text-lg font-semibold text-purple-900 mb-2">Challenge:</h2>
      <p class="text-purple-800 mb-4">{{ question.challenge_description }}</p>
      
      <div class="space-y-2">
        <h3 class="font-medium text-purple-900">Requirements:</h3>
        <ul class="list-disc list-inside text-purple-800">
          <li v-for="(req, index) in question.requirements" :key="index">
            {{ req }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Prompt Editor -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Your Prompt Solution
      </label>
      <div class="h-[200px] border rounded-lg overflow-hidden bg-white">
        <MonacoEditor
          v-model="localAnswer"
          language="markdown"
          :options="editorOptions"
          @change="updateAnswer"
        />
      </div>
    </div>

    <!-- Validation Message -->
    <p v-if="showValidation" class="mt-2 text-sm text-red-600">
      Please write your prompt before proceeding
    </p>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import * as monaco from 'monaco-editor-vue3'

const MonacoEditor = monaco.default

const props = defineProps({
  question: {
    type: Object,
    required: true
  },
  answer: {
    type: String,
    default: ''
  },
  showValidation: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:answer'])

const localAnswer = ref(props.answer)

const editorOptions = {
  minimap: { enabled: false },
  scrollBeyondLastLine: false,
  fontSize: 14,
  lineNumbers: 'on',
  automaticLayout: true,
  wordWrap: 'on',
  theme: 'vs-light',
  padding: { top: 16, bottom: 16 },
  renderLineHighlight: 'none',
  overviewRulerBorder: false,
  scrollbar: {
    vertical: 'hidden',
    horizontal: 'hidden'
  }
}

function updateAnswer(value) {
  localAnswer.value = value
  emit('update:answer', value)
}

watch(() => props.answer, (newValue) => {
  if (newValue !== localAnswer.value) {
    localAnswer.value = newValue
  }
})
</script>