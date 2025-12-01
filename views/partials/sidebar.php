<?php
if (!isset($auth)) {
    $auth = new Auth();
}
?>
<!-- Sidebar Overlay (Mobile Only) -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden transition-opacity duration-300"></div>

<!-- Sidebar -->
<div id="sidebar" class="bg-white border-r border-gray-200 min-h-[calc(100vh-4rem)] sticky top-16 transition-all duration-300 ease-in-out overflow-hidden sidebar-container sidebar-mobile-hidden lg:!translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-4 sm:p-5 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2 flex-1 min-w-0">

                <h5 class="text-sm sm:text-base font-semibold text-gray-900 sidebar-text truncate">Menu</h5>
            </div>
            <div class="flex items-center space-x-1 flex-shrink-0">
                <!-- Mobile: Close button -->
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition" title="Close menu" aria-label="Close sidebar">
                    <i class="fas fa-times text-gray-500 text-sm"></i>
                </button>
                <!-- Desktop: Collapse button -->
                <button onclick="toggleSidebar()" class="hidden lg:flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 transition sidebar-toggle" title="Collapse sidebar" aria-label="Collapse sidebar">
                    <i class="fas fa-chevron-left text-gray-500 text-xs"></i>
                </button>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-2 sm:p-3 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            <ul class="space-y-1">
                <li>
                    <a href="<?= BASE_URL ?>dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" data-tooltip="Dashboard">
                        <i class="fas fa-tachometer-alt sidebar-icon"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                
                <?php if ($auth->isAdmin()): ?>
                    <li>
                        <a href="<?= BASE_URL ?>admin/entrepreneurs.php" class="sidebar-link <?= $currentPage === 'entrepreneurs' ? 'active' : '' ?>" data-tooltip="Entrepreneurs">
                            <i class="fas fa-users sidebar-icon"></i>
                            <span class="sidebar-text">Entrepreneurs</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <li class="mt-3 pt-2 border-t border-gray-100">
                    <div class="px-3 py-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider sidebar-text">Profile</span>
                    </div>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>profile.php" class="sidebar-link <?= $currentPage === 'profile' ? 'active' : '' ?>" data-tooltip="My Profile">
                        <i class="fas fa-user sidebar-icon"></i>
                        <span class="sidebar-text">My Profile</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>biodata.php" class="sidebar-link <?= $currentPage === 'biodata' ? 'active' : '' ?>" data-tooltip="My Biodata">
                        <i class="fas fa-id-card sidebar-icon"></i>
                        <span class="sidebar-text">My Biodata</span>
                    </a>
                </li>
                
                <li class="mt-3 pt-2 border-t border-gray-100">
                    <div class="px-3 py-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider sidebar-text">Business</span>
                    </div>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>farm.php" class="sidebar-link <?= $currentPage === 'farm' ? 'active' : '' ?>" data-tooltip="My Farm">
                        <i class="fas fa-seedling sidebar-icon"></i>
                        <span class="sidebar-text">My Farm</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>shop.php" class="sidebar-link <?= $currentPage === 'shop' ? 'active' : '' ?>" data-tooltip="My Shop">
                        <i class="fas fa-store sidebar-icon"></i>
                        <span class="sidebar-text">My Shop</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>announcements.php" class="sidebar-link <?= $currentPage === 'announcements' ? 'active' : '' ?>" data-tooltip="Announcements">
                        <i class="fas fa-bullhorn sidebar-icon"></i>
                        <span class="sidebar-text">Announcements</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>social-links.php" class="sidebar-link <?= $currentPage === 'social-links' ? 'active' : '' ?>" data-tooltip="Social Links">
                        <i class="fas fa-share-alt sidebar-icon"></i>
                        <span class="sidebar-text">Social Links</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?= BASE_URL ?>chatbot.php" class="sidebar-link <?= $currentPage === 'chatbot' ? 'active' : '' ?>" data-tooltip="AI Chatbot">
                        <i class="fas fa-robot sidebar-icon"></i>
                        <span class="sidebar-text">AI Chatbot</span>
                    </a>
                </li>
                
                <?php if ($auth->isAdmin()): ?>
                    <li class="mt-3 pt-2 border-t border-gray-100">
                        <div class="px-3 py-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider sidebar-text">Admin</span>
                        </div>
                    </li>
                    
                    <li>
                        <a href="<?= BASE_URL ?>admin/settings.php" class="sidebar-link <?= $currentPage === 'settings' ? 'active' : '' ?>" data-tooltip="Settings">
                            <i class="fas fa-cog sidebar-icon"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/faq.php" class="sidebar-link <?= $currentPage === 'faq' ? 'active' : '' ?>" data-tooltip="FAQ">
                            <i class="fas fa-question-circle sidebar-icon"></i>
                            <span class="sidebar-text">FAQ</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<style>
