const setupSidebar = (toggleId, sidebarId, overlayId) => {
    const toggle = document.getElementById(toggleId);
    const sidebar = document.getElementById(sidebarId);
    const overlay = document.getElementById(overlayId);

    if (!toggle || !sidebar || !overlay) return;

    const close = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    };

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });
    overlay.addEventListener('click', close);
};

setupSidebar('sidebar-toggle', 'dashboard-sidebar', 'sidebar-overlay');
setupSidebar('kol-sidebar-toggle', 'kol-dashboard-sidebar', 'kol-sidebar-overlay');
