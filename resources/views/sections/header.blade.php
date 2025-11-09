<header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 py-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <a href="{{ home_url() }}" class="flex items-center gap-2">
          <div class="bg-blue-600 rounded-lg p-1.5">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">Rentwise</span>
        </a>
      </div>
      <div class="flex items-center gap-3">
        @if (is_user_logged_in())
          <a href="{{ home_url() }}" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 transition-colors">
            Dashboard
          </a>
          <a href="{{ wp_logout_url(home_url()) }}" class="bg-gray-900 hover:bg-gray-800 text-white font-semibold px-5 py-2 rounded-lg transition-colors shadow-sm">
            Log Out
          </a>
        @else
          <a href="{{ home_url('/log-in') }}" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 transition-colors">
            Log In
          </a>
          <a href="{{ home_url('/register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition-colors shadow-sm">
            Sign Up
          </a>
        @endif
      </div>
    </div>
  </div>
</header>
