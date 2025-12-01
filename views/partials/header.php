<?php
if (!isset($auth)) {
    $auth = new Auth();
}

// Get current logged in user (for profile image and name)
$currentUser = $auth->isLoggedIn() ? $auth->getUser() : null;
$headerUserName = $currentUser['name'] ?? ($_SESSION['user_name'] ?? 'User');
$headerUserInitial = strtoupper(substr($headerUserName, 0, 1));
$headerUserProfileImage = $currentUser['profile_image'] ?? null;

// Detect if we're on the landing page (index.php) or auth pages or info pages
// The landing page sets $pageTitle = 'Home'
// Auth pages are in /auth/ directory
// Info pages: Contact, Privacy Policy, Terms of Service
$isLandingPage = isset($pageTitle) && $pageTitle === 'Home';
$isAuthPage = (isset($_SERVER['SCRIPT_NAME']) && strpos($_SERVER['SCRIPT_NAME'], '/auth/') !== false) ||
              (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/auth/') !== false) ||
              (isset($pageTitle) && in_array($pageTitle, ['Login', 'Register', 'Reset Password', 'Forgot Password']));
$isInfoPage = (isset($_SERVER['SCRIPT_NAME']) && in_array(basename($_SERVER['SCRIPT_NAME']), ['contact.php', 'privacy-policy.php', 'terms-of-service.php'])) ||
              (isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/contact.php') !== false || 
                                                   strpos($_SERVER['REQUEST_URI'], '/privacy-policy.php') !== false || 
                                                   strpos($_SERVER['REQUEST_URI'], '/terms-of-service.php') !== false)) ||
              (isset($pageTitle) && in_array($pageTitle, ['Contact Us', 'Privacy Policy', 'Terms of Service']));
