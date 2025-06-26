document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

    function openMobileSidebar() {
        mobileSidebar.classList.remove('-translate-x-full');
        mobileSidebarOverlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMobileSidebar() {
        mobileSidebar.classList.add('-translate-x-full');
        mobileSidebarOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    mobileMenuButton.addEventListener('click', openMobileSidebar);
    mobileSidebarClose.addEventListener('click', closeMobileSidebar);
    mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);

    // Close sidebar when clicking on nav links
    const mobileNavLinks = mobileSidebar.querySelectorAll('nav a');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileSidebar);
    });

    // User Dropdown Toggle
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

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
    dropdownLinks.forEach(link => {
        link.addEventListener('click', function() {
            userDropdown.classList.add('hidden');
        });
    });
});