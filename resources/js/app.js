import './bootstrap';

// Sidebar Toggle functionality
document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const toggleMobileBtn = document.getElementById('sidebarToggleMobile');
  const themeToggle = document.getElementById('themeToggle');

  // Desktop vs Mobile detection
  const isDesktop = () => window.innerWidth >= 768;

  // Sidebar initial state
  if (sidebar) {
    if (isDesktop()) {
      // Desktop: always visible
      sidebar.style.transform = 'translateX(0)';
      sidebar.dataset.visible = 'true';
    }
  }

  // Mobile sidebar toggle
  if (toggleMobileBtn && sidebar) {
    let isOpen = false;
    toggleMobileBtn.addEventListener('click', () => {
      isOpen = !isOpen;
      sidebar.style.transform = isOpen ? 'translateX(0)' : 'translateX(-100%)';
      sidebar.dataset.visible = isOpen ? 'true' : 'false';
      toggleMobileBtn.classList.toggle('btn-active', isOpen);
    });
  }

  // Handle resize for responsive
  window.addEventListener('resize', () => {
    if (sidebar && toggleMobileBtn) {
      if (isDesktop()) {
        // Switch to desktop mode
        sidebar.style.transform = 'translateX(0)';
        sidebar.dataset.visible = 'true';
        toggleMobileBtn.classList.remove('btn-active');
      }
    }
  });

  // Theme toggle
  if (themeToggle) {
    // Load saved theme from localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    themeToggle.checked = savedTheme === 'dark';

    themeToggle.addEventListener('change', (e) => {
      const theme = e.target.checked ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
    });
  }
});
