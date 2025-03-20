<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <div class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-10">
      <div class="px-4 sm:px-6 lg:px-8 mx-auto flex items-center justify-between h-16"> <!-- Increased height -->
        <h1 class="text-lg font-medium text-gray-900 truncate">{{ exam?.title }}</h1>

        <div class="flex items-center space-x-6"> <!-- Increased spacing -->
          <!-- Enhanced Timer -->
          <div class="flex items-center px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100 shadow-sm">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-6 w-6 text-indigo-600 mr-2"
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" 
              />
            </svg>
            <div class="flex flex-col">
              <!-- <span class="text-xs text-indigo-600 font-medium">Time Remaining</span> -->
              <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                {{ formatTime(timeLeft) }}
              </span>
            </div>
          </div>

          <!-- Exit Button -->
          <button 
            @click="confirmExit"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors rounded-md hover:bg-gray-50"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5 mr-2" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" 
              />
            </svg>
            {{ t.exit }}
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pb-20">
      <div class="max-w-3xl mx-auto px-4 py-8">
        <div v-if="currentQuestion" class="space-y-8">
          <!-- Question Text -->
          <div class="prose max-w-none">
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              {{ t.question }} {{ currentQuestionIndex + 1 }}
              <span class="text-sm text-gray-500 ml-2">
                ({{ currentQuestion.type === 'multiple_choice' ? t.multipleChoice : t.singleChoice }})
              </span>
            </h3>
            <div class="text-gray-700" v-html="currentQuestion.question_text"></div>
          </div>

          <!-- Options -->
          <div class="space-y-3">
            <!-- Single Choice Questions -->
            <template v-if="currentQuestion.type === 'single_choice'">
              <label v-for="(option, index) in currentQuestion.options" :key="index"
                class="flex items-start p-4 rounded-lg border transition-colors duration-150 cursor-pointer" :class="[
                  isOptionSelected(index)
                    ? 'border-indigo-500 bg-gradient-to-r from-indigo-50 to-purple-50'
                    : 'border-gray-200 hover:bg-gray-50',
                  !isCurrentQuestionAnswered && 'border-red-200'
                ]">
                <input type="radio" :name="'question-' + currentQuestion.id" :value="index"
                  v-model="selectedAnswers[currentQuestion.id]"
                  class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" required />
                <span class="ml-3 text-gray-700" v-html="option.option_text"></span>
              </label>
            </template>

            <!-- Multiple Choice Questions -->
            <template v-else-if="currentQuestion.type === 'multiple_choice'">
              <label v-for="(option, index) in currentQuestion.options" :key="index"
                class="flex items-start p-4 rounded-lg border transition-colors duration-150 cursor-pointer" :class="[
                  isOptionSelected(index)
                    ? 'border-indigo-500 bg-gradient-to-r from-indigo-50 to-purple-50'
                    : 'border-gray-200 hover:bg-gray-50',
                  !isCurrentQuestionAnswered && 'border-red-200'
                ]">
                <input type="checkbox" :value="index" :checked="isOptionSelected(index)"
                  @change="handleMultipleChoice(index)"
                  class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" required />
                <span class="ml-3 text-gray-700" v-html="option.option_text"></span>
              </label>
            </template>

            <!-- Prompt Questions -->
            <template v-else-if="currentQuestion.type === 'prompt'">
              <div class="space-y-6">
                <!-- Challenge Description -->
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg p-6">
                  <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                      <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0012 18.75c-1.03 0-1.96-.413-2.64-1.088l-.547-.547z" />
                      </svg>
                      <h4 class="text-lg font-semibold text-purple-900">Challenge Description</h4>
                    </div>

                    <div class="prose max-w-none">
                      <div class="text-gray-700" v-html="currentQuestion.question_text"></div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="mt-6 space-y-3">
                      <h5 class="flex items-center text-sm font-medium text-purple-900">
                        <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Requirements:
                      </h5>
                      <ul class="space-y-2">
                        <li v-for="(req, index) in currentQuestion.requirements" :key="index" class="flex items-start">
                          <svg class="h-5 w-5 text-purple-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <span class="text-gray-700">{{ req }}</span>
                        </li>
                      </ul>
                    </div>

                    <!-- Tips Section -->
                    <!-- <div class="mt-4 bg-white bg-opacity-50 rounded-md p-4">
                      <h6 class="flex items-center text-sm font-medium text-purple-900 mb-2">
                        <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tips:
                      </h6>
                      <ul class="text-sm text-gray-600 space-y-1">
                        <li>Be specific and detailed in your response</li>
                        <li>Address all requirements listed above</li>
                        <li>Use clear and concise language</li>
                        <li>Check your answer before proceeding</li>
                      </ul>
                    </div> -->
                  </div>
                </div>

                <!-- Answer Editor Section -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                  <!-- Editor Toolbar -->
                  <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm font-medium text-gray-700">{{ t.yourAnswer }}</span>
                      <div class="h-4 border-l border-gray-300"></div>
                      <span class="text-xs text-gray-500">
                        AI will evaluate your answer based on the requirements
                      </span>
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                      <span>{{ t.markdownSupported }}</span>
                    </div>
                  </div>

                  <!-- Monaco Editor -->
                  <div class="relative">
                    <div class="h-[300px] w-full border-0">
                      <MonacoEditor v-if="shouldShowEditor" :key="currentQuestionIndex"
                        v-model="selectedAnswers[currentQuestion.id]" :language="'markdown'" :options="{
                          ...editorOptions,
                          automaticLayout: true,
                          minimap: { enabled: false },
                          scrollBeyondLastLine: false,
                          fontSize: 14,
                          lineNumbers: 'on',
                          wordWrap: 'on',
                          theme: 'vs-light',
                          padding: { top: 16, bottom: 16 },
                          scrollbar: {
                            vertical: 'visible',
                            horizontal: 'visible'
                          },
                          lineHeight: 24,
                          folding: true,
                          renderLineHighlight: 'all',
                        }" @change="handleEditorChange" class="w-full h-full" />
                    </div>
                  </div>

                  <!-- Character Count -->
                  <div class="px-4 py-2 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                      {{ t.characters }}: {{ selectedAnswers[currentQuestion.id]?.length || 0 }}
                    </div>
                    <div class="text-xs text-gray-500">
                      {{ t.suggestions }}
                    </div>
                  </div>
                </div>

                <!-- Test Prompt Section -->
                <!-- <div class="mt-6">
                  <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                      <button @click="testPrompt" :disabled="!selectedAnswers[currentQuestion.id] || isTestingPrompt"
                        class="inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium transition-all"
                        :class="[
                          !selectedAnswers[currentQuestion.id] || isTestingPrompt
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white'
                        ]">
                        <template v-if="isTestingPrompt">
                          <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                          </svg>
                          Evaluating...
                        </template>
                        <template v-else>
                          <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                          </svg>
                          Test Your Answer
                        </template>
                      </button>

                      <span class="text-sm text-gray-500">
                        Get instant feedback before submitting
                      </span>
                    </div>

                    <transition enter-active-class="transition ease-out duration-100"
                      enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                      leave-active-class="transition ease-in duration-75"
                      leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                      <button v-if="testResults" @click="resetTestResults"
                        class="inline-flex items-center px-3 py-1 rounded-md text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Results
                      </button>
                    </transition>
                  </div>

                  <transition enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-y-4" enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-4">
                    <div v-if="testResults" class="bg-white rounded-lg border shadow-sm overflow-hidden">
                      <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                        <div class="flex items-center justify-between">
                          <h4 class="text-sm font-medium text-gray-900">Evaluation Results</h4>
                          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="[
                            testResults.score >= 70
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          ]">
                            Score: {{ testResults.score }}/100
                          </span>
                        </div>
                      </div>

                      <div class="px-4 py-3 space-y-4">
              
                        <div>
                          <h5 class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Feedback</h5>
                          <p class="text-sm text-gray-600">{{ testResults.feedback }}</p>
                        </div>
                        <div v-if="testResults.criteria?.length">
                          <h5 class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Evaluation Criteria
                          </h5>
                          <ul class="space-y-2">
                            <li v-for="(criterion, index) in testResults.criteria" :key="index"
                              class="flex items-start text-sm">
                              <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                              </svg>
                              <span class="text-gray-600">{{ criterion }}</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </transition>
                </div> -->
              </div>
            </template>

            <!-- Validation Message -->
            <div v-if="!isCurrentQuestionAnswered" class="text-sm text-red-500 mt-2">
              {{ t.pleaseAnswer }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
      <div class="max-w-3xl mx-auto flex justify-between items-center">
        <button @click="previousQuestion" :disabled="currentQuestionIndex === 0"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium rounded-md border border-gray-300 shadow-sm"
          :class="[
            currentQuestionIndex === 0
              ? 'bg-gray-50 text-gray-400 cursor-not-allowed'
              : 'bg-white text-indigo-600 hover:bg-indigo-50'
          ]">
          <ArrowLeftIcon class="h-5 w-5 mr-2" />
          {{ t.previous }}
        </button>

        <button v-if="currentQuestionIndex < questions.length - 1" @click="nextQuestion"
          :disabled="!isCurrentQuestionAnswered"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium rounded-md shadow-sm transition-all" :class="[
            isCurrentQuestionAnswered
              ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white'
              : 'bg-gray-100 text-gray-400 cursor-not-allowed'
          ]">
          {{ t.next }}
          <ArrowRightIcon class="h-5 w-5 ml-2" />
        </button>

        <button v-else @click="submitExam" :disabled="!isCurrentQuestionAnswered"
          class="inline-flex items-center px-6 py-2.5 text-sm font-medium rounded-md shadow-sm transition-all" :class="[
            isCurrentQuestionAnswered
              ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white'
              : 'bg-gray-100 text-gray-400 cursor-not-allowed'
          ]">
          {{ t.submit }}
        </button>
      </div>
    </div>

    <!-- Modals -->
    <div v-if="showExitModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ t.exitExam }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    {{ t.exitConfirm }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"></div>
          <button @click="exit"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">
            {{ t.exit }}
          </button>
          <button @click="showExitModal = false"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            {{ t.cancel }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Time's Up Modal -->
  <div v-if="showTimeoutModal" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div
              class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 sm:mx-0 sm:h-10 sm:w-10">
              <ClockIcon class="h-6 w-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ t.timesUp }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  {{ t.examExpired }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button @click="submitExam"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">
            {{ t.submitExam }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <AssessmentResultModal :is-open="showResults" :results="examResults" @close="handleModalClose"
    @download-certificate="handleDownloadCertificate" />

  <!-- Loading Dialog -->
  <div v-if="isSubmitting" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

      <div class="relative bg-white rounded-lg px-8 py-6 shadow-xl transform transition-all">
        <div class="flex items-center space-x-4">
          <svg class="animate-spin h-6 w-6 text-gradient-to-r from-indigo-600 to-purple-600"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <span class="text-lg font-medium text-gray-900">{{ t.submitting }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirm Submit Modal -->
  <div v-if="showConfirmSubmitModal" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div
              class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 sm:mx-0 sm:h-10 sm:w-10">
              <ExclamationTriangleIcon class="h-6 w-6 text-yellow-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ t.unansweredQuestions }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  {{ t.unansweredCount.replace('{count}', unansweredCount).replace('{plural}', unansweredCount === 1 ? 'question' : 'questions') }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button @click="handleConfirmSubmit(true)"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">
            {{ t.submitAnyway }}
          </button>
          <button @click="handleConfirmSubmit(false)"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            {{ t.goBack }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Submit Before Exit Modal -->
  <div v-if="showSubmitBeforeExitModal" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div
              class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 sm:mx-0 sm:h-10 sm:w-10">
              <ExclamationTriangleIcon class="h-6 w-6 text-indigo-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg font-medium text-gray-900">
                {{ t.submitBeforeExit }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  {{ t.timeRemaining }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                  {{ t.exitNote }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
          <!-- <button @click="handleExitSubmit(true)"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all">
            {{ t.submitAndExit }}
          </button> -->
          <button @click="showSubmitBeforeExitModal = false"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium  bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
            {{ t.cancel }}
          </button>
          <button @click="handleExitSubmit(false)"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
            {{ t.exitWithoutSubmit }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import AssessmentResultModal from '../../Components/AssessmentResultModal.vue'
import { onBeforeRouteLeave } from 'vue-router'
// Add new import
import * as monaco from 'monaco-editor-vue3'
const MonacoEditor = monaco.default

const route = useRoute()
const router = useRouter()
const toast = useToast()

const exam = ref(null)
const questions = ref([])
const currentQuestionIndex = ref(0)
const selectedAnswers = ref({})
const timeLeft = ref(0)
const showExitModal = ref(false)
const showResults = ref(false)
const examResults = ref(null)
const showTimeoutModal = ref(false)
const examStartTime = ref(null)
let timer = null
const isSubmitting = ref(false)
const showConfirmSubmitModal = ref(false)
const unansweredCount = ref(0)
const showSubmitBeforeExitModal = ref(false)
const exitAfterSubmit = ref(false)

const currentQuestion = computed(() => {
  // Add null checks
  if (!questions.value || !questions.value.length) return null
  return questions.value[currentQuestionIndex.value] || null
})

const isOptionSelected = (optionIndex) => {
  // Add null check
  if (!currentQuestion.value || !currentQuestion.value.id) return false
  
  const answer = selectedAnswers.value[currentQuestion.value.id]
  if (currentQuestion.value.type === 'multiple') {
    return Array.isArray(answer) && answer.includes(optionIndex)
  }
  return answer === optionIndex
}

const formatTime = (seconds) => {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const remainingSeconds = seconds % 60
  return `${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(remainingSeconds).padStart(2, '0')}`
}

const startTimer = () => {
  timer = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
      if (timeLeft.value === 0) {
        handleTimeout()
      }
    }
  }, 1000)
}

const loadExam = async () => {
  try {
    const response = await axios.get(`/exams/${route.params.id}/attempt/${route.params.attemptId || ''}`)
    exam.value = response.data.exam
    questions.value = response.data.questions

    // Initialize answers for all questions
    questions.value.forEach(question => {
      initializeAnswer(question)
    })

    // Get exam start time from server or localStorage
    const storedStartTime = localStorage.getItem(`exam_${route.params.attemptId}_start_time`)
    examStartTime.value = storedStartTime ? parseInt(storedStartTime) : Date.now()

    // Calculate remaining time
    const totalDuration = exam.value.duration * 60
    const elapsedSeconds = Math.floor((Date.now() - examStartTime.value) / 1000)
    timeLeft.value = Math.max(0, totalDuration - elapsedSeconds)

    // Store start time if not already stored
    if (!storedStartTime) {
      localStorage.setItem(`exam_${route.params.attemptId}_start_time`, examStartTime.value.toString())
    }

    if (timeLeft.value > 0) {
      startTimer()
    } else {
      showTimeoutModal.value = true
    }
  } catch (error) {
    console.error('Error loading exam:', error)
    // toast.error('Failed to load exam')
  }
}

// Add editor options after the existing const declarations
const editorOptions = {
  minimap: { enabled: false },
  scrollBeyondLastLine: false,
  fontSize: 14,
  lineNumbers: 'on',
  wordWrap: 'on',
  theme: 'vs-light',
  padding: { top: 16, bottom: 16 }
}

// Add new computed property to check if all prompts are answered
const hasUnansweredPrompts = computed(() => {
  return questions.value.some(question => {
    if (question.type === 'prompt') {
      const answer = selectedAnswers.value[question.id]
      return !answer || answer.trim().length === 0
    }
    return false
  })
})

// Update isCurrentQuestionAnswered computed property
const isCurrentQuestionAnswered = computed(() => {
  const question = currentQuestion.value
  // Add null check
  if (!question || !question.id) return false

  const answer = selectedAnswers.value[question.id]

  switch (question.type) {
    case 'prompt':
      return answer && answer.trim().length > 0
    case 'multiple_choice':
    case 'multiple':
      return Array.isArray(answer) && answer.length > 0
    case 'single_choice':
    case 'single':
    default:
      return answer !== null && answer !== undefined
  }
})

// Replace the existing initializeAnswer function
const initializeAnswer = (question) => {
  if (!selectedAnswers.value[question.id]) {
    switch (question.type) {
      case 'prompt':
        selectedAnswers.value[question.id] = ''
        break
      case 'multiple_choice':
      case 'multiple':
        selectedAnswers.value[question.id] = []
        break
      case 'single_choice':
      case 'single':
      default:
        selectedAnswers.value[question.id] = null
        break
    }
  }
}

const nextQuestion = () => {
  if (!isCurrentQuestionAnswered.value) {
    toast.warning('Please select an answer before proceeding to the next question.')
    return
  }

  if (currentQuestionIndex.value < questions.value.length - 1) {
    resetEditor()
    resetTestResults() // Add this line
    currentQuestionIndex.value++
  }
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    resetEditor()
    resetTestResults() // Add this line
    currentQuestionIndex.value--
  }
}

// Add reset editor function
const resetEditor = () => {
  shouldShowEditor.value = false
  nextTick(() => {
    shouldShowEditor.value = true
  })
}

/**
 * Submit exam
 *  
 * @returns {void}
 */
const MIN_LOADING_TIME = 1000 // 1 second minimum loading time

const submitExamRequest = async () => {
  const startTime = Date.now()

  try {
    const response = await axios.post(`/exams/attempts/${route.params.attemptId}/submit`, {
      answers: formattedAnswers.value,
      attempt_id: route.params.attemptId,
      remaining_time: timeLeft.value // Add remaining time to request
    })

    // Ensure minimum loading time
    const elapsedTime = Date.now() - startTime
    if (elapsedTime < MIN_LOADING_TIME) {
      await new Promise(resolve => setTimeout(resolve, MIN_LOADING_TIME - elapsedTime))
    }

    handleExamSubmission(response.data)
  } catch (error) {
    throw error
  }
}

const checkUnansweredQuestions = () => {
  return questions.value.filter(question => {
    const answer = selectedAnswers.value[question.id]
    
    switch (question.type) {
      case 'prompt':
        return !answer || answer.trim().length === 0
      case 'multiple_choice':
      case 'multiple':
        return !Array.isArray(answer) || answer.length === 0
      case 'single_choice':
      case 'single':
      default:
        return answer === null || answer === undefined
    }
  })
}

// Update submitExam function
const submitExam = async () => {
  if (hasUnansweredPrompts.value) {
    const unansweredCount = questions.value.filter(q => {
      if (q.type === 'prompt') {
        const answer = selectedAnswers.value[q.id]
        return !answer || answer.trim().length === 0
      }
      return false
    }).length

    toast.error(t.value.unansweredCount.replace(
      '{count}', 
      unansweredCount
    ).replace(
      '{plural}', 
      unansweredCount === 1 ? t.value.question.toLowerCase() : t.value.questions.toLowerCase()
    ))
    return
  }

  try {
    // Stop timer immediately when submitting
    if (timer) {
      clearInterval(timer)
      timer = null
    }

    isSubmitting.value = true
    await submitExamRequest()
  } catch (error) {
    console.error('Submit error:', error)
    toast.error(t.value.submitError)
    
    // Restart timer if submission fails
    if (!timer && timeLeft.value > 0) {
      startTimer()
    }
  } finally {
    isSubmitting.value = false
  }
}

const confirmExit = () => {
  showSubmitBeforeExitModal.value = true;
}

const exit = async () => {
  if (timeLeft.value > 0) {
    showSubmitBeforeExitModal.value = true
  } else {
    router.push({ name: 'exams.index' })
  }
}

const handleExitSubmit = (shouldSubmit) => {
  showSubmitBeforeExitModal.value = false;

  if (shouldSubmit) {
    // Check if all questions are answered
    const unansweredQuestions = questions.value.filter(question => {
      const answer = selectedAnswers.value[question.id]
      return question.type === 'multiple'
        ? !Array.isArray(answer) || answer.length === 0
        : answer === null || answer === undefined
    });

    if (unansweredQuestions.length > 0) {
      toast.error(`You have ${unansweredQuestions.length} unanswered questions. Please answer all questions before submitting.`);
      return;
    }

    try {
      isSubmitting.value = true;
      submitExamRequest();
    } catch (error) {
      console.error('Error submitting exam:', error);
      toast.error('Failed to submit exam');
      isSubmitting.value = false;
    }
  } else {
    // Clear timer before exit
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    // Set flag to allow navigation
    examResults.value = true;

    // Exit without submitting - navigate away immediately
    router.push({
      name: 'exams.show',
      params: { id: route.params.id }
    });
  }
};

const handleExamSubmission = (results) => {
  examResults.value = results;
  
  if (timer) {
    clearInterval(timer);
  }

  // Navigate based on context
  if (exitAfterSubmit.value) {
    router.push({
      name: 'exams.show',
      params: { id: route.params.id },
      query: { attemptId: route.params.attemptId }
    });
  } else {
    showResults.value = true;
  }
};

const handleDownloadCertificate = async () => {
  // Your download logic here
}

const handleModalClose = () => {
  showResults.value = false
  // Navigate back to exam details page
  router.push({
    name: 'exams.show',
    params: { id: exam.value.id }
  })
}

const handleMultipleChoice = (optionIndex) => {
  const questionId = currentQuestion.value.id
  if (!selectedAnswers.value[questionId]) {
    selectedAnswers.value[questionId] = []
  }

  const index = selectedAnswers.value[questionId].indexOf(optionIndex)
  if (index === -1) {
    selectedAnswers.value[questionId].push(optionIndex)
  } else {
    selectedAnswers.value[questionId].splice(index, 1)
  }
}

const handleTimeout = () => {
  clearInterval(timer)
  showTimeoutModal.value = true
}

const handleConfirmSubmit = async (confirm) => {
  // Close the confirmation modal immediately
  showConfirmSubmitModal.value = false

  try {
    if (confirm) {
      isSubmitting.value = true
      await submitExamRequest()
    }
  } catch (error) {
    console.error('Error in confirm submit:', error)
    toast.error('Failed to submit exam')
  } finally {
    isSubmitting.value = false
  }
}

const formattedAnswers = computed(() => {
  return Object.keys(selectedAnswers.value).reduce((acc, questionId) => {
    const question = questions.value.find(q => q.id === parseInt(questionId))
    const answer = selectedAnswers.value[questionId]

    if (!question) return acc

    switch (question.type) {
      case 'prompt':
        // For prompt questions, send the answer text directly
        if (answer && answer.trim()) {
          acc[questionId] = answer.trim()
        }
        break

      case 'single_choice':
      case 'single':
        // For single choice, get the option ID at the selected index
        if (answer !== null && answer !== undefined && question.options[answer]) {
          acc[questionId] = question.options[answer].id
        }
        break

      case 'multiple_choice':
      case 'multiple':
        // For multiple choice, map selected indices to option IDs
        if (Array.isArray(answer) && answer.length > 0) {
          acc[questionId] = answer.map(index => question.options[index].id)
        }
        break
    }

    return acc
  }, {})
})

const handleBeforeRouteLeave = (to, from, next) => {
  // Always allow navigation if exam is submitted, time is up, or we're exiting without submit
  if (examResults.value || timeLeft.value === 0) {
    next();
    return;
  }

  // Show modal if we have time left and not already exiting
  if (timeLeft.value > 0 && !isSubmitting.value && !showSubmitBeforeExitModal.value) {
    showSubmitBeforeExitModal.value = true;
    next(false);
    return;
  }

  next();
};

// Add new ref for editor visibility
const shouldShowEditor = ref(true)

// Add editor change handler
const handleEditorChange = (value) => {
  selectedAnswers.value[currentQuestion.value.id] = value
}

// Add new state
const isTestingPrompt = ref(false)
const testResults = ref(null)

// Add test prompt function
const testPrompt = async () => {
  if (!selectedAnswers.value[currentQuestion.value.id]) return

  try {
    isTestingPrompt.value = true
    const response = await axios.post('/exams/test-prompt', {
      question_id: currentQuestion.value.id,
      answer: selectedAnswers.value[currentQuestion.value.id]
    })

    testResults.value = response.data
  } catch (error) {
    console.error('Error testing prompt:', error)
    toast.error('Failed to test prompt')
  } finally {
    isTestingPrompt.value = false
  }
}

// Add to existing script setup
const resetTestResults = () => {
  testResults.value = null
}

onMounted(() => {
  loadExam()
})

onUnmounted(() => {
  if (timer) {
    clearInterval(timer)
  }
})

onBeforeRouteLeave(handleBeforeRouteLeave)

// Add language utilities
const getTranslations = (language = 'en') => {
  return {
    en: {
      timeRemaining: "Time Remaining",
      exit: "Exit",
      question: "Question",
      multipleChoice: "Multiple Choice",
      singleChoice: "Single Choice",
      prompt: "Prompt",
      requirements: "Requirements",
      yourAnswer: "Your Answer",
      markdownSupported: "Markdown supported",
      characters: "Characters",
      suggestions: "Press Ctrl+Space for suggestions",
      pleaseAnswer: "Please select an answer before proceeding",
      previous: "Previous",
      next: "Next",
      submit: "Submit",
      exitExam: "Exit Exam",
      exitConfirm: "Are you sure you want to exit? Your progress will be saved, but the exam timer will continue running.",
      cancel: "Cancel",
      timesUp: "Time's Up!",
      examExpired: "The exam time has expired. Your answers will be automatically submitted.",
      submitExam: "Submit Exam",
      submitting: "Submitting your exam...",
      unansweredQuestions: "Unanswered Questions",
      unansweredCount: "You have {count} unanswered {plural}. Are you sure you want to submit anyway?",
      submitAnyway: "Submit Anyway",
      goBack: "Go Back",
      submitBeforeExit: "Submit Exam?",
      timeRemaining: "You still have time remaining. Would you like to submit your exam now?",
      exitNote: "Note: Exiting without submitting will save your progress but the timer will continue.",
      submitAndExit: "Submit & Exit",
      exitWithoutSubmit: "Exit Without Submitting",
      promptRequired: "Please provide an answer for all prompt questions before submitting",
      question: "Question",
      questions: "Questions",
      submitError: "Failed to submit exam. Please try again.",
    },
    ja: {
      timeRemaining: "残り時間",
      exit: "終了",
      question: "問題",
      multipleChoice: "複数選択",
      singleChoice: "単一選択",
      prompt: "プロンプト",
      requirements: "要件",
      yourAnswer: "あなたの回答",
      markdownSupported: "Markdown対応",
      characters: "文字数",
      suggestions: "Ctrl+Spaceでサジェスト",
      pleaseAnswer: "次に進む前に回答を選択してください",
      previous: "前へ",
      next: "次へ",
      submit: "提出",
      // ... add other Japanese translations
      promptRequired: "提出前に、すべてのプロンプト問題に回答してください",
      question: "問題",
      questions: "問題",
      submitError: "提出に失敗しました。もう一度お試しください。",
    },
    vi: {
      timeRemaining: "Thời gian còn lại",
      exit: "Thoát",
      question: "Câu hỏi",
      multipleChoice: "Nhiều lựa chọn",
      singleChoice: "Một lựa chọn",
      prompt: "Câu hỏi tự luận",
      requirements: "Yêu cầu",
      yourAnswer: "Câu trả lời của bạn",
      markdownSupported: "Hỗ trợ Markdown",
      characters: "Số ký tự",
      suggestions: "Nhấn Ctrl+Space để gợi ý",
      pleaseAnswer: "Vui lòng chọn câu trả lời trước khi tiếp tục",
      previous: "Trước",
      next: "Tiếp theo",
      submit: "Nộp bài",
      // ... add other Vietnamese translations
      promptRequired: "Vui lòng trả lời tất cả các câu hỏi tự luận trước khi nộp bài",
      question: "câu hỏi",
      questions: "câu hỏi",
      submitError: "Không thể nộp bài. Vui lòng thử lại.",
    }
  }[language]
}

// Add computed for translations
const t = computed(() => getTranslations(exam.value?.language))
</script>