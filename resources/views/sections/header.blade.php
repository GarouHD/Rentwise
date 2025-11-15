<header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 py-3">
    <div class="flex items-center justify-between">

      <!-- LEFT: Logo -->
      <a href="{{ home_url() }}" class="flex items-center gap-2">
        <div class="bg-blue-600 rounded-lg p-1.5">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <span class="text-xl font-bold text-gray-900">Rentwise</span>
      </a>

      <!-- RIGHT: Auth Links -->
      @if (is_user_logged_in())
        <a href="{{ wp_logout_url(home_url()) }}"
           class="text-sm text-gray-700 underline hover:text-gray-900 transition">
          Logout
        </a>
      @else
        <div class="flex items-center gap-4">
          <a href="{{ home_url('/log-in') }}"
             class="text-sm text-gray-700 hover:text-gray-900 transition">
            Log In
          </a>
          <a href="{{ home_url('/register') }}"
             class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Sign Up
          </a>
        </div>
      @endif

    </div>
  </div>
</header>