/* Sidebar Container */
.sidebar-container {
    width: 260px;
    flex-shrink: 0;
}

.sidebar-container.collapsed {
    width: 72px;
}

/* Mobile Sidebar Styles */
@media (max-width: 1023px) {
    .sidebar-container {
        position: fixed;
        top: 4rem;
        left: 0;
        height: calc(100vh - 4rem);
        z-index: 50;
        transform: translateX(-100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-container.sidebar-mobile-hidden {
        transform: translateX(-100%);
    }
    
    .sidebar-container:not(.sidebar-mobile-hidden) {
        transform: translateX(0);
    }
    
    #sidebarOverlay:not(.hidden) {
        opacity: 1;
    }
}

/* Desktop: Keep sidebar visible */
@media (min-width: 1024px) {
    .sidebar-container {
        position: sticky;
        transform: translateX(0) !important;
    }
}

/* Sidebar Link Styles */
.sidebar-link {
    display: flex;
    align-items: center;
    padding: 0.625rem 0.875rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    position: relative;
    color: #6b7280;
    font-weight: 500;
    font-size: 0.875rem;
    white-space: nowrap;
    text-decoration: none;
}

.sidebar-link:hover {
    background-color: #f9fafb;
    color: #f59e0b;
}

.sidebar-link.active {
    background-color: #fef3c7;
    color: #d97706;
    font-weight: 600;
}

.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background-color: #f59e0b;
    border-radius: 0 2px 2px 0;
}

.sidebar-icon {
    width: 1.125rem;
    text-align: center;
    margin-right: 0.75rem;
    flex-shrink: 0;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.sidebar-text {
    white-space: nowrap;
    opacity: 1;
    transition: opacity 0.3s ease, width 0.3s ease, margin 0.3s ease;
    overflow: hidden;
}

.sidebar-toggle {
    transition: transform 0.3s ease;
}

.sidebar-toggle i {
    transition: transform 0.3s ease;
}

/* Collapsed State */
.sidebar-container.collapsed .sidebar-text {
    opacity: 0;
    width: 0;
    margin: 0;
    overflow: hidden;
}

.sidebar-container.collapsed .sidebar-toggle i {
    transform: rotate(180deg);
}

.sidebar-container.collapsed .sidebar-link {
    justify-content: center;
    padding: 0.625rem;
}

.sidebar-container.collapsed .sidebar-icon {
    margin-right: 0;
}

.sidebar-container.collapsed .sidebar-link:hover {
    transform: scale(1.1);
}

.sidebar-container.collapsed .sidebar-link.active::before {
    display: none;
}

/* Tooltip for collapsed sidebar items */
.sidebar-container.collapsed .sidebar-link {
    position: relative;
}

.sidebar-container.collapsed .sidebar-link::after {
    content: attr(data-tooltip);
    position: absolute;
    left: calc(100% + 8px);
    padding: 0.5rem 0.75rem;
    background: #1f2937;
    color: white;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
    z-index: 60;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.sidebar-container.collapsed .sidebar-link:hover::after {
    opacity: 1;
}

/* Hide section headers when collapsed */
.sidebar-container.collapsed li[class*="border-t"] {
    display: none;
}

/* Smooth scrollbar */
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
    border-radius: 0.25rem;
}

.scrollbar-thumb-gray-300::-webkit-scrollbar-thumb:hover {
    background-color: #9ca3af;
}

.scrollbar-track-transparent::-webkit-scrollbar-track {
    background-color: transparent;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    
    const isCollapsed = sidebar.classList.toggle('collapsed');
    
    // Trigger resize event for any charts or responsive elements
    window.dispatchEvent(new Event('resize'));
    
    // Save state to localStorage
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

// Restore sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    
    // Only restore collapsed state on desktop
    if (window.innerWidth >= 1024) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    }
    
    // Trigger resize after restore
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 300);
});
</script>
