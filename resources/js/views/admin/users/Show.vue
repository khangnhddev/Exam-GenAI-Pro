<template>
  <div>
    <!-- Back button -->
    <div class="mb-6">
      <button
        @click="$router.back()"
        class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900"
      >
        <svg 
          class="h-5 w-5 mr-1" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M15 19l-7-7 7-7"
          />
        </svg>
        Back to Users
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <svg class="animate-spin mx-auto h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="mt-2 text-sm text-gray-500">Loading user details...</p>
    </div>

    <div v-else-if="user" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex justify-between">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            User Information
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Personal details and activity
          </p>
        </div>
        <div>
          <button
            @click="showEditModal = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
          </button>
        </div>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Avatar
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <div v-if="user.avatar_url" class="h-20 w-20 rounded-full overflow-hidden">
                <img :src="user.avatar_url" :alt="`${user.name}'s avatar`" class="h-full w-full object-cover">
              </div>
              <div v-else class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                <span class="text-indigo-800 text-2xl font-medium">{{ user.name.charAt(0) }}</span>
              </div>
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Full name
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ user.name }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Email address
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ user.email }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Role
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <span 
                :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'
                ]"
              >
                {{ user.is_admin ? 'Admin' : 'User' }}
              </span>
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Member since
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ formatDate(user.created_at) }}
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- User Activity Section -->
    <div v-if="user" class="mt-8">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        User Activity
      </h3>
      
      <!-- Tabs -->
      <div class="mt-3 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'exams'"
            :class="[
              activeTab === 'exams'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Exams
          </button>
          <button
            @click="activeTab = 'certificates'"
            :class="[
              activeTab === 'certificates'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Certificates
          </button>
        </nav>
      </div>
      
      <!-- Exams Tab Content -->
      <div v-if="activeTab === 'exams'" class="mt-6">
        <div v-if="userActivity.exams.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
          <ul class="divide-y divide-gray-200">
            <li v-for="exam in userActivity.exams" :key="exam.id" class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <div class="sm:flex sm:justify-between w-full">
                  <div>
                    <p class="text-sm font-medium text-indigo-600 truncate">
                      {{ exam.title }}
                    </p>
                    <p class="mt-2 flex items-center text-sm text-gray-500">
                      <span>Attempt date: {{ formatDate(exam.attempt_date) }}</span>
                    </p>
                  </div>
                  <div class="mt-2 sm:mt-0 text-right">
                    <p class="text-sm font-medium text-gray-900">
                      Score: {{ exam.score }}%
                    </p>
                    <p class="mt-2">
                      <span 
                        :class="[
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                          exam.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        ]"
                      >
                        {{ exam.passed ? 'Passed' : 'Failed' }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div v-else class="text-center py-12 bg-white shadow sm:rounded-lg">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No exams taken</h3>
          <p class="mt-1 text-sm text-gray-500">This user hasn't taken any exams yet.</p>
        </div>
      </div>
      
      <!-- Certificates Tab Content -->
      <div v-if="activeTab === 'certificates'" class="mt-6">
        <div v-if="userActivity.certificates.length > 0" class="bg-white shadow overflow-hidden sm:rounded-<template>
  <div>
    <!-- Back button -->
    <div class="mb-6">
      <button
        @click="$router.back()"
        class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900"
      >
        <svg 
          class="h-5 w-5 mr-1" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M15 19l-7-7 7-7"
          />
        </svg>
        Back to Users
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <svg class="animate-spin mx-auto h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="mt-2 text-sm text-gray-500">Loading user details...</p>
    </div>

    <div v-else-if="user" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex justify-between">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            User Information
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Personal details and activity
          </p>
        </div>
        <div>
          <button
            @click="showEditModal = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
          </button>
        </div>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Avatar
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <div v-if="user.avatar_url" class="h-20 w-20 rounded-full overflow-hidden">
                <img :src="user.avatar_url" :alt="`${user.name}'s avatar`" class="h-full w-full object-cover">
              </div>
              <div v-else class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                <span class="text-indigo-800 text-2xl font-medium">{{ user.name.charAt(0) }}</span>
              </div>
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Full name
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ user.name }}
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Email address
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ user.email }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Role
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <span 
                :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'
                ]"
              >
                {{ user.is_admin ? 'Admin' : 'User' }}
              </span>
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Member since
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ formatDate(user.created_at) }}
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- User Activity Section -->
    <div v-if="user" class="mt-8">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        User Activity
      </h3>
      
      <!-- Tabs -->
      <div class="mt-3 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'exams'"
            :class="[
              activeTab === 'exams'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Exams
          </button>
          <button
            @click="activeTab = 'certificates'"
            :class="[
              activeTab === 'certificates'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Certificates
          </button>
        </nav>
      </div>
      
      <!-- Exams Tab Content -->
      <div v-if="activeTab === 'exams'" class="mt-6">
        <div v-if="userActivity.exams.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
          <ul class="divide-y divide-gray-200">
            <li v-for="exam in userActivity.exams" :key="exam.id" class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <div class="sm:flex sm:justify-between w-full">
                  <div>
                    <p class="text-sm font-medium text-indigo-600 truncate">
                      {{ exam.title }}
                    </p>
                    <p class="mt-2 flex items-center text-sm text-gray-500">
                      <span>Attempt date: {{ formatDate(exam.attempt_date) }}</span>
                    </p>
                  </div>
                  <div class="mt-2 sm:mt-0 text-right">
                    <p class="text-sm font-medium text-gray-900">
                      Score: {{ exam.score }}%
                    </p>
                    <p class="mt-2">
                      <span 
                        :class="[
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                          exam.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        ]"
                      >
                        {{ exam.passed ? 'Passed' : 'Failed' }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div v-else class="text-center py-12 bg-white shadow sm:rounded-lg">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No exams taken</h3>
          <p class="mt-1 text-sm text-gray-500">This user hasn't taken any exams yet.</p>
        </div>
      </div>
      
      <!-- Certificates Tab Content -->
      <div v-if="activeTab === 'certificates'" class="mt-6">
        <div v-if="userActivity.certificates.length > 0" class="bg-white shadow overflow-hidden sm:rounded-