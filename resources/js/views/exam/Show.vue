<template>
  <div>
   
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { ClockIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  slug: {
    type: String,
    required: true
  }
})

const router = useRouter()
const exam = ref(null)
const loading = ref(true)
const started = ref(false)
const currentQuestionIndex = ref(0)
const currentAnswer = ref(null)
const showValidation = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/exams/${props.slug}`)
    exam.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch exam:', error)
    router.push({ name: 'not-found' })
  } finally {
    loading.value = false
  }
})

const currentQuestion = computed(() => {
  if (!exam.value?.questions) return null
  return exam.value.questions[currentQuestionIndex.value]
})

function startExam() {
  started.value = true
}

// ...rest of your exam logic (navigation, submission, etc.)
</script>