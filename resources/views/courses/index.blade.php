<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillCheck - Courses</title>
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

  <!-- Search Section -->
  <div class="bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative max-w-2xl mx-auto">
        <input
          type="text"
          placeholder="What would you like to learn today?"
          class="w-full px-4 py-3 pl-12 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <i data-lucide="search" class="absolute left-4 top-3.5 text-gray-400 w-5 h-5"></i>
      </div>
    </div>
  </div>

  <!-- Popular Courses Section -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center space-x-2">
        <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
        <h2 class="text-2xl font-bold text-gray-900">Popular courses and paths</h2>
      </div>
      <button class="text-blue-600 hover:text-blue-700 font-medium">View all</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Course Card 1 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            System Design Interview Guide
          </h3>
          <p class="text-gray-600 mb-4">
            Master distributed system fundamentals and ace your next system design interview with real-world examples.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>26h</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Card 2 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Coding Interview Patterns
          </h3>
          <p class="text-gray-600 mb-4">
            Learn essential patterns to solve thousands of LeetCode-style questions efficiently. Get interview-ready fast.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>85h</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Card 3 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Object-Oriented Design
          </h3>
          <p class="text-gray-600 mb-4">
            Master OOD principles with practical examples and real-world design patterns. Perfect for technical interviews.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>50h</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recommended Section -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center space-x-2">
        <i data-lucide="sparkles" class="w-6 h-6 text-blue-600"></i>
        <h2 class="text-2xl font-bold text-gray-900">Recommended for you</h2>
      </div>
      <button class="text-blue-600 hover:text-blue-700 font-medium">Learning Preferences</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Recommended Course 1 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Flutter Development
          </h3>
          <p class="text-gray-600 mb-4">
            Build beautiful, natively compiled applications for mobile, web, and desktop from a single codebase.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Beginner</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>40h</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recommended Course 2 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Rust Programming
          </h3>
          <p class="text-gray-600 mb-4">
            Learn systems programming with Rust. Build reliable and efficient software with zero-cost abstractions.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>60h</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recommended Course 3 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <div class="flex space-x-2">
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
              </button>
            </div>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Cloud Architecture
          </h3>
          <p class="text-gray-600 mb-4">
            Design scalable and resilient cloud solutions. Master AWS, Azure, and GCP best practices.
          </p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-2">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center space-x-2">
              <i data-lucide="clock" class="w-4 h-4"></i>
              <span>45h</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Initialize Lucide icons
    lucide.createIcons();
  </script>
</body>
</html>