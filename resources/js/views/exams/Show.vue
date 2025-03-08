<template>
  <!-- Hero Section - Full Width -->
  <div class="bg-gradient-to-br from-indigo-600 to-purple-600 w-full rounded-xl">
    <div class="max-w-[2000px] mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Content -->
        <div class="text-white p-12 lg:pl-24">

          <h1 class="text-4xl font-bold mb-4">{{ exam.title }}</h1>
          <p class="text-lg text-indigo-100 mb-8">{{ exam.description }}</p>

          <!-- Stats Grid -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-2xl font-bold">{{ exam.total_questions }}</div>
              <div class="text-sm text-indigo-200">Questions</div>
            </div>
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-2xl font-bold">{{ exam.duration }}m</div>
              <div class="text-sm text-indigo-200">Duration</div>
            </div>
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-2xl font-bold">{{ exam.passing_score }}%</div>
              <div class="text-sm text-indigo-200">Pass Score</div>
            </div>
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
              <div class="text-2xl font-bold">{{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</div>
              <div class="text-sm text-indigo-200">Attempts</div>
            </div>
          </div>

          <!-- Action Button -->
          <button v-if="canAttempt" @click="startExam"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white shadow-sm transition-all duration-200">
            Start Assessment
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>

        <!-- Right Content - Certificate Preview -->
        <div class="hidden lg:flex items-center justify-end p-12 lg:pr-16">
          <div class="relative w-80">
            <div class="w-80">
              <svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d)">
                  <!-- Cloud -->
                  <path d="M80 100 C80 80, 100 70, 120 80 C120 60, 150 50, 170 70 C190 50, 220 60, 220 90 C240 80, 260 90, 260 110 C260 130, 240 140, 220 130 C220 140, 190 150, 170 140 C150 150, 120 140, 110 130 C90 140, 70 130, 70 110 C70 100, 80 100, 80 100" 
                    fill="white" fill-opacity="0.1" stroke="white" stroke-opacity="0.6" stroke-width="1"/>
                  
                  <!-- Server Stack -->
                  <rect x="160" y="140" width="80" height="20" rx="2" fill="white" fill-opacity="0.2" stroke="white" stroke-opacity="0.6"/>
                  <rect x="160" y="165" width="80" height="20" rx="2" fill="white" fill-opacity="0.2" stroke="white" stroke-opacity="0.6"/>
                  <rect x="160" y="190" width="80" height="20" rx="2" fill="white" fill-opacity="0.2" stroke="white" stroke-opacity="0.6"/>
                  
                  <!-- Server Details -->
                  <circle cx="170" cy="150" r="2" fill="white" fill-opacity="0.8"/>
                  <circle cx="170" cy="175" r="2" fill="white" fill-opacity="0.8"/>
                  <circle cx="170" cy="200" r="2" fill="white" fill-opacity="0.8"/>
                  
                  <!-- Connection Lines -->
                  <path d="M140 110 L160 140" stroke="white" stroke-opacity="0.4" stroke-width="1" stroke-dasharray="2 2"/>
                  <path d="M200 110 L200 140" stroke="white" stroke-opacity="0.4" stroke-width="1" stroke-dasharray="2 2"/>
                  <path d="M260 110 L240 140" stroke="white" stroke-opacity="0.4" stroke-width="1" stroke-dasharray="2 2"/>
                  
                  <!-- Monitor -->
                  <path d="M280 160 L320 140 L320 180 L280 200 Z" fill="white" fill-opacity="0.2" stroke="white" stroke-opacity="0.6"/>
                  <path d="M320 140 L360 160 L360 200 L320 180 Z" fill="white" fill-opacity="0.15" stroke="white" stroke-opacity="0.6"/>
                  
                  <!-- Monitor Stand -->
                  <path d="M310 200 L330 190 L330 210 L310 220 Z" fill="white" fill-opacity="0.3" stroke="white" stroke-opacity="0.6"/>
                  
                  <!-- Connection to Server -->
                  <path d="M240 180 L280 180" stroke="white" stroke-opacity="0.4" stroke-width="1" stroke-dasharray="2 2"/>
                </g>
                <defs>
                  <filter id="filter0_d" x="0" y="0" width="400" height="300" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                    <feOffset dy="4"/>
                    <feGaussianBlur stdDeviation="8"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                  </filter>
                </defs>
              </svg>
            </div>

            <!-- Decorative Elements -->
            <div
              class="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-purple-400 to-indigo-400 rounded-full opacity-50 blur-xl">
            </div>
            <div
              class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full opacity-50 blur-xl">
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
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- Assessment Type Badge -->
          <div class="flex items-center gap-2 mb-6">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="text-sm font-medium text-indigo-600 uppercase tracking-wider">EXAM</span>
          </div>

          <!-- Exam Title and Description -->
          <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ exam.title }}</h1>
          <p class="text-lg text-gray-600 mb-8">{{ exam.description }}</p>

          <!-- Add this section right after the title and description, before Assessment Overview -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Assessment Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
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
            <div class="bg-white rounded-lg shadow-sm p-6">
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
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Attempts:</span>
                  <span class="font-medium">{{ userAttempts?.length || 0 }}/{{ exam.attempts_allowed }}</span>
                </div>
              </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
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

          <!-- Assessment Overview -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Assessment Overview</h2>
            <p class="text-gray-600">This skill assessment covers the concepts of {{ exam.title }} and will help you
              identify gaps in your understanding of major concepts. It will further help you gauge your current level
              of understanding for leveling up your skills.</p>
          </div>

          <!-- Topics Covered -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Topics Covered</h2>
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
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
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
                <!-- <div class="flex items-center text-gray-700">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                  <span class="ml-2">Attempts Allowed: {{ exam.attempts_allowed }}</span>
                </div> -->
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
          </div>

          <!-- Previous Attempts -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
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
                      <button @click="router.push(`/exams/attempts/${attempt.id}/review`)"
                        class="text-indigo-600 hover:text-indigo-900">
                        Review
                      </button>
                      <button v-if="attempt.score >= exam.passing_score" @click="downloadCertificate(attempt.id)"
                        class="ml-4 text-indigo-600 hover:text-indigo-900">
                        Certificate
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-8 space-y-6">
            <!-- Take Assessment Card -->
            <div v-if="!examResults" class="bg-white rounded-xl shadow-sm p-8">
              <div class="flex flex-col items-center text-center">
                <div class="relative flex flex-col items-center justify-center p-8 bg-gradient-to-br from-white/40 to-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-xl">
                  <!-- Certificate Design -->
                  <div class="relative w-48 h-48 mb-6">
                    <!-- Certificate Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-lg border border-white/20 backdrop-blur-sm">
                      <!-- Certificate Content -->
                      <div :class="[
                        'absolute inset-0 flex flex-col items-center justify-center p-4 transition-all duration-500',
                        !hasPassed ? 'opacity-30 filter blur-[2px]' : ''
                      ]">
                        <div class="w-16 h-16 mb-2">
                          <svg class="w-full h-full text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                          </svg>
                        </div>
                        <div class="text-xs font-medium text-indigo-600 uppercase tracking-wider">Certificate of</div>
                        <div class="text-base font-bold bg-gradient-to-br from-indigo-600 to-purple-600 bg-clip-text text-transparent mt-1">Achievement</div>
                      </div>

                      <!-- Lock Overlay -->
                      <div v-if="!hasPassed" 
                        class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-gray-900/70 to-black/70 rounded-xl backdrop-blur-[2px] transition-all duration-500">
                        <svg class="w-12 h-12 text-white/90 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z">
                          </path>
                        </svg>
                        <span class="text-sm font-medium text-white/90">Complete to Unlock</span>
                      </div>
                    </div>
                  </div>

                  <!-- Status Title -->
                  <h3 class="text-lg font-bold bg-gradient-to-br from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    {{ hasPassed ? 'Certificate Unlocked!' : 'Certificate Locked' }}
                  </h3>

                  <!-- Status Description -->
                  <p class="text-sm text-gray-600 text-center mb-6">
                    {{ hasPassed
                      ? 'Congratulations! You can now claim your professional certificate.'
                      : 'Pass this assessment to unlock your professional certificate.'
                    }}
                  </p>

                  <!-- Progress Bar -->
                  <div class="w-full h-2 bg-gray-100 rounded-full mb-4 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-500 rounded-full"
                      :style="`width: ${(userAttempts.value?.length || 0) / exam.attempts_allowed * 100}%`">
                    </div>
                  </div>

                  <!-- Action Button -->
                  <button v-if="canAttempt" @click="hasPassed ? downloadCertificate() : startExam()"
                    class="w-full px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ hasPassed ? 'Download Certificate' : (hasAttempted ? 'Retake Assessment' : 'Start Assessment') }}
                  </button>
                  <div v-else
                    class="w-full px-6 py-3 text-base font-medium text-center text-gray-500 bg-gray-100 rounded-lg cursor-not-allowed">
                    No Attempts Remaining
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import ExamResultModal from '@/Components/ExamResultModal.vue'

