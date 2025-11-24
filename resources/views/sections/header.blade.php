<header class="bg-white/95 backdrop-blur-md border-b border-gray-200/50 sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
    <div class="flex items-center justify-between">

      <!-- LEFT: Logo -->
      <a href="{{ home_url() }}" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
        <div class="bg-blue-600 rounded-lg p-1.5 shadow-sm">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <span class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Rentwise</span>
      </a>

      <!-- RIGHT: Auth Links -->
      @if (is_user_logged_in())
        @php
          $current_user = wp_get_current_user();
          $user_initials = strtoupper(substr($current_user->display_name, 0, 2));
          $avatar_color = 'bg-blue-600'; // Default color, can be customized per user
        @endphp
        <div class="flex items-center gap-3 sm:gap-4">
          <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600">
            <span>Signed in as</span>
            <span class="font-semibold text-gray-800">{{ $current_user->display_name }}</span>
          </div>
          
          <!-- User Avatar Button -->
          <button onclick="showUserProfile()" 
                  class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 {{ $avatar_color }} text-white rounded-full font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  aria-label="View profile">
            {{ $user_initials }}
          </button>
          
          <a href="{{ wp_logout_url(home_url()) }}"
             class="text-sm text-gray-700 underline hover:text-gray-900 transition-colors px-2 py-1 rounded hover:bg-gray-50">
            Logout
          </a>
        </div>
      @else
        <div class="flex items-center gap-3 sm:gap-4">
          <a href="{{ home_url('/log-in') }}"
             class="text-sm text-gray-700 hover:text-gray-900 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50 font-medium">
            Log In
          </a>
          <a href="{{ home_url('/register') }}"
             class="text-sm bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md font-medium">
            Sign Up
          </a>
        </div>
      @endif

    </div>
  </div>
</header>
