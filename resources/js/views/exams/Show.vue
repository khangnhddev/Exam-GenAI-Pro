<template>
  <!-- Hero Section - Full Width Background -->
  <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-lg"> <!-- Changed rounded-b-[40px] to rounded-[40px] -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex flex-col md:flex-row justify-between items-start gap-8">
        <!-- Left Content -->
        <div class="space-y-6 text-white max-w-2xl">
          <!-- Badge -->
          <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full">
            <svg 
              width="20" 
              height="20" 
              viewBox="0 0 25 25" 
              fill="none" 
              xmlns="http://www.w3.org/2000/svg" 
              class="text-white"
            >
              <g clip-path="url(#assessmentsSkillIcon)" fill="currentColor">
                <circle cx="10.604" cy="14.857" r="2.384"></circle>
                <path d="m19.76 10.158 4.254-5.07a.2.2 0 0 0-.167-.329l-4.036.274a.2.2 0 0 1-.212-.178L19.168.832a.2.2 0 0 0-.352-.107L14.56 5.796a.2.2 0 0 0-.038.188l1.13 3.621a.2.2 0 0 0 .165.139l3.763.484a.2.2 0 0 0 .178-.07Z"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="m9.904 15.286 5.982-7.13.766.644-5.982 7.129-.766-.643Z"></path>
                <path d="M11.448 5.896a9 9 0 1 0 7.88 6.745l-2.215.562a6.716 6.716 0 1 1-5.88-5.033l.215-2.274Z"></path>
              </g>
              <defs>
                <clipPath id="assessmentsSkillIcon">
                  <path fill="currentColor" transform="translate(.604 .365)" d="M0 0h24v24H0z"></path>
                </clipPath>
              </defs>
            </svg>
            <span class="text-sm font-medium tracking-wider">SKILL ASSESSMENT</span>
          </div>

          <!-- Title & Description -->
          <div ref="examTitleRef" class="space-y-4">
            <h1 class="text-4xl font-bold">{{ exam.title }}</h1>
            <p class="text-white/80 text-lg leading-relaxed">{{ exam.description }}</p>
          </div>

          <!-- Quick Stats -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-white/60 text-sm mb-1">Duration</div>
              <div class="text-xl font-semibold">{{ exam.duration }}m</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-white/60 text-sm mb-1">Questions</div>
              <div class="text-xl font-semibold">{{ exam.total_questions }}</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-white/60 text-sm mb-1">Pass Score</div>
              <div class="text-xl font-semibold">{{ exam.passing_score }}%</div>
            </div>
            <div v-if="authStore.isAuthenticated" class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-white/60 text-sm mb-1">Attempts</div>
              <div class="text-xl font-semibold">
                {{ userAttempts?.length }}
              </div>
            </div>
          </div>
        </div>

        <!-- Right Content - Progress Circle (if attempted) -->
        <div v-if="authStore.isAuthenticated && hasAttempted" class="w-80 md:w-96">
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8"> <!-- Changed rounded-xl to rounded-2xl -->
            <div class="relative w-48 h-48 mx-auto overflow-hidden"> <!-- Added overflow-hidden -->
              <!-- Background Circle -->
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <!-- Added rounded corners with stroke-linecap -->
                <circle 
                  cx="50" 
                  cy="50" 
                  r="45" 
                  fill="none" 
                  stroke="rgba(255,255,255,0.2)" 
                  stroke-width="8"
                  stroke-linecap="round"
                />
                <!-- Progress Circle -->
                <circle 
                  cx="50" 
                  cy="50" 
                  r="45" 
                  fill="none" 
                  stroke="white"
                  stroke-width="8" 
                  :stroke-dasharray="`${bestScore * 2.83}, 283`" 
                  stroke-linecap="round"
                />
              </svg>
              <!-- Center Text -->
              <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white">
                <span class="text-4xl font-bold">{{ bestScore }}%</span>
                <span class="text-sm opacity-80">Best Score</span>
                <span :class="[
                  'mt-2 px-3 py-1 text-sm font-medium rounded-full',
                  bestScore >= exam.passing_score 
                    ? 'bg-green-400/20 text-green-100' 
                    : 'bg-red-400/20 text-red-100'
                ]">
                  {{ bestScore >= exam.passing_score ? 'PASSED' : 'FAILED' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-[2000px] mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center min-h-[400px]">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Section -->
        <div class="lg:col-span-2">
          <!-- Status Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Assessment Card -->
            <div v-if="authStore.isAuthenticated" class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Assessment</h3>
                  <p class="text-sm text-gray-500">Test your knowledge</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Questions:</span>
                  <span class="font-medium">{{ exam.total_questions }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Time Limit:</span>
                  <span class="font-medium">{{ exam.duration }} mins</span>
                </div>
              </div>
            </div>

            <!-- Certificate Card -->
            <div v-if="authStore.isAuthenticated" class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Certificate</h3>
                  <p class="text-sm text-gray-500">Upon completion</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Passing Score:</span>
                  <span class="font-medium">{{ exam.passing_score }}%</span>
                </div>
                <div v-if="authStore.isAuthenticated" class="flex justify-between text-sm">
                  <span class="text-gray-500">Attempts:</span>
                  <span class="font-medium">{{ userAttempts?.length }}</span>
                </div>
              </div>
            </div>

            <!-- Status Card -->
            <div v-if="authStore.isAuthenticated" class="bg-white rounded-lg shadow-sm p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Your Status</h3>
                  <p class="text-sm text-gray-500">Current progress</p>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Best Score:</span>
                  <span class="font-medium">{{ bestScore }}%</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span :class="[
                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                    hasPassed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]">
                    {{ hasPassed ? 'PASSED' : 'NOT COMPLETED' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Take/Retake Exam Section - Moved Here -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex flex-col items-center text-center max-w-xl mx-auto">
              <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                  {{ !hasAttempted ? 'Ready to Start?' : hasPassed ? 'Congratulations!' : 'Try Again?' }}
                </h3>
                <p class="text-gray-600">
                  {{ !hasAttempted 
                    ? 'Begin your assessment to test your knowledge and earn a certificate.' 
                    : hasPassed
                      ? 'You\'ve successfully passed this assessment. View your certificate in the sidebar.'
                      : 'Keep trying to achieve a passing score and earn your certificate.'
                  }}
                </p>
              </div>

              <!-- Only show button if not passed -->
              <div v-if="!hasPassed" class="flex flex-col items-center gap-3">
                <button 
                  @click="handleExamStart"
                  class="px-8 py-3 text-base font-medium rounded-lg transition-all duration-200 transform hover:scale-[1.02] bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700"
                >
                  {{ !authStore.isAuthenticated ? 'Take Exam' : !hasAttempted ? 'Take Exam' : 'Retake Exam' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Assessment Overview with Success Banner -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600">This skill assessment covers the concepts of {{ exam.title }} and will help you
              identify gaps in your understanding of major concepts. It will further help you gauge your current level
              of understanding for leveling up your skills.</p>
          </div>

          <!-- Topics Covered -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Topics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="(topic, index) in exam.topics" :key="index" class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700">{{ topic }}</span>
              </div>
            </div>
          </div>
         
          <!-- Exam Details -->
          <!-- <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Duration: {{ exam.duration }} minutes</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Passing Score: {{ exam.passing_score }}%</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                  <span class="ml-2">Attempts Allowed: {{ exam.attempts_allowed }}</span>
                </div>
              </div>

              <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">Total Questions: {{ exam.total_questions }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <span class="ml-2">Your Attempts: {{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</span>
                </div>
              </div>
            </div>
          </div> -->

          <!-- Previous Attempts -->
          <div v-if="authStore.isAuthenticated" class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Attempts History</h2>
            <div class="overflow-x-auto">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">DATE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SCORE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">SKILL LEVEL</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">STATUS</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">ACTIONS</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="!formattedAttempts.length" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-sm text-gray-500 text-center">
                      No attempts yet
                    </td>
                  </tr>
                  <tr v-for="attempt in formattedAttempts" :key="attempt.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                      {{ attempt.attempt_number }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ attempt.formattedDate }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                      {{ attempt.score }}%
                    </td>
                    <td class="px-6 py-4">
                      <span :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        attempt.skillLevel.class
                      ]">
                        {{ attempt.skillLevel.label }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        attempt.score >= exam.passing_score
                          ? 'bg-green-100 text-green-800'
                          : 'bg-red-100 text-red-800'
                      ]">
                        {{ attempt.score >= exam.passing_score ? 'PASSED' : 'FAILED' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                      <button 
                        @click="router.push({
                          name: 'exams.attempt.review',
                          params: {
                            examId: exam.id,
                            attemptId: attempt.id
                          }
                        })"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Review
                      </button>
                      <!-- <button v-if="attempt.score >= exam.passing_score" @click="downloadCertificate(attempt.id)"
                        class="ml-4 text-indigo-600 hover:text-indigo-900">
                        Certificate
                      </button> -->
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 ml-10">
          <div class="sticky top-8 space-y-6">
            <!-- Show this for unauthenticated users -->
            <div v-if="!authStore.isAuthenticated" class="space-y-6">
              <!-- Login to Start Card -->
              <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                <div class="flex flex-col items-center text-center">
                  <div class="p-3 bg-white rounded-full shadow-sm mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Login to Start</h3>
                  <p class="text-sm text-gray-600 mb-4">
                    Create an account or log in to track your progress, earn certificates, and access exclusive features.
                  </p>
                  <button 
                    @click="showLoginDialog = true"
                    class="w-full py-2 px-4 rounded-lg text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
                  >
                    Sign In to Continue
                  </button>
                </div>
              </div>

              <!-- Benefits Card -->
              <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Why Create an Account?</h3>
                <ul class="space-y-3">
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-gray-600">Track your progress across multiple attempts</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-gray-600">Earn verified certificates upon completion</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-gray-600">Access detailed performance analytics</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-gray-600">Share achievements on your professional network</span>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Existing authenticated user content -->
            <div v-else>
              <!-- Certificate Card -->
              <div class="flex flex-col items-center text-center p-8">
                <!-- Certificate Status -->
                <div class="relative w-full mb-8">
                  <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                    <!-- Certificate Icon & Status -->
                    <div class="flex flex-col items-center">
                      <div class="relative mb-4">
                        <div :class="[
                          'w-16 h-16 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center transform transition-all duration-500',
                          hasPassed ? 'scale-100' : 'scale-95 opacity-50'
                        ]">
                          <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                          </svg>
                        </div>
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-indigo-500 opacity-20 blur-xl rounded-full transform scale-150 -z-10"></div>
                      </div>

                      <h3 class="text-xl font-bold text-gray-900 mb-2">
                        {{ hasPassed ? 'Professional Certificate Unlocked! 🎉' : 'Professional Certificate Locked' }}
                      </h3>
                      <p class="text-sm text-gray-600 mb-4">
                        {{ hasPassed
                          ? 'Congratulations! You\'ve demonstrated professional proficiency in this skill assessment. Your dedication has earned you our verified certificate, validating your expertise in this domain.'
                          : 'Complete this assessment with a passing score to earn your professional certificate. This credential validates your expertise and can be shared with your professional network.'
                        }}
                      </p>
                      <p class="text-sm text-gray-500 mb-6">
                        {{ hasPassed
                          ? 'Your certificate includes a unique verification ID and can be shared on LinkedIn or added to your professional portfolio.'
                          : 'Our certificates are recognized by industry professionals and demonstrate your commitment to continuous learning and skill development.'
                        }}
                      </p>

                      <!-- View Certificate Button - Only show if passed -->
                      <button 
                        v-if="hasPassed"
                        @click="router.push(`/certificates/${certificateId}`)"
                        class="w-full max-w-[240px] py-2.5 px-4 rounded-lg font-medium bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
                      >
                        View Your Certificate
                      </button>
                    </div>
                  </div>

                  <!-- Lock Overlay -->
                  <div v-if="!hasPassed" 
                    class="absolute inset-0 flex items-center justify-center backdrop-blur-[1px] rounded-2xl transition-all duration-500"
                  >
                    <div class="p-3 rounded-full bg-black/40">
                      <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.5" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ExamResultModal :is-open="!!examResults" :results="examResults" @close="closeResults"
    @download-certificate="downloadCertificate" />

  <!-- Add LoginDialog -->
  <LoginDialog 
    :is-open="showLoginDialog"
    @close="showLoginDialog = false"
    @success="onLoginSuccess"
  />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import ExamResultModal from '@/Components/ExamResultModal.vue'
import LoginDialog from '@/Components/LoginDialog.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()
const loading = ref(true)
const exam = ref({
  title: '',
  description: '',
  duration: 0,
  passing_score: 0,
  attempts_allowed: 0,
  total_questions: 0,
  topics: [
    'Basics of Classes',
    'Inheritance',
    'Composition',
    'Polymorphism',
    'Aggregation',
    'Association'
  ]
})
const userAttempts = ref([])
// Initialize attemptHistory with an empty array
const attemptHistory = ref([])
const examResults = ref(null)
const examTitleRef = ref(null)

const showLoginDialog = ref(false)

// Add ref for storing return path
const returnPath = ref(null)

const canAttempt = computed(() => {
  if (!exam.value || !userAttempts.value) return false
  return true // Always allow attempts
})

const hasAttempted = computed(() => {
  return userAttempts.value?.length > 0
})

const hasPassed = computed(() => {
  if (!userAttempts.value?.length) return false
  return userAttempts.value.some(attempt => attempt.score >= exam.value.passing_score)
})

// Add null check in computed property
const formattedAttempts = computed(() => {
  if (!attemptHistory.value) return []

  return attemptHistory.value.map(attempt => ({
    ...attempt,
    skillLevel: getSkillLevel(attempt.score),
    formattedDate: new Date(attempt.created_at).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }))
})

function getSkillLevel(score) {
  if (score >= 90) return { label: 'Expert', class: 'bg-green-100 text-green-800' }
  if (score >= 75) return { label: 'Advanced', class: 'bg-blue-100 text-blue-800' }
  if (score >= 60) return { label: 'Intermediate', class: 'bg-yellow-100 text-yellow-800' }
  return { label: 'Beginner', class: 'bg-orange-100 text-orange-800' }
}

/**
 * Load exam
 * 
 * @returns {void}
 */
// Update the loadExam function with better error handling
const loadExam = async () => {
  const examId = route.params.id;
  if (!examId) {
    console.error('No exam ID found');
    toast.error('Invalid exam ID');
    return;
  }

  try {
    loading.value = true;
    
    // Get public exam data first
    const examResponse = await axios.get(`/exams/${examId}/public`);
    exam.value = examResponse.data.data || {};

    // If user is authenticated, get additional data
    if (authStore.isAuthenticated) {
      const [attemptsResponse, historyResponse] = await Promise.all([
        axios.get(`/exams/${examId}`),
        axios.get(`/exams/${examId}/attempts`)
      ]);
      
      exam.value = attemptsResponse.data.data || {};
      attemptHistory.value = historyResponse.data.data || [];
      userAttempts.value = attemptsResponse.data.data?.attempts || [];
    }
  } catch (error) {
    console.error('Error loading exam:', error);
    toast.error('Failed to load exam data');
  } finally {
    loading.value = false;
  }
}

/**
 * Start exam
 * 
 * @returns {void}
 */
const startExam = async () => {
  try {
    const examId = route.params.id || returnPath.value?.examId
    if (!examId) {
      throw new Error('Exam ID not found')
    }

    const { data } = await axios.post(`/exams/${examId}/start`)
    
    router.push({
      name: 'exams.attempt',
      params: {
        id: examId,
        attemptId: data.attempt_id
      }
    }).then(() => {
      window.scrollTo(0, 0)
    })
  } catch (error) {
    console.error('Error starting exam:', error)
    toast.error('Failed to start exam')
  }
}

const handleExamStart = () => {
  if (!authStore.isAuthenticated) {
    // Store both exam ID and return path
    returnPath.value = {
      path: router.currentRoute.value.fullPath,
      examId: route.params.id
    };
    showLoginDialog.value = true;
    return;
  }
  startExam();
}

const onLoginSuccess = async () => {
  showLoginDialog.value = false;
  
  try {
    if (returnPath.value) {
      await loadExam();
      
      const path = returnPath.value.path;
      returnPath.value = null; 
      
      await router.replace(path);
    }
  } catch (error) {
    console.error('Error after login:', error);
    toast.error('Failed to load exam data');
  }
}

const downloadCertificate = async (attemptId) => {
  try {
    const response = await axios.get(`/exams/${route.params.id}/certificate`, {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `${exam.value.title}_Certificate.pdf`)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    console.error('Error downloading certificate:', error)
  }
}

/**
 * Fetch exam results
 * 
 * @returns {void}
 */
const fetchExamResults = async () => {
  try {
    if (route.query.attemptId) {
      const response = await axios.get(`/exams/attempts/${route.query.attemptId}/results`)
      examResults.value = response.data
    }
  } catch (error) {
    console.error('Error fetching results:', error)
  }
}

const averageScore = computed(() => {
  if (!userAttempts.value.length) return 0
  const totalScore = userAttempts.value.reduce((acc, attempt) => acc + attempt.score, 0)
  return (totalScore / userAttempts.value.length).toFixed(2)
})

const bestScore = computed(() => {
  if (!userAttempts.value.length) return 0
  return Math.max(...userAttempts.value.map(attempt => attempt.score))
})

const currentSkillLevel = computed(() => {
  if (!userAttempts.value.length) return { label: 'N/A', class: '' }
  const latestAttempt = userAttempts.value[userAttempts.value.length - 1]
  return getSkillLevel(latestAttempt.score)
})

const progressPercentage = computed(() => {
  if (!userAttempts.value.length) return 0
  const latestAttempt = userAttempts.value[userAttempts.value.length - 1]
  return (latestAttempt.score / 100) * 100
})

const closeResults = () => {
  examResults.value = null
  // Remove attemptId from URL
  router.replace({ query: {} })
}

// Add new computed property for certificate ID
const certificateId = computed(() => {
  if (!attemptHistory.value?.length) return null
  const passedAttempt = attemptHistory.value.find(attempt =>
    attempt.score >= exam.value.passing_score && attempt.certificate
  )
  return passedAttempt?.certificate?.id
})

onMounted(() => {
  loadExam()
  fetchExamResults()

  // Check if user has just passed the exam
  if (route.query.passed === 'true') {
    // Remove the query parameter
    router.replace({ query: {} })

    // Scroll to exam title section
    setTimeout(() => {
      examTitleRef.value?.scrollIntoView({ behavior: 'smooth', block: 'center' })
      toast.success('Congratulations! You have passed the exam. You can now view your certificate.', {
        timeout: 5000
      })
    }, 500)
  }
})
</script>