$hideHamburger = $isLandingPage || $isAuthPage || $isInfoPage;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="Pineapple Entrepreneur Information Management System - Manage farms, shops, and announcements">
    <meta name="theme-color" content="#f59e0b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="PinePix">
    <meta name="mobile-web-app-capable" content="yes">
    <title>PinePix - <?= $pageTitle ?? 'Home' ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>favicon.ico?v=1" />
    <link rel="shortcut icon" href="<?= BASE_URL ?>favicon.ico?v=1" />
    <link rel="icon" type="image/png" sizes="96x96" href="<?= BASE_URL ?>favicon-96x96.png?v=1" />
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>favicon.svg?v=1" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>apple-touch-icon.png?v=1" />
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= BASE_URL ?>site.webmanifest?v=1" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Tailwind config - set after Tailwind loads
        (function() {
            if (typeof tailwind !== 'undefined') {
                tailwind.config = {
                    theme: {
                        extend: {
                            colors: {
                                primary: {
                                    50: '#fffbeb',
                                    100: '#fef3c7',
                                    200: '#fde68a',
                                    300: '#fcd34d',
                                    400: '#fbbf24',
                                    500: '#f59e0b',
                                    600: '#d97706',
                                    700: '#b45309',
                                    800: '#92400e',
                                    900: '#78350f',
                                },
                                gold: {
                                    50: '#fffbeb',
                                    100: '#fef3c7',
                                    200: '#fde68a',
                                    300: '#fcd34d',
                                    400: '#fbbf24',
                                    500: '#f59e0b',
                                    600: '#d97706',
                                    700: '#b45309',
                                    800: '#92400e',
                                    900: '#78350f',
                                },
                                accent: {
                                    50: '#f0fdfa',
                                    100: '#ccfbf1',
                                    200: '#99f6e4',
                                    300: '#5eead4',
                                    400: '#2dd4bf',
                                    500: '#14b8a6',
                                    600: '#0d9488',
                                    700: '#0f766e',
                                    800: '#115e59',
                                    900: '#134e4a',
                                }
                            }
                        }
                    }
                };
            }
        })();
    </script>
    <!-- jQuery (must be loaded before DataTables and other jQuery-dependent libraries) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables JS (requires jQuery) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Swiper.js for Image Carousels -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/custom.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50 transition-colors duration-200">
    <nav class="bg-white border-b-2 border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                    <!-- Mobile Hamburger Button -->
                    <?php if (!$hideHamburger): ?>
                        <?php if ($auth->isLoggedIn()): ?>
                            <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition" onclick="toggleMobileSidebar()" aria-label="Toggle sidebar" id="sidebarToggleBtn">
                                <i class="fas fa-bars text-gray-600 text-base sm:text-lg"></i>
                            </button>
                        <?php else: ?>
                            <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                                <i class="fas fa-bars text-gray-600 text-base sm:text-lg"></i>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>index.php" class="flex items-center group">
                        <div class="relative">
                            <img src="<?= BASE_URL ?>assets/images/logoblack.png" alt="PinePix Logo" class="h-8 sm:h-10 md:h-12 object-contain transition-transform" loading="eager">
                        </div>
                    </a>
                </div>
                
                <div class="hidden lg:flex items-center space-x-2 xl:space-x-3">
                    <?php if ($auth->isLoggedIn()): ?>
                        <div class="relative" id="userMenu">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" aria-label="User menu">
                                <div class="relative flex-shrink-0">
                                    <?php if (!empty($headerUserProfileImage)): ?>
                                        <img src="<?= BASE_URL . $headerUserProfileImage ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-primary-100">
                                    <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-xs font-semibold">
                                            <?= $headerUserInitial ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-left hidden xl:block">
                                    <div class="text-xs font-medium text-gray-700 truncate max-w-[100px]"><?= htmlspecialchars($headerUserName) ?></div>
                                </div>
                                <i id="userMenuChevron" class="fas fa-chevron-down text-xs text-gray-400 transition transform"></i>
                            </button>
                            <div id="userMenuDropdown" class="absolute right-0 mt-1.5 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1.5 hidden z-50">
                                <a href="<?= BASE_URL ?>index.php" class="user-menu-link" onclick="closeUserMenu()">
                                    <i class="fas fa-home user-menu-icon"></i>
                                    <span>Home</span>
                                </a>
                                <a href="<?= BASE_URL ?>dashboard.php" class="user-menu-link" onclick="closeUserMenu()">
                                    <i class="fas fa-tachometer-alt user-menu-icon"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="<?= BASE_URL ?>profile.php" class="user-menu-link" onclick="closeUserMenu()">
                                    <i class="fas fa-user user-menu-icon"></i>
                                    <span>My Profile</span>
                                </a>
                                <hr class="my-1.5 border-gray-200">
                                <a href="<?= BASE_URL ?>auth/logout.php" class="user-menu-link user-menu-link-logout" onclick="confirmLogout(event)">
                                    <i class="fas fa-sign-out-alt user-menu-icon"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="relative" id="authMenu">
                            <button onclick="toggleAuthMenu()" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" aria-label="Account menu">
                                <i class="fas fa-user-circle text-gray-600 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700 hidden xl:block">Account</span>
                                <i id="authMenuChevron" class="fas fa-chevron-down text-xs text-gray-400 transition transform"></i>
                            </button>
                            <div id="authMenuDropdown" class="absolute right-0 mt-1.5 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1.5 hidden z-50">
                                <a href="<?= BASE_URL ?>auth/login.php" class="user-menu-link" onclick="closeAuthMenu()">
                                    <i class="fas fa-sign-in-alt user-menu-icon"></i>
                                    <span>Login</span>
                                </a>
                                <a href="<?= BASE_URL ?>auth/register.php" class="user-menu-link" onclick="closeAuthMenu()">
                                    <i class="fas fa-user-plus user-menu-icon"></i>
                                    <span>Get Started</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Actions -->
                <div class="flex items-center space-x-1 sm:space-x-2 lg:hidden relative">
                    <?php if ($auth->isLoggedIn()): ?>
                        <button class="p-1.5 rounded-full hover:bg-gray-100 active:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" onclick="toggleMobileMenu(event)" aria-label="Toggle user menu" id="mobileUserMenuBtn">
                            <?php if (!empty($headerUserProfileImage)): ?>
                                <img src="<?= BASE_URL . $headerUserProfileImage ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-primary-100">
                            <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-xs font-semibold">
                                    <?= $headerUserInitial ?>
                            </div>
                            <?php endif; ?>
                        </button>
                    <?php else: ?>
                        <button class="p-1.5 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" onclick="toggleMobileMenu()" aria-label="Toggle menu" id="mobileUserMenuBtn">
                            <i class="fas fa-ellipsis-v text-gray-600 text-base"></i>
                        </button>
                    <?php endif; ?>
                    
                    <!-- Mobile menu dropdown -->
                    <div id="mobileMenu" class="hidden lg:hidden bg-white rounded-lg shadow-lg border border-gray-200 py-1.5 absolute right-0 top-full mt-1.5 w-48 z-[60]">
            <?php if ($auth->isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>index.php" class="user-menu-link" onclick="closeUserMenu()">
                    <i class="fas fa-home user-menu-icon"></i>
                    <span>Home</span>
                </a>
                <a href="<?= BASE_URL ?>dashboard.php" class="user-menu-link" onclick="closeUserMenu()">
                    <i class="fas fa-tachometer-alt user-menu-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>profile.php" class="user-menu-link" onclick="closeUserMenu()">
                    <i class="fas fa-user user-menu-icon"></i>
                    <span>My Profile</span>
                </a>
                <hr class="my-1.5 border-gray-200">
                <a href="<?= BASE_URL ?>auth/logout.php" class="user-menu-link user-menu-link-logout" onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt user-menu-icon"></i>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login.php" class="user-menu-link" onclick="closeUserMenu()">
                    <i class="fas fa-sign-in-alt user-menu-icon"></i>
                    <span>Login</span>
                </a>
                <a href="<?= BASE_URL ?>auth/register.php" class="user-menu-link" onclick="closeUserMenu()">
                    <i class="fas fa-user-plus user-menu-icon"></i>
                    <span>Register</span>
                </a>
            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <style>
        /* Header responsive improvements */
        @media (max-width: 640px) {
            nav {
                height: 3.5rem;
            }
            
            nav .h-14 {
                height: 3.5rem;
            }
        }
        
        /* User menu dropdown improvements */
        #userMenu:hover .group-hover\:opacity-100,
        #userMenu:focus-within .group-hover\:opacity-100 {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Smooth transitions for header elements */
        nav a, nav button {
            transition: all 0.2s ease;
        }
        
        /* User menu dropdown styling - consistent with sidebar */
        .user-menu-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.875rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            color: #6b7280;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
        }
        
        .user-menu-link:hover {
            background-color: #f9fafb;
            color: #f59e0b;
        }
        
        .user-menu-link-logout {
            color: #dc2626;
        }
        
        .user-menu-link-logout:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        .user-menu-icon {
            width: 1.125rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        
        #userMenuDropdown,
        #authMenuDropdown,
        #mobileMenu {
            animation: fadeInDown 0.2s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <script>
        function toggleMobileMenu(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            
            const menu = document.getElementById('mobileMenu');
            if (!menu) return;
            
            const isCurrentlyOpen = !menu.classList.contains('hidden');
            
            // Close sidebar if it's open
            if (!isCurrentlyOpen) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                if (sidebar && overlay) {
                    sidebar.classList.add('sidebar-mobile-hidden');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
            
            // Toggle menu visibility
            if (isCurrentlyOpen) {
                // Close menu
                menu.classList.add('hidden');
                menu.dataset.menuOpen = '';
                document.body.style.overflow = '';
            } else {
                // Open menu
                menu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Add a small delay to prevent immediate closing by click outside handler
                setTimeout(() => {
                    menu.dataset.menuOpen = 'true';
                }, 100);
            }
        }
        
        function closeUserMenu() {
            const desktopMenu = document.getElementById('userMenuDropdown');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (desktopMenu) desktopMenu.classList.add('hidden');
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
        
        // Toggle auth menu dropdown (for non-logged-in users)
        function toggleAuthMenu() {
            const dropdown = document.getElementById('authMenuDropdown');
            const chevron = document.getElementById('authMenuChevron');
            const userMenu = document.getElementById('userMenuDropdown');
            
            // Close user menu if open
            if (userMenu) userMenu.classList.add('hidden');
            
            if (dropdown && chevron) {
                const isOpen = !dropdown.classList.contains('hidden');
                
                if (isOpen) {
                    dropdown.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                } else {
                    dropdown.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                }
            }
        }
        
        function closeAuthMenu() {
            const dropdown = document.getElementById('authMenuDropdown');
            const chevron = document.getElementById('authMenuChevron');
            
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
        
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar && overlay) {
                const isOpen = !sidebar.classList.contains('sidebar-mobile-hidden');
                
                // Close mobile menu if it's open
                if (!isOpen) {
                    const menu = document.getElementById('mobileMenu');
                    if (menu) {
                        menu.classList.add('hidden');
                    }
                }
                
                if (isOpen) {
                    // Close
                    sidebar.classList.add('sidebar-mobile-hidden');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    // Open
                    sidebar.classList.remove('sidebar-mobile-hidden');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            if (!menu || menu.classList.contains('hidden')) return;
            
            // Don't close if menu was just opened
            if (!menu.dataset.menuOpen) return;
            
            const menuButton = event.target.closest('[onclick*="toggleMobileMenu"]');
            const mobileUserMenuBtn = document.getElementById('mobileUserMenuBtn');
            const mobileMenuContainer = menu.parentElement;
            
            // Check if click is outside menu and not on menu button
            if (!menu.contains(event.target) && 
                !menuButton && 
                event.target !== mobileUserMenuBtn && 
                !mobileUserMenuBtn?.contains(event.target) &&
                (!mobileMenuContainer || !mobileMenuContainer.contains(event.target) || event.target === mobileUserMenuBtn)) {
                menu.classList.add('hidden');
                menu.dataset.menuOpen = '';
                document.body.style.overflow = '';
            }
        });
        
        // Close sidebar on mobile when clicking overlay
        document.addEventListener('click', function(event) {
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay && event.target === overlay) {
                toggleMobileSidebar();
            }
        });
        
        // Close sidebar on mobile when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const menu = document.getElementById('mobileMenu');
                if (sidebar) sidebar.classList.add('sidebar-mobile-hidden');
                if (overlay) overlay.classList.add('hidden');
                if (menu) menu.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Close mobile menu/sidebar on route change (for SPA-like behavior)
        document.addEventListener('DOMContentLoaded', function() {
            // Close menus when navigating
            const links = document.querySelectorAll('nav a[href]:not([onclick])');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    const menu = document.getElementById('mobileMenu');
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (menu && !menu.classList.contains('hidden')) {
                        menu.classList.add('hidden');
                    }
                    if (window.innerWidth < 1024 && sidebar && overlay) {
                        sidebar.classList.add('sidebar-mobile-hidden');
                        overlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            });
        });
        
        // Toggle user menu dropdown
        function toggleUserMenu() {
            const dropdown = document.getElementById('userMenuDropdown');
            const chevron = document.getElementById('userMenuChevron');
            
            if (dropdown && chevron) {
                const isOpen = !dropdown.classList.contains('hidden');
                
                if (isOpen) {
                    dropdown.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                } else {
                    dropdown.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                }
            }
        }
        
        function closeUserMenu() {
            const desktopMenu = document.getElementById('userMenuDropdown');
            const mobileMenu = document.getElementById('mobileMenu');
            const chevron = document.getElementById('userMenuChevron');
            
            if (desktopMenu) desktopMenu.classList.add('hidden');
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }
            if (chevron) chevron.classList.remove('rotate-180');
        }
        
        // Toggle auth menu dropdown (for non-logged-in users)
        function toggleAuthMenu() {
            const dropdown = document.getElementById('authMenuDropdown');
            const chevron = document.getElementById('authMenuChevron');
            const userMenu = document.getElementById('userMenuDropdown');
            
            // Close user menu if open
            if (userMenu) userMenu.classList.add('hidden');
            
            if (dropdown && chevron) {
                const isOpen = !dropdown.classList.contains('hidden');
                
                if (isOpen) {
                    dropdown.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                } else {
                    dropdown.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                }
            }
        }
        
        function closeAuthMenu() {
            const dropdown = document.getElementById('authMenuDropdown');
            const chevron = document.getElementById('authMenuChevron');
            
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const dropdown = document.getElementById('userMenuDropdown');
            const authMenu = document.getElementById('authMenu');
            const authDropdown = document.getElementById('authMenuDropdown');
            
            if (userMenu && dropdown && !userMenu.contains(event.target)) {
                closeUserMenu();
            }
            
            if (authMenu && authDropdown && !authMenu.contains(event.target)) {
                closeAuthMenu();
            }
        });
        
        // Logout confirmation with SweetAlert
        function confirmLogout(event) {
            event.preventDefault();
            event.stopPropagation();
            const logoutUrl = event.currentTarget.href;
            
            closeUserMenu();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to logout from your account?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, logout!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = logoutUrl;
                    }
                });
            } else {
                // Fallback if SweetAlert is not loaded
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = logoutUrl;
                }
            }
            
            return false;
        }
    </script>