const route = useRoute()
const router = useRouter()
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

const canAttempt = computed(() => {
  return true;
  if (!exam.value || !userAttempts.value) return false
  return userAttempts.value.length < exam.value.attempts_allowed
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
  try {
    loading.value = true
    const [examResponse, historyResponse] = await Promise.all([
      axios.get(`/exams/${route.params.id}`),
      axios.get(`/exams/${route.params.id}/attempts`)
    ])

    // Add null checks and default values
    exam.value = examResponse.data.data || {}
    attemptHistory.value = historyResponse.data.data || []
    userAttempts.value = examResponse.data.data?.attempts || []

  } catch (error) {
    console.error('Error loading exam:', error)
    toast.error('Failed to load exam data')
    // Set default values on error
    attemptHistory.value = []
    userAttempts.value = []
  } finally {
    loading.value = false
  }
}

/**
 * Start exam
 * 
 * @returns {void}
 */
const startExam = async () => {
  try {
    const { data } = await axios.post(`/exams/${route.params.id}/start`);

    router.push({
      name: 'exams.attempt',
      params: {
        id: route.params.id,
        attemptId: data.attempt_id
      }
    }).then(() => {
      window.scrollTo(0, 0);
    });
  } catch (error) {
    console.error('Error starting exam:', error)
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

onMounted(() => {
  loadExam()
  fetchExamResults()
})
</script>