import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// User Profile Modal Functions
window.showUserProfile = function() {
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
};

window.hideUserProfile = function() {
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
};

// Close modal on Escape key
document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      const profileModal = document.getElementById("userProfileModal");
      if (profileModal && !profileModal.classList.contains('hidden')) {
        hideUserProfile();
      }
    }
  });
});
