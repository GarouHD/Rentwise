<header class="bg-gradient-to-r from-white via-white to-gray-50/80 backdrop-blur-lg border-b border-gray-200/60 sticky top-0 z-50 shadow-lg transition-shadow duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
    <div class="flex items-center justify-between">

      <!-- LEFT: Logo -->
      <a href="{{ home_url() }}" class="flex items-center gap-3 group">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <span class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent tracking-tight group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-300">Rentwise</span>
      </a>

      <!-- RIGHT: Auth Links -->
      @if (is_user_logged_in())
        @php
          $current_user = wp_get_current_user();
          $user_initials = strtoupper(substr($current_user->display_name, 0, 2));
          $avatar_color = 'bg-gradient-to-br from-blue-600 to-blue-700'; // Default color, can be customized per user
        @endphp
        <div class="flex items-center gap-4 sm:gap-5">
          <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-lg bg-gray-50/80 border border-gray-200/50">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Signed in as</span>
            <span class="text-sm font-bold text-gray-900">{{ $current_user->display_name }}</span>
          </div>
          
          <!-- User Avatar Button -->
          <button onclick="showUserProfile()" 
                  class="flex items-center justify-center w-11 h-11 sm:w-12 sm:h-12 {{ $avatar_color }} text-white rounded-full font-bold text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 hover:ring-4 hover:ring-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2"
                  aria-label="View profile">
            {{ $user_initials }}
          </button>
          
          <a href="{{ wp_logout_url(home_url()) }}"
             class="text-sm font-semibold text-gray-700 hover:text-gray-900 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-gray-100 border border-gray-200 hover:border-gray-300 hover:shadow-md">
            Logout
          </a>
        </div>
      @else
        <div class="flex items-center gap-3 sm:gap-4">
          <a href="{{ home_url('/log-in') }}"
             class="text-sm font-semibold text-gray-700 hover:text-gray-900 transition-all duration-200 px-5 py-2.5 rounded-lg hover:bg-gray-100 border border-gray-200 hover:border-gray-300 hover:shadow-md">
            Log In
          </a>
          <a href="{{ home_url('/register') }}"
             class="text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 transform">
            Sign Up
          </a>
        </div>
      @endif

    </div>
  </div>
</header>
