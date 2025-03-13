<template>
  <div>
    <!-- Question Text -->
    <div class="mb-4">
      <h3 class="text-lg font-medium text-gray-900">
        Question {{ questionNumber }}
      </h3>
      <p class="mt-1 text-gray-600">{{ question.text }}</p>
    </div>

    <!-- Question Content Based on Type -->
    <div v-if="question.type === 'prompt'">
      <QuestionPrompt
        :question="question"
        v-model:answer="answer"
        :showValidation="showValidation"
      />
    </div>
    <div v-else-if="question.type === 'single_choice'">
      <QuestionSingleChoice
        :question="question"
        v-model:answer="answer"
        :showValidation="showValidation"
      />
    </div>
    <div v-else-if="question.type === 'multiple_choice'">
      <QuestionMultipleChoice
        :question="question"
        v-model:answer="answer"
        :showValidation="showValidation"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import QuestionSingleChoice from './QuestionSingleChoice.vue'
import QuestionMultipleChoice from './QuestionMultipleChoice.vue'
import QuestionPrompt from './QuestionPrompt.vue'

// ...existing code...
</script>
