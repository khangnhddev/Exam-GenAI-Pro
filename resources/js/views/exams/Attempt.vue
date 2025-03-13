<template>
  <!-- ...existing code... -->
  <div v-if="currentQuestion">
    <!-- Regular MCQ Questions -->
    <MultipleChoiceQuestion
      v-if="['single_choice', 'multiple_choice'].includes(currentQuestion.type)"
      :question="currentQuestion"
      :attempt-id="attemptId"
      @answer-submitted="handleAnswerSubmitted"
    />
    
    <!-- Code Submission Questions -->
    <CodeSubmissionQuestion
      v-else-if="currentQuestion.type === 'code_submission'"
      :question="currentQuestion"
      :attempt-id="attemptId"
      @answer-submitted="handleAnswerSubmitted"
    />
  </div>
  <!-- ...existing code... -->
</template>

<script setup>
import CodeSubmissionQuestion from '@/Components/exam/CodeSubmissionQuestion.vue'
// ...existing imports...

// Add to your existing script
function handleAnswerSubmitted(passed) {
  if (isLastQuestion.value) {
    finishExam()
  } else {
    nextQuestion()
  }
}
</script>
