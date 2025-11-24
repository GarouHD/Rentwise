{{-- User Profile Modal --}}
@php
  $current_user = wp_get_current_user();
  $user_registered = $current_user->user_registered;
  $user_roles = $current_user->roles;
  $role_display = !empty($user_roles) ? ucfirst($user_roles[0]) : 'User';
  $user_initials = strtoupper(substr($current_user->display_name, 0, 2));
@endphp

<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="userProfileModal"
     onclick="if (event.target === this) hideUserProfile()">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all duration-300 scale-100">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-4 sm:px-6 py-3 sm:py-4">
      <h2 class="text-base sm:text-lg font-semibold text-slate-800">User Profile</h2>
      <button type="button" onclick="hideUserProfile()" class="text-slate-500 hover:text-red-600 text-xl transition-colors" aria-label="Close">✕</button>
    </div>

    {{-- Content --}}
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-5 flex-1 overflow-y-auto overscroll-contain min-h-0">
      {{-- Profile Header --}}
      <div class="flex items-center gap-4 pb-4 border-b border-slate-200">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
          <span class="text-2xl sm:text-3xl font-bold text-white">{{ $user_initials }}</span>
        </div>
        <div class="flex-1">
          <h3 class="text-xl sm:text-2xl font-bold text-slate-800">{{ $current_user->display_name }}</h3>
          <p class="text-sm sm:text-base text-slate-600 mt-1">{{ $role_display }}</p>
        </div>
      </div>

      {{-- Profile Information --}}
      <div class="space-y-4 sm:space-y-5">
        <div class="bg-slate-50 rounded-lg p-4">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Display Name</label>
          <p class="text-base sm:text-lg text-slate-800 font-medium">{{ $current_user->display_name }}</p>
        </div>

        <div class="bg-slate-50 rounded-lg p-4">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Username</label>
          <p class="text-base sm:text-lg text-slate-800 font-medium">{{ $current_user->user_login }}</p>
        </div>

        <div class="bg-slate-50 rounded-lg p-4">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
          <p class="text-base sm:text-lg text-slate-800 font-medium break-all">{{ $current_user->user_email }}</p>
        </div>

        <div class="bg-slate-50 rounded-lg p-4">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Role</label>
          <p class="text-base sm:text-lg text-slate-800 font-medium">{{ $role_display }}</p>
        </div>

        <div class="bg-slate-50 rounded-lg p-4">
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Member Since</label>
          <p class="text-base sm:text-lg text-slate-800 font-medium">{{ date('F j, Y', strtotime($user_registered)) }}</p>
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <div class="flex justify-end space-x-3 px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50">
      <button type="button"
              onclick="hideUserProfile()"
              class="rounded-lg px-5 py-2.5 text-slate-700 hover:bg-slate-200 bg-white border border-slate-300 font-medium transition-colors shadow-sm">
        Close
      </button>
    </div>
  </div>
</div>

<script>
  function showUserProfile() {
    const modal = document.getElementById("userProfileModal");
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      setTimeout(() => {
        modal.style.opacity = '1';
      }, 10);
      // Prevent body scroll when modal is open
      document.body.style.overflow = 'hidden';
    }
  }

  function hideUserProfile() {
    const modal = document.getElementById("userProfileModal");
    if (modal) {
      modal.style.opacity = '0';
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Restore body scroll
        document.body.style.overflow = '';
      }, 300);
    }
  }

  // Close modal on Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      hideUserProfile();
    }
  });
</script>

<style>
  #userProfileModal {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  #userProfileModal.flex {
    opacity: 1;
  }
</style>

