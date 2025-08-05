(function() {
    "use strict";

    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

    function openMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('-translate-x-full');
        }
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.classList.remove('hidden');
        }
        document.body.classList.add('overflow-hidden');
    }

    function closeMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.add('-translate-x-full');
        }
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.classList.add('hidden');
        }
        document.body.classList.remove('overflow-hidden');
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', openMobileSidebar);
    }

    if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', closeMobileSidebar);
    }

    if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    // Close sidebar when clicking on nav links
    if (mobileSidebar) {
        const mobileNavLinks = mobileSidebar.querySelectorAll('nav a');
        if (mobileNavLinks && mobileNavLinks.length > 0) {
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });
        }
    }

    // User Dropdown Toggle
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Close dropdown when clicking on links
        const dropdownLinks = userDropdown.querySelectorAll('a');
        if (dropdownLinks && dropdownLinks.length > 0) {
            dropdownLinks.forEach(link => {
                link.addEventListener('click', function() {
                    userDropdown.classList.add('hidden');
                });
            });
        }
    }

    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const darkModeIcon = document.getElementById('dark-mode-icon');
    const htmlElement = document.documentElement;

    if (darkModeToggle && darkModeIcon) {
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('color-theme') ||
            (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        if (currentTheme === 'dark') {
            htmlElement.classList.add('dark');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }

        darkModeToggle.addEventListener('click', function() {
            htmlElement.classList.toggle('dark');

            if (htmlElement.classList.contains('dark')) {
                localStorage.setItem('color-theme', 'dark');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            } else {
                localStorage.setItem('color-theme', 'light');
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
            }
        });
    }

})();