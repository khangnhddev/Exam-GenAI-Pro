<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillCheck - Assessments</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://unpkg.com/@lucide/web@latest"></script>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Navigation Bar -->
  <nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <i data-lucide="target" class="w-8 h-8 text-blue-600"></i>
          <span class="ml-2 text-xl font-semibold">SkillCheck</span>
        </div>
        <div class="flex items-center space-x-4">
          <button class="px-4 py-2 text-gray-600 hover:text-gray-900">Explore</button>
          <button class="px-4 py-2 text-gray-600 hover:text-gray-900">Resources</button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Upgrade Now
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="bg-blue-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <div class="max-w-2xl">
          <div class="flex items-center text-white mb-4">
            <i data-lucide="target" class="w-6 h-6 mr-2"></i>
            <span class="uppercase tracking-wide">ASSESSMENTS</span>
          </div>
          <h1 class="text-4xl font-bold text-white mb-6">
            Identify your skill gaps
          </h1>
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="flex-shrink-0 h-6 w-6 text-blue-300">✓</div>
              <p class="ml-3 text-white">
                Take an Assessment to benchmark your current coding skills
              </p>
            </div>
            <div class="flex items-start">
              <div class="flex-shrink-0 h-6 w-6 text-blue-300">✓</div>
              <p class="ml-3 text-white">
                Understand your strengths and weaknesses, to improve your skills
              </p>
            </div>
          </div>
        </div>
        <div class="hidden lg:block">
          <i data-lucide="target" class="w-64 h-64 text-blue-400 opacity-50"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Search Section -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
    <div class="relative">
      <input
        type="text"
        placeholder="Search Assessments"
        class="w-full px-4 py-3 pl-12 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <i data-lucide="search" class="absolute left-4 top-3.5 text-gray-400 w-5 h-5"></i>
    </div>
  </div>

  <!-- Assessment Cards -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- SQL Fundamentals -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start justify-between">
          <div class="p-2 bg-gray-50 rounded-lg">
            <i data-lucide="database" class="w-8 h-8 text-blue-600"></i>
          </div>
          <span class="text-xs text-gray-500">ASSESSMENT</span>
        </div>
        <h3 class="mt-4 text-xl font-semibold text-gray-900">
          SQL Fundamentals
        </h3>
        <button class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
          Preview
          <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
        </button>
      </div>

      <!-- JavaScript Basics -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start justify-between">
          <div class="p-2 bg-gray-50 rounded-lg">
            <i data-lucide="code-2" class="w-8 h-8 text-yellow-600"></i>
          </div>
          <span class="text-xs text-gray-500">ASSESSMENT</span>
        </div>
        <h3 class="mt-4 text-xl font-semibold text-gray-900">
          JavaScript Basics
        </h3>
        <button class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
          Preview
          <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
        </button>
      </div>

      <!-- Data Structures -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start justify-between">
          <div class="p-2 bg-gray-50 rounded-lg">
            <i data-lucide="brain" class="w-8 h-8 text-purple-600"></i>
          </div>
          <span class="text-xs text-gray-500">ASSESSMENT</span>
        </div>
        <h3 class="mt-4 text-xl font-semibold text-gray-900">
          Data Structures
        </h3>
        <button class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
          Preview
          <i data-lucide="chevron-right" class="ml-1 w-4 h-4"></i>
        </button>
      </div>
    </div>
  </div>

  <script>
    // Initialize Lucide icons
    lucide.createIcons();
  </script>
</body>
</html>