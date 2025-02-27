<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillCheck - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://unpkg.com/@lucide/web@latest"></script>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Top Banner -->
  <div class="bg-orange-700 text-white px-4 py-2 flex justify-between items-center">
    <div class="flex items-center">
      <span>Transform your team with the latest tech skills</span>
      <a href="#" class="ml-2 underline">Learn more</a>
    </div>
    <button class="text-white">
      <i data-lucide="x" class="w-4 h-4"></i>
    </button>
  </div>

  <!-- Navigation Bar -->
  <nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-8">
          <div class="flex items-center">
            <i data-lucide="target" class="w-8 h-8 text-blue-600"></i>
            <span class="ml-2 text-xl font-semibold">SkillCheck</span>
          </div>
          <div class="hidden md:flex items-center space-x-4">
            <button class="px-3 py-2 text-gray-600 hover:text-gray-900">Explore</button>
            <button class="px-3 py-2 text-gray-600 hover:text-gray-900">Pricing</button>
            <button class="px-3 py-2 text-gray-600 hover:text-gray-900">For Business</button>
            <button class="px-3 py-2 text-gray-600 hover:text-gray-900">Resources</button>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input
              type="text"
              placeholder="Search"
              class="w-64 px-4 py-2 pl-10 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <i data-lucide="search" class="absolute left-3 top-2.5 text-gray-400 w-4 h-4"></i>
          </div>
          <button class="w-8 h-8 bg-gray-700 rounded-full text-white flex items-center justify-center">
            K
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center space-x-4">
        <div class="w-12 h-12 bg-gray-700 rounded-full text-white flex items-center justify-center text-xl">
          K
        </div>
        <div>
          <div class="flex items-center space-x-3">
            <h1 class="text-2xl font-bold">Welcome, Khang!</h1>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Get Started (1/2)</span>
          </div>
          <p class="text-gray-600">Take the first steps to building your professional Skills</p>
        </div>
      </div>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Personalized Preferences
      </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-8">
      <nav class="flex space-x-8">
        <button class="px-3 py-4 text-blue-600 border-b-2 border-blue-600 font-medium">Home</button>
        <button class="px-3 py-4 text-gray-500 hover:text-gray-700">In Progress</button>
        <button class="px-3 py-4 text-gray-500 hover:text-gray-700">Saved</button>
        <button class="px-3 py-4 text-gray-500 hover:text-gray-700">Completed</button>
        <button class="px-3 py-4 text-gray-500 hover:text-gray-700">My Courses</button>
      </nav>
    </div>

    <!-- Current Course -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
        <button class="text-gray-400 hover:text-gray-600">
          <i data-lucide="more-vertical" class="w-5 h-5"></i>
        </button>
      </div>
      <h3 class="text-xl font-semibold mb-4">Beginning Flutter: Android Mobile App Development</h3>
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div class="bg-green-500 h-2 rounded-full" style="width: 5%"></div>
        </div>
        <div class="flex items-center text-gray-500">
          <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
          <span>9 hours 30 minutes left</span>
        </div>
      </div>
      <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg">
        Continue Learning
      </button>
    </div>

    <!-- Interview Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
      <div class="text-sm text-gray-500 mb-2">FULL LOOP INTERVIEW</div>
      <h2 class="text-2xl font-bold mb-4">The shortest path to interview mastery</h2>
      <p class="text-gray-600 mb-4">Skip the LeetCode grind with PAL (Personalized Adaptive Learning).</p>
      <div class="flex items-center space-x-4">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Get Started
        </button>
        <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg flex items-center">
          <i data-lucide="play" class="w-4 h-4 mr-2"></i>
          See how it works
        </button>
      </div>
    </div>

    <!-- Activity Section -->
    <div class="mb-8">
      <div class="flex items-center space-x-2 mb-6">
        <i data-lucide="activity" class="w-6 h-6 text-blue-600"></i>
        <h2 class="text-xl font-bold">Your Activity</h2>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Learning Streak -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Learning Streak</h3>
            <button class="text-blue-600 hover:text-blue-700">View All</button>
          </div>
          <div class="flex items-center space-x-8">
            <div class="text-center">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-2">
                <i data-lucide="flame" class="w-6 h-6 text-orange-500"></i>
              </div>
              <div class="font-bold">5 days</div>
              <div class="text-sm text-gray-500">Current Streak</div>
            </div>
            <div class="text-center">
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                <i data-lucide="award" class="w-6 h-6 text-blue-500"></i>
              </div>
              <div class="font-bold">72 days</div>
              <div class="text-sm text-gray-500">Longest Streak</div>
            </div>
          </div>
        </div>

        <!-- Learning Calendar -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <h3 class="font-semibold mb-4">My Learning Calendar</h3>
          <p class="text-gray-600 mb-4">Goal-setters are 42% more likely to succeed. Schedule one now! 🚀</p>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Explore Now
          </button>
          <p class="text-sm text-gray-500 mt-2">Note: Create a goal by clicking on ⭐ icon on any content tile</p>
        </div>
      </div>
    </div>

    <!-- Recommended Section -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-2">
          <i data-lucide="sparkles" class="w-6 h-6 text-blue-600"></i>
          <h2 class="text-xl font-bold">Recommended For You</h2>
        </div>
        <button class="text-blue-600 hover:text-blue-700">Learning Preferences</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Course Card 1 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">Flask: Develop Web Applications in Python</h3>
          <p class="text-gray-600 text-sm mb-4">Gain insights into developing web applications using Flask, a lightweight and easy-to-learn Python framework.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>10 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 2 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">React Native: Build Mobile Apps</h3>
          <p class="text-gray-600 text-sm mb-4">Learn to create cross-platform mobile applications using React Native and modern JavaScript practices.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>15 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 3 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">GraphQL API Development</h3>
          <p class="text-gray-600 text-sm mb-4">Master GraphQL to build efficient, powerful APIs. Learn schema design, resolvers, and best practices.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>12 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 4 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">Docker & Kubernetes Essentials</h3>
          <p class="text-gray-600 text-sm mb-4">Learn container orchestration from basics to advanced deployment strategies.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>20 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 5 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">Vue.js 3 Masterclass</h3>
          <p class="text-gray-600 text-sm mb-4">Build modern web applications with Vue 3's Composition API and best practices.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Intermediate</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>16 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 6 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">Machine Learning with Python</h3>
          <p class="text-gray-600 text-sm mb-4">From basic algorithms to deep learning. Build real-world AI applications.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>25 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 7 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">Rust Programming Language</h3>
          <p class="text-gray-600 text-sm mb-4">Learn systems programming with Rust. Build reliable and efficient software.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>30 hours</span>
            </div>
          </div>
        </div>

        <!-- Course Card 8 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">COURSE</span>
            <button class="text-gray-400 hover:text-gray-600">
              <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
          </div>
          <h3 class="font-semibold mb-4">AWS Cloud Architecture</h3>
          <p class="text-gray-600 text-sm mb-4">Design and implement scalable cloud solutions using AWS services.</p>
          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i data-lucide="bar-chart-2" class="w-4 h-4 mr-1"></i>
              <span>Advanced</span>
            </div>
            <div class="flex items-center">
              <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
              <span>22 hours</span>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-8">
        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Explore All
        </button>
      </div>
    </div>

    <!-- Certification Section -->
    <div class="mb-8">
      <div class="flex items-center space-x-2 mb-6">
        <i data-lucide="award" class="w-6 h-6 text-blue-600"></i>
        <h2 class="text-xl font-bold">Certification</h2>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between">
        <div class="flex items-center space-x-6">
          <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
            <i data-lucide="certificate" class="w-8 h-8 text-blue-600"></i>
          </div>
          <div>
            <h3 class="font-semibold mb-2">Your certificate is waiting. Don't delay!</h3>
            <p class="text-gray-600">Accelerate your career with a SkillCheck Certification</p>
          </div>
        </div>
        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Explore
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