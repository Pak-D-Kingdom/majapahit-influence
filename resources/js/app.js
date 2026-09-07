const setupSidebar = (toggleId, sidebarId, overlayId) => {
    const toggle = document.getElementById(toggleId);
    const sidebar = document.getElementById(sidebarId);
    const overlay = document.getElementById(overlayId);

    if (!toggle || !sidebar || !overlay) return;

    const open = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
    };

    const close = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', () => {
        const isClosed = sidebar.classList.contains('-translate-x-full');
        if (isClosed) {
            open();
        } else {
            close();
        }
    });

    overlay.addEventListener('click', close);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            close();
            toggle.focus();
        }
    });
};

setupSidebar('sidebar-toggle', 'dashboard-sidebar', 'sidebar-overlay');
setupSidebar('kol-sidebar-toggle', 'kol-dashboard-sidebar', 'kol-sidebar-overlay');
