<?php
$pageTitle = 'Home';
include VIEWS_PATH . 'partials/header.php';
?>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- ApexCharts for price comparison chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Leaflet Marker Cluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center text-white overflow-hidden">
    <!-- Background Image with Blur -->
    <div class="absolute inset-0">
        <img src="<?= BASE_URL ?>assets/images/hero.png" alt="Hero Background" class="w-full h-full object-cover blur-sm">
    </div>
    
    <!-- Darkening Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-white/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-white/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-white/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center py-20">
        <div data-aos="fade-down" data-aos-duration="800" class="mb-8 flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-white/30 rounded-full blur-2xl animate-ping"></div>
                <img src="<?= BASE_URL ?>assets/images/logowhite.png" alt="PinePix Logo" class="relative h-40 sm:h-48 md:h-56 lg:h-64 object-contain drop-shadow-2xl">
            </div>
        </div>
        
        <p data-aos="fade-up" data-aos-delay="200" class="text-xl sm:text-2xl md:text-3xl font-light mb-4 opacity-95">
            Pineapple Entrepreneur Information Management System
        </p>
        
        <p data-aos="fade-up" data-aos-delay="300" class="text-lg sm:text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto px-4 leading-relaxed">
            Connecting pineapple entrepreneurs, farms, and businesses in one unified platform
        </p>
        
        <?php if (!$auth->isLoggedIn()): ?>
            <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?= BASE_URL ?>auth/register.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold text-lg hover:bg-yellow-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-rocket mr-3 group-hover:rotate-12 transition-transform"></i>
                    Get Started Free
                </a>
                <a href="<?= BASE_URL ?>auth/login.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white/20 backdrop-blur-md text-white border-2 border-white/50 rounded-2xl font-bold text-lg hover:bg-white/30 hover:border-white transition-all duration-300 w-full sm:w-auto">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Login
                </a>
            </div>
        <?php else: ?>
            <div data-aos="fade-up" data-aos-delay="400" class="w-full sm:w-auto">
                <a href="<?= BASE_URL ?>dashboard.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold text-lg hover:bg-yellow-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-105 w-full sm:w-auto">
                    <i class="fas fa-tachometer-alt mr-3 group-hover:rotate-12 transition-transform"></i>
                    Go to Dashboard
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Scroll Indicator -->
        <div data-aos="fade-up" data-aos-delay="600" class="mt-20 animate-bounce">
            <a href="#features" class="inline-flex flex-col items-center text-white/80 hover:text-white transition">
                <span class="text-sm mb-2 font-medium">Scroll to explore</span>
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white relative">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                Powerful Features for Your Business
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Everything you need to manage and grow your pineapple business
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div data-aos="fade-up" data-aos-delay="100" class="group p-8 rounded-3xl bg-yellow-100 border-2 border-yellow-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-yellow-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-seedling text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Farm Management</h3>
                <p class="text-gray-600 leading-relaxed">Easily manage multiple farms with location tracking, size monitoring, and detailed information all in one place.</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="200" class="group p-8 rounded-3xl bg-teal-100 border-2 border-teal-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-teal-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-store text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Shop Information</h3>
                <p class="text-gray-600 leading-relaxed">Manage your shop details, contact information, and business profiles to connect with customers.</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="300" class="group p-8 rounded-3xl bg-pink-100 border-2 border-pink-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-pink-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-bullhorn text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Announcements</h3>
                <p class="text-gray-600 leading-relaxed">Stay updated with the latest prices, promotions, roadshows, and important news from the community.</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="400" class="group p-8 rounded-3xl bg-cyan-100 border-2 border-cyan-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-cyan-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-map-marked-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Interactive Maps</h3>
                <p class="text-gray-600 leading-relaxed">Explore pineapple farms across the region with our interactive map visualization.</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="500" class="group p-8 rounded-3xl bg-green-100 border-2 border-green-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-green-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-robot text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">AI Chatbot</h3>
                <p class="text-gray-600 leading-relaxed">Get instant answers to your questions with our AI-powered chatbot using advanced Gemini technology.</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="600" class="group p-8 rounded-3xl bg-orange-100 border-2 border-orange-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-orange-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Community Network</h3>
                <p class="text-gray-600 leading-relaxed">Connect with fellow entrepreneurs, share experiences, and grow your network in the pineapple industry.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gold-400 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 md:gap-8 text-center">
            <div data-aos="zoom-in" data-aos-delay="100" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= count($farms) ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">Active Farms</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="200" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= count($shops ?? []) ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">Active Shops</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="300" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= count($announcements) ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">Announcements</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="400" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= $totalEntrepreneurs ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">Entrepreneurs</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="500" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= $totalLocations ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">Locations</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="600" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    <?= $totalFAQ ?>+
                </div>
                <div class="text-lg md:text-xl opacity-90">FAQ Knowledge</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="700" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    24/7
                </div>
                <div class="text-lg md:text-xl opacity-90">AI Support</div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="800" class="group">
                <div class="text-5xl md:text-6xl font-extrabold mb-2 transform group-hover:scale-110 transition-transform">
                    100%
                </div>
                <div class="text-lg md:text-xl opacity-90">Free Access</div>
            </div>
        </div>
    </div>
</section>

<!-- Announcements Section -->
<section id="announcements" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                <i class="fas fa-bullhorn mr-3 text-primary-600"></i>Latest Announcements
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Stay updated with the latest news, prices, and promotions from the pineapple community</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php if (empty($announcements)): ?>
                <div class="col-span-full" data-aos="fade-up">
                    <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl text-gray-500">No announcements available at the moment.</p>
                        <p class="text-gray-400 mt-2">Check back later for updates!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $index => $announcement): ?>
                    <?php 
                    // Get images (support both old 'image' field and new 'images' field) - MUST be defined BEFORE using in data attributes
                    $annImages = [];
                    if (!empty($announcement['images'])) {
                        $annImages = json_decode($announcement['images'], true) ?: [];
                    } elseif (!empty($announcement['image'])) {
                        $annImages = [$announcement['image']];
                    }
                    ?>
                    <div
                        data-aos="fade-up"
                        data-aos-delay="<?= $index * 100 ?>"
                        class="group bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full flex flex-col"
                        data-announcement-id="<?= (int)$announcement['id'] ?>"
                        data-title="<?= htmlspecialchars($announcement['title'] ?? '', ENT_QUOTES) ?>"
                        data-description="<?= htmlspecialchars($announcement['description'] ?? '', ENT_QUOTES) ?>"
                        data-images="<?= htmlspecialchars(json_encode(array_map(function($img) { return BASE_URL . $img; }, $annImages)), ENT_QUOTES) ?>"
                        data-type="<?= htmlspecialchars($announcement['type'] ?? '', ENT_QUOTES) ?>"
                        data-created="<?= Helper::formatDate($announcement['created_at']) ?>"
                        data-created-by="<?= htmlspecialchars($announcement['created_by_name'] ?? 'System', ENT_QUOTES) ?>"
                    >
                        <div class="relative overflow-hidden">
                        <?php if (!empty($annImages)): ?>
                            <div class="swiper landingAnnouncementSwiper-<?= $announcement['id'] ?> w-full h-64 bg-black">
                                <div class="swiper-wrapper">
                                    <?php foreach ($annImages as $img): ?>
                                        <div class="swiper-slide flex items-center justify-center">
                                            <img src="<?= BASE_URL . $img ?>" alt="<?= htmlspecialchars_decode($announcement['title'] ?? '', ENT_QUOTES) ?>" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($annImages) > 1): ?>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-pagination"></div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                                <div class="w-full h-64 <?= $announcement['type'] === 'price' ? 'bg-green-400' : ($announcement['type'] === 'promotion' ? 'bg-amber-400' : 'bg-blue-400') ?> flex items-center justify-center relative">
                                    <i class="fas fa-bullhorn text-7xl text-white/30"></i>
                            </div>
                        <?php endif; ?>
                            <div class="absolute top-4 right-4">
                                <span class="px-4 py-2 text-xs font-bold rounded-full backdrop-blur-md shadow-lg <?= $announcement['type'] === 'price' ? 'bg-green-500/90 text-white' : ($announcement['type'] === 'promotion' ? 'bg-amber-500/90 text-white' : 'bg-blue-500/90 text-white') ?>">
                                <?= ucfirst($announcement['type']) ?>
                            </span>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h5 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">
                                <?= htmlspecialchars_decode($announcement['title'] ?? '', ENT_QUOTES) ?>
                            </h5>
                            <p class="text-gray-600 flex-grow mb-6 leading-relaxed line-clamp-3">
                                <?= htmlspecialchars_decode(substr($announcement['description'] ?? '', 0, 120), ENT_QUOTES) ?><?= strlen($announcement['description'] ?? '') > 120 ? '...' : '' ?>
                            </p>
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                <small class="text-gray-500 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>
                                    <?= Helper::formatDate($announcement['created_at']) ?>
                                </small>
                                <button
                                    type="button"
                                    class="announcement-read-more text-primary-600 font-semibold group-hover:translate-x-1 transition-transform inline-flex items-center focus:outline-none"
                                >
                                    Read more <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" data-aos="fade-up">
            <a href="<?= BASE_URL ?>announcements.php" class="group inline-flex items-center px-8 py-4 bg-primary-400 text-white rounded-2xl font-bold text-lg hover:bg-primary-500 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 hover:scale-105">
                <span>View All Announcements</span>
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<!-- Current Market Price Section -->
<section class="py-20 bg-gradient-to-br from-green-50 via-yellow-50 to-amber-50 relative overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                <i class="fas fa-chart-line mr-3 text-green-600"></i>Current Market Prices
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Stay updated with the latest pineapple prices across Malaysia</p>
        </div>
        
        <div class="max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-white rounded-3xl shadow-2xl border-2 border-green-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-pineapple mr-3"></i>
                        NENAS BIASA (JOSAPINE / MORRIS / SARAWAK)
                    </h3>
                </div>
                
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 rounded-2xl p-6 border-2 border-green-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Average Price</span>
                                <i class="fas fa-tag text-green-600 text-xl"></i>
                            </div>
                            <div class="text-4xl font-extrabold text-green-700 mb-1" id="currentPrice">RM<?= number_format($currentPriceData['price'], 2) ?></div>
                            <div class="text-sm text-gray-600" id="priceInfo">
                                <?= $currentPriceData['unit'] ?> 
                                <?php if ($currentPriceData['week'] && $currentPriceData['year']): ?>
                                    (Week <?= $currentPriceData['week'] ?>, <?= $currentPriceData['year'] ?>)
                                <?php endif; ?>
                            </div>
                            <button onclick="refreshPrice()" class="mt-2 text-xs text-green-600 hover:text-green-700 font-semibold flex items-center">
                                <i class="fas fa-sync-alt mr-1" id="refreshIcon"></i>
                                Refresh Price
                            </button>
                        </div>
            
                        <div class="bg-amber-50 rounded-2xl p-6 border-2 border-amber-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Data Source</span>
                                <i class="fas fa-database text-amber-600 text-xl"></i>
                            </div>
                            <div class="text-lg font-bold text-amber-700 mb-1">
                                <?= implode(' & ', $currentPriceData['data_sources']) ?>
                            </div>
                            <div class="text-sm text-gray-600" id="lastUpdated">
                                Last updated: <?= $currentPriceData['update_date'] ?: date('d M Y', strtotime($currentPriceData['last_updated'])) ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($currentPriceData['state_averages'])): ?>
                        <?php
                        // Sort state averages by price ascending
                        $stateAveragesSorted = $currentPriceData['state_averages'];
                        usort($stateAveragesSorted, function($a, $b) {
                            $aPrice = is_array($a) ? (float)($a['average_price'] ?? 0) : 0;
                            $bPrice = is_array($b) ? (float)($b['average_price'] ?? 0) : 0;
                            return $aPrice <=> $bPrice;
                        });
                        ?>
                        <!-- State Average Prices Table (English, sorted ascending) -->
                            <div class="mb-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-map-marked-alt text-green-600 mr-2"></i>
                                    Average Price by State
                                </h4>
                                <div class="overflow-x-auto rounded-xl border-2 border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                                        <thead class="bg-green-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">State</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Average Price (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php foreach ($stateAveragesSorted as $avg): ?>
                                                <?php 
                                                // Handle both array formats (from cache/API vs database)
                                                $state = is_array($avg) ? ($avg['state'] ?? 'N/A') : 'N/A';
                                                $avgPrice = is_array($avg) ? ($avg['average_price'] ?? 0) : 0;
                                                ?>
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900"><?= htmlspecialchars($state) ?></td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 font-medium">RM<?= number_format($avgPrice, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- State Price Comparison Chart -->
                            <div class="mb-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                    State Price Comparison
                                </h4>
                                <div id="statePriceChart" class="w-full h-80"></div>
                            </div>
                    <?php endif; ?>
                    
                    <div class="bg-blue-50 rounded-2xl p-6 border-2 border-blue-200 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">Price Information</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    The average price shown is based on nationwide monitoring data from <strong>PriceCatcher KPDN</strong> and <strong>Open DOSM</strong> 
                                    <?php if ($currentPriceData['week'] && $currentPriceData['year']): ?>
                                        for week <?= $currentPriceData['week'] ?> of <?= $currentPriceData['year'] ?>.
                                    <?php else: ?>
                                        for the latest available period.
                                    <?php endif; ?>
                                    Prices may vary by location and market conditions.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="https://manamurah.com/barang/nenas_biasa_josapine-25" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="group inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View Full Price Details
                        </a>
                        <?php if ($latestPrice): ?>
                        <a href="<?= BASE_URL ?>announcements.php?view=<?= $latestPrice['id'] ?>" 
                           class="group inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white rounded-xl font-semibold hover:bg-primary-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Latest Price Update
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            <span id="updateTimestamp">Last updated: <?= $currentPriceData['update_date'] ?: date('d M Y', strtotime($currentPriceData['last_updated'])) ?></span> | 
                            <a href="https://manamurah.com" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:underline">ManaMurah.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Announcement Lightbox Modal -->
<div id="landingAnnouncementModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-3 sm:p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <span id="landingAnnouncementTypeBadge" class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"></span>
            </div>
            <button type="button" id="landingAnnouncementClose" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6 space-y-4">
            <div class="w-full bg-black flex items-center justify-center rounded-xl overflow-hidden">
                <div id="landingAnnouncementSwiper" class="swiper w-full max-h-80 sm:max-h-[24rem]">
                    <div class="swiper-wrapper"></div>
                    <div class="swiper-button-next text-white"></div>
                    <div class="swiper-button-prev text-white"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="space-y-2">
                <h3 id="landingAnnouncementTitle" class="text-xl sm:text-2xl font-bold text-gray-900"></h3>
                <p id="landingAnnouncementMeta" class="text-xs sm:text-sm text-gray-500"></p>
            </div>
            <p id="landingAnnouncementDescription" class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line"></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('landingAnnouncementModal');
    const closeBtn = document.getElementById('landingAnnouncementClose');
    const swiperWrapper = document.querySelector('#landingAnnouncementSwiper .swiper-wrapper');
    const titleEl = document.getElementById('landingAnnouncementTitle');
    const metaEl = document.getElementById('landingAnnouncementMeta');
    const descEl = document.getElementById('landingAnnouncementDescription');
    const typeBadge = document.getElementById('landingAnnouncementTypeBadge');
    let landingSwiper = null;

    if (!modal) return;

    function decodeHtml(html) {
        const txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }

    function openModalFromCard(card) {
        if (!card) return;

        const title = decodeHtml(card.dataset.title || '');
        const description = decodeHtml(card.dataset.description || '');
        const imagesJson = card.dataset.images || '';
        const type = (card.dataset.type || '').toLowerCase();
        const created = card.dataset.created || '';
        const createdBy = decodeHtml(card.dataset.createdBy || 'System');

        titleEl.textContent = title;
        descEl.textContent = description;

        // Clear and populate carousel
        if (swiperWrapper) {
            swiperWrapper.innerHTML = '';
            
            let images = [];
            if (imagesJson) {
                try {
                    images = JSON.parse(imagesJson);
                } catch (e) {
                    // Fallback: treat as single image string
                    if (imagesJson) images = [imagesJson];
                }
            }
            
            if (images.length > 0) {
                images.forEach(img => {
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide flex items-center justify-center';
                    slide.innerHTML = `<img src="${img}" alt="${title}" class="w-full h-full object-contain">`;
                    swiperWrapper.appendChild(slide);
                });
                
                // Initialize or update Swiper
                if (landingSwiper) {
                    landingSwiper.destroy(true, true);
                }
                landingSwiper = new Swiper('#landingAnnouncementSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: images.length > 1,
                    navigation: {
                        nextEl: '#landingAnnouncementSwiper .swiper-button-next',
                        prevEl: '#landingAnnouncementSwiper .swiper-button-prev',
                    },
                    pagination: {
                        el: '#landingAnnouncementSwiper .swiper-pagination',
                        clickable: true,
                    },
                });
            }
        }

        // Type badge styling
        typeBadge.textContent = type ? type.charAt(0).toUpperCase() + type.slice(1) : 'Announcement';
        typeBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full ' + (
            type === 'price'
                ? 'bg-green-100 text-green-800'
                : (type === 'promotion'
                    ? 'bg-amber-100 text-amber-800'
                    : 'bg-blue-100 text-blue-800')
        );

        metaEl.textContent = created
            ? `Posted by ${createdBy} • ${created}`
            : `Posted by ${createdBy}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Initialize Swiper for announcement cards on landing page
    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($announcements as $ann): ?>
            <?php 
            $annImages = [];
            if (!empty($ann['images'])) {
                $annImages = json_decode($ann['images'], true) ?: [];
            } elseif (!empty($ann['image'])) {
                $annImages = [$ann['image']];
            }
            ?>
            <?php if (!empty($annImages) && count($annImages) > 1): ?>
                new Swiper('.landingAnnouncementSwiper-<?= $ann['id'] ?>', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.landingAnnouncementSwiper-<?= $ann['id'] ?> .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.landingAnnouncementSwiper-<?= $ann['id'] ?> .swiper-button-next',
                        prevEl: '.landingAnnouncementSwiper-<?= $ann['id'] ?> .swiper-button-prev',
                    },
                });
            <?php endif; ?>
        <?php endforeach; ?>
    });

    document.querySelectorAll('.announcement-read-more').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const card = e.target.closest('[data-announcement-id]');
            openModalFromCard(card);
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>

<!-- Map Section -->
<section class="py-20 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                <i class="fas fa-map-marked-alt mr-3 text-primary-600"></i>Explore Farms & Shops
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover pineapple farms and shops across the region on our interactive map</p>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="200" class="relative">
            <div id="farmMap" class="rounded-3xl border-2 border-gray-200 shadow-2xl overflow-hidden" style="height: 600px; min-height: 600px; width: 100%; position: relative; z-index: 1;"></div>
            
            <!-- State Filter - Top Left (Collapsible) -->
            <div class="absolute top-4 left-4 z-[1000] flex flex-col space-y-3">
                <!-- Filter toggle icon -->
                <button id="stateFilterToggle" class="bg-white/95 backdrop-blur-md rounded-full p-3 shadow-xl border border-gray-200 flex items-center justify-center text-primary-600 hover:bg-primary-50 hover:text-primary-700 transition focus:outline-none" title="Filter by State" aria-label="Filter by State">
                    <i class="fas fa-filter"></i>
                </button>
                <!-- Filter panel -->
                <div id="stateFilterPanel" class="bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-gray-200 hidden">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-filter text-primary-600 mr-2"></i>
                    Filter by State
                </h3>
                <select id="stateFilter" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition text-sm font-medium">
                    <option value="all">All States</option>
                    <option value="johor">Johor</option>
                    <option value="kedah">Kedah</option>
                    <option value="kelantan">Kelantan</option>
                    <option value="melaka">Melaka</option>
                    <option value="negeri-sembilan">Negeri Sembilan</option>
                    <option value="pahang">Pahang</option>
                    <option value="penang">Penang</option>
                    <option value="perak">Perak</option>
                    <option value="perlis">Perlis</option>
                    <option value="sabah">Sabah</option>
                    <option value="sarawak">Sarawak</option>
                    <option value="selangor">Selangor</option>
                    <option value="terengganu">Terengganu</option>
                    <option value="kl">Kuala Lumpur</option>
                    <option value="labuan">Labuan</option>
                    <option value="putrajaya">Putrajaya</option>
                </select>
            </div>
            </div>
            
            <!-- Help and Legend Container - Right side, stacked (Collapsible) -->
            <div class="absolute bottom-4 right-4 flex flex-col items-end space-y-3 z-[1000]">
                <!-- Icon group for Help & Legend -->
                <div class="bg-white/95 backdrop-blur-md rounded-2xl p-2 shadow-lg border border-gray-200 flex items-center space-x-2">
                    <button id="mapInfoToggle" class="w-9 h-9 flex items-center justify-center rounded-full text-primary-600 hover:bg-primary-50 hover:text-primary-700 transition focus:outline-none" title="Map Help" aria-label="Map Help">
                        <i class="fas fa-question-circle"></i>
                    </button>
                    <button id="mapLegendToggle" class="w-9 h-9 flex items-center justify-center rounded-full text-primary-600 hover:bg-primary-50 hover:text-primary-700 transition focus:outline-none" title="Map Legend" aria-label="Map Legend">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <button id="currentLocationBtn" class="w-9 h-9 flex items-center justify-center rounded-full text-primary-600 hover:bg-primary-50 hover:text-primary-700 transition focus:outline-none" title="My Location" aria-label="My Location">
                        <i class="fas fa-crosshairs"></i>
                    </button>
                </div>
                
                <!-- Map Instructions Panel (Hidden by default) -->
                <div id="mapInstructions" class="bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-lg max-w-xs border border-gray-200 hidden">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Map Instructions</h4>
                    <ul class="text-xs text-gray-600 space-y-1.5">
                        <li class="flex items-start">
                            <i class="fas fa-mouse-pointer text-primary-400 mr-2 mt-0.5"></i>
                            <span>Click on markers to view farm details</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-search-plus text-primary-400 mr-2 mt-0.5"></i>
                            <span>Use zoom controls to explore</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-hand-paper text-primary-400 mr-2 mt-0.5"></i>
                            <span>Drag to pan around the map</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-expand text-primary-400 mr-2 mt-0.5"></i>
                            <span>Use fullscreen for better view</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Custom Legend (Hidden by default) -->
                <div id="mapLegendPanel" class="bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-xl max-w-xs border border-gray-200 hidden">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        Map Legend
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-green-500 border-2 border-white shadow-md flex items-center justify-center" style="border-radius: 50% 50% 50% 0; transform: rotate(-45deg);">
                                <div style="transform: rotate(45deg);">
                                    <i class="fas fa-seedling text-white text-xs"></i>
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium">Large Farm (≥10 acres)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-blue-500 border-2 border-white shadow-md flex items-center justify-center" style="border-radius: 50% 50% 50% 0; transform: rotate(-45deg);">
                                <div style="transform: rotate(45deg);">
                                    <i class="fas fa-leaf text-white text-xs"></i>
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium">Medium Farm (5-9 acres)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-yellow-500 border-2 border-white shadow-md flex items-center justify-center" style="border-radius: 50% 50% 50% 0; transform: rotate(-45deg);">
                                <div style="transform: rotate(45deg);">
                                    <i class="fas fa-home text-white text-xs"></i>
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium">Small Farm (&lt;5 acres)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-primary-400 border-2 border-white shadow-md flex items-center justify-center" style="border-radius: 50% 50% 50% 0; transform: rotate(-45deg);">
                                <div style="transform: rotate(45deg);">
                                    <i class="fas fa-map-pin text-white text-xs"></i>
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium">Unknown Size</span>
                        </div>
                        <div class="flex items-center space-x-3 mt-2 pt-2 border-t border-gray-200">
                            <div class="w-7 h-7 rounded-full bg-purple-500 border-2 border-white shadow-md flex items-center justify-center" style="border-radius: 50% 50% 50% 0; transform: rotate(-45deg);">
                                <div style="transform: rotate(45deg);">
                                    <i class="fas fa-store text-white text-xs"></i>
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium">Shop</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>Total Farms</span>
                            <span class="font-bold text-primary-600"><?= count($farms) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Total Shops</span>
                            <span class="font-bold text-purple-600"><?= count($shops ?? []) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if (!$auth->isLoggedIn()): ?>
<section class="py-20 bg-primary-400 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
        <h2 class="text-4xl sm:text-5xl font-bold mb-6">Ready to Get Started?</h2>
        <p class="text-xl mb-10 opacity-90 max-w-2xl mx-auto">Join our community today and start managing your pineapple business efficiently.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>auth/register.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold text-lg hover:bg-yellow-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-105">
                <i class="fas fa-user-plus mr-3"></i>
                Create Free Account
            </a>
            <a href="<?= BASE_URL ?>auth/login.php" class="group inline-flex items-center justify-center px-8 py-4 bg-white/20 backdrop-blur-md text-white border-2 border-white/50 rounded-2xl font-bold text-lg hover:bg-white/30 hover:border-white transition-all duration-300">
                <i class="fas fa-sign-in-alt mr-3"></i>
                Login to Account
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 100
});

// Wait for DOM and Leaflet to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Leaflet to be available
    function initMap() {
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            return;
        }
        
        const mapContainer = document.getElementById('farmMap');
        if (!mapContainer) {
            console.error('Map container not found');
            return;
        }
        
        // Check if map is already initialized
        if (mapContainer._leaflet_id) {
            return; // Map already initialized
        }
        
        try {
// Initialize map
            const map = L.map('farmMap', {
                zoomControl: false,
                attributionControl: false,
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                keyboard: true,
                dragging: true
            }).setView([3.1390, 101.6869], 7);
            
            // Base tile layers
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                subdomains: 'abc'
            });
            
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© <a href="https://www.esri.com/">Esri</a>',
                maxZoom: 19
            });
            
            // Add default layer
            osmLayer.addTo(map);
            
            // Layer control
            const baseMaps = {
                "Street Map": osmLayer,
                "Satellite": satelliteLayer
            };
            L.control.layers(baseMaps).addTo(map);
            
            // Scale control
            L.control.scale({
                imperial: false,
                metric: true,
                position: 'bottomleft'
}).addTo(map);
            
            // Helper function to parse farm size from various formats
            function parseFarmSize(sizeStr) {
                if (!sizeStr) return 0;
                // Extract number from string (handles "10 acres", "5 hectares", "10", etc.)
                const match = sizeStr.toString().match(/(\d+\.?\d*)/);
                if (match) {
                    let size = parseFloat(match[1]);
                    // Convert hectares to acres if needed (1 hectare = 2.471 acres)
                    if (sizeStr.toLowerCase().includes('hectare')) {
                        size = size * 2.471;
                    }
                    return size;
                }
                return 0;
            }
            
            // Helper function to get farm size category and icon
            function getFarmIcon(farm) {
                const farmSize = parseFarmSize(farm.farm_size);
                let iconColor, iconClass, sizeLabel;
                
                if (farmSize >= 10) {
                    iconColor = '#10b981'; // green-500
                    iconClass = 'fa-seedling';
                    sizeLabel = 'Large';
                } else if (farmSize >= 5) {
                    iconColor = '#3b82f6'; // blue-500
                    iconClass = 'fa-farm';
                    sizeLabel = 'Medium';
                } else if (farmSize > 0) {
                    iconColor = '#eab308'; // yellow-500
                    iconClass = 'fa-home';
                    sizeLabel = 'Small';
                } else {
                    iconColor = '#fbbf24'; // primary-400
                    iconClass = 'fa-map-pin';
                    sizeLabel = 'Unknown';
                }
                
                return {
                    icon: L.divIcon({
                        className: 'custom-farm-marker',
                        html: `<div style="background: ${iconColor}; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); cursor: pointer; transition: transform 0.2s;">
                            <div style="transform: rotate(45deg); display: flex; align-items: center; justify-content: center; height: 100%;">
                                <i class="fas ${iconClass}" style="color: white; font-size: 14px;"></i>
                            </div>
                        </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    }),
                    size: farmSize,
                    category: sizeLabel
                };
            }
            
            // Helper function to get shop icon
            function getShopIcon() {
                return {
                    icon: L.divIcon({
                        className: 'custom-shop-marker',
                        html: `<div style="background: #8b5cf6; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); cursor: pointer; transition: transform 0.2s;">
                            <div style="transform: rotate(45deg); display: flex; align-items: center; justify-content: center; height: 100%;">
                                <i class="fas fa-store" style="color: white; font-size: 14px;"></i>
                            </div>
                        </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    })
                };
            }
            
            // Check if MarkerClusterGroup is available
            const useClustering = typeof L.markerClusterGroup !== 'undefined';
            
            // Create marker cluster group for better performance (if available)
            let markers = useClustering ? L.markerClusterGroup({
                maxClusterRadius: 60,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                iconCreateFunction: function(cluster) {
                    const count = cluster.getChildCount();
                    let sizeClass = 'small';
                    let size = 40;
                    if (count > 20) {
                        sizeClass = 'large';
                        size = 50;
                    } else if (count > 10) {
                        sizeClass = 'medium';
                        size = 45;
                    }
                    
                    return L.divIcon({
                        html: `<div style="background: #f59e0b; color: white; border-radius: 50%; width: ${size}px; height: ${size}px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: ${count > 99 ? '12px' : '14px'}; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3);">${count}</div>`,
                        className: 'marker-cluster marker-cluster-' + sizeClass,
                        iconSize: L.point(size, size)
                    });
                }
            }) : L.layerGroup();

// Add farm markers
<?php if (!empty($farms)): ?>
    const farms = <?= json_encode($farms) ?>;
    farms.forEach(function(farm) {
        if (farm.latitude && farm.longitude) {
                        const farmData = getFarmIcon(farm);
                        const farmSize = farmData.size;
                        const farmSizeText = farmSize > 0 ? `${farmSize.toFixed(1)} acres` : (farm.farm_size || 'Size not specified');
                        
                        const marker = L.marker([parseFloat(farm.latitude), parseFloat(farm.longitude)], {
                            icon: farmData.icon
                        });
                        
                        // Simple one-colored box popup
                        const popupContent = `
                            <div style="background: #f59e0b; color: white; padding: 16px; border-radius: 8px; min-width: 200px; max-width: 250px;">
                                <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">${farm.farm_name || 'Unnamed Farm'}</div>
                                <div style="font-size: 13px; margin-bottom: 4px;">Owner: ${farm.farmer_name || 'Unknown'}</div>
                                <div style="font-size: 13px; margin-bottom: 4px;">Size: ${farmSizeText}</div>
                                ${farm.address ? `<div style="font-size: 12px; margin-bottom: 8px; opacity: 0.9;">${farm.address}</div>` : ''}
                                <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=${parseFloat(farm.latitude)},${parseFloat(farm.longitude)}', '_blank')" 
                                        style="background: white; color: #f59e0b; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; width: 100%; margin-top: 8px;">
                                    Get Directions
                                </button>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent, {
                            className: 'custom-popup',
                            maxWidth: 320
                        });
                        
                        // Zoom to marker when clicked
                        marker.on('click', function() {
                            map.setView([parseFloat(farm.latitude), parseFloat(farm.longitude)], 14, {
                                animate: true,
                                duration: 0.5
                            });
                        });
                        
                        markers.addLayer(marker);
                    }
                });
<?php endif; ?>
                
// Add shop markers
<?php if (!empty($shops)): ?>
    const shops = <?= json_encode($shops) ?>;
    shops.forEach(function(shop) {
        if (shop.latitude && shop.longitude) {
            const shopData = getShopIcon();
            
            const marker = L.marker([parseFloat(shop.latitude), parseFloat(shop.longitude)], {
                icon: shopData.icon
            });
            
            // Shop popup content
            const popupContent = `
                <div style="background: #8b5cf6; color: white; padding: 16px; border-radius: 8px; min-width: 200px; max-width: 250px;">
                    <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">${shop.shop_name || 'Unnamed Shop'}</div>
                    <div style="font-size: 13px; margin-bottom: 4px;">Owner: ${shop.shop_owner_name || 'Unknown'}</div>
                    ${shop.operation_hours ? `<div style="font-size: 13px; margin-bottom: 4px;"><i class="fas fa-clock"></i> ${shop.operation_hours}</div>` : ''}
                    ${shop.contact ? `<div style="font-size: 13px; margin-bottom: 4px;"><i class="fas fa-phone"></i> ${shop.contact}</div>` : ''}
                    ${shop.address ? `<div style="font-size: 12px; margin-bottom: 8px; opacity: 0.9;">${shop.address}</div>` : ''}
                    <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=${parseFloat(shop.latitude)},${parseFloat(shop.longitude)}', '_blank')" 
                            style="background: white; color: #8b5cf6; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; width: 100%; margin-top: 8px;">
                        Get Directions
                    </button>
                </div>
            `;
            
            marker.bindPopup(popupContent, {
                className: 'custom-popup',
                maxWidth: 320
            });
            
            // Zoom to marker when clicked
            marker.on('click', function() {
                map.setView([parseFloat(shop.latitude), parseFloat(shop.longitude)], 14, {
                    animate: true,
                    duration: 0.5
                });
            });
            
            markers.addLayer(marker);
        }
    });
<?php endif; ?>
                
                map.addLayer(markers);
                
                // Fit map to show all markers (farms and shops) with padding
                const allLocations = [];
                <?php if (!empty($farms)): ?>
                farms.forEach(function(f) {
                    if (f.latitude && f.longitude) {
                        allLocations.push([parseFloat(f.latitude), parseFloat(f.longitude)]);
                    }
                });
                <?php endif; ?>
                <?php if (!empty($shops)): ?>
                shops.forEach(function(s) {
                    if (s.latitude && s.longitude) {
                        allLocations.push([parseFloat(s.latitude), parseFloat(s.longitude)]);
                    }
                });
                <?php endif; ?>
                
                if (allLocations.length > 0) {
                        setTimeout(function() {
                        map.fitBounds(allLocations, { padding: [50, 50], maxZoom: 12 });
                            map.invalidateSize();
                        }, 500);
                } else {
                    // Default view for Malaysia if no locations
                map.setView([3.1390, 101.6869], 7);
                }
            
            // Ensure map renders properly after a short delay
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
            
            // Malaysian States Coordinates (approximate centers)
            const malaysianStates = {
                'all': { center: [3.1390, 101.6869], zoom: 10, bounds: null },
                'johor': { center: [1.4927, 103.7414], zoom: 25, bounds: [[1.0, 102.5], [2.5, 104.5]] },
                'kedah': { center: [6.1254, 100.3673], zoom: 25, bounds: [[5.0, 99.5], [6.8, 101.0]] },
                'kelantan': { center: [6.1254, 102.2386], zoom: 25, bounds: [[4.5, 101.0], [6.5, 103.5]] },
                'melaka': { center: [2.1896, 102.2501], zoom: 25, bounds: [[2.0, 102.0], [2.4, 102.5]] },
                'negeri-sembilan': { center: [2.7258, 101.9378], zoom: 25, bounds: [[2.3, 101.5], [3.2, 102.5]] },
                'pahang': { center: [3.8077, 103.3260], zoom: 25, bounds: [[2.5, 101.0], [4.5, 104.5]] },
                'penang': { center: [5.4164, 100.3327], zoom: 25, bounds: [[5.1, 100.1], [5.6, 100.5]] },
                'perak': { center: [4.5921, 101.0901], zoom: 25, bounds: [[3.8, 100.0], [5.5, 101.5]] },
                'perlis': { center: [6.4443, 100.2048], zoom: 25, bounds: [[6.2, 99.8], [6.7, 100.5]] },
                'sabah': { center: [5.9804, 116.0735], zoom: 25, bounds: [[4.0, 115.0], [7.5, 119.0]] },
                'sarawak': { center: [2.5000, 112.5000], zoom: 25, bounds: [[0.5, 109.0], [4.5, 115.5]] },
                'selangor': { center: [3.0738, 101.5183], zoom: 25, bounds: [[2.5, 100.8], [3.8, 102.0]] },
                'terengganu': { center: [5.3117, 103.1324], zoom: 25, bounds: [[4.0, 102.5], [6.0, 103.8]] },
                'kl': { center: [3.1390, 101.6869], zoom: 25, bounds: [[3.0, 101.5], [3.3, 101.8]] },
                'labuan': { center: [5.2831, 115.2308], zoom: 25, bounds: [[5.2, 115.1], [5.4, 115.3]] },
                'putrajaya': { center: [2.9264, 101.6964], zoom: 25, bounds: [[2.9, 101.6], [2.95, 101.7]] }
            };
            
            // State filter functionality
            const stateFilter = document.getElementById('stateFilter');
            if (stateFilter) {
                stateFilter.addEventListener('change', function() {
                    const selectedState = this.value;
                    const stateData = malaysianStates[selectedState];
                    
                    if (stateData) {
                        if (selectedState === 'all') {
                            // Show all farms and fit bounds
                            <?php if (!empty($farms)): ?>
                                const allBounds = farms
                                    .filter(f => f.latitude && f.longitude)
                                    .map(f => [parseFloat(f.latitude), parseFloat(f.longitude)]);
                                if (allBounds.length > 0) {
                                    map.fitBounds(allBounds, { padding: [50, 50], maxZoom: 8 });
                                } else {
                                    map.setView(stateData.center, stateData.zoom);
                                }
                            <?php else: ?>
                                map.setView(stateData.center, stateData.zoom);
                            <?php endif; ?>
                            
                            // Show all markers
                            markers.eachLayer(function(marker) {
                                if (marker.setOpacity) marker.setOpacity(1);
                            });
                        } else {
                            // Zoom to selected state with closer zoom
                            if (stateData.bounds) {
                                map.fitBounds(stateData.bounds, { padding: [30, 30], maxZoom: 15 });
                            } else {
                                map.setView(stateData.center, 12);
                            }
                            
                            // Filter markers to show only those in the state bounds
                            if (stateData.bounds) {
                                const bounds = L.latLngBounds(stateData.bounds);
                                markers.eachLayer(function(marker) {
                                    // Handle both regular markers and cluster groups
                                    if (marker.getLatLng) {
                                        const latlng = marker.getLatLng();
                                        if (bounds.contains(latlng)) {
                                            if (marker.setOpacity) marker.setOpacity(1);
                                        } else {
                                            if (marker.setOpacity) marker.setOpacity(0.3);
                                        }
                                    } else if (marker.getAllChildMarkers) {
                                        // Handle marker clusters
                                        const childMarkers = marker.getAllChildMarkers();
                                        let hasVisibleChild = false;
                                        childMarkers.forEach(function(child) {
                                            if (child.getLatLng && bounds.contains(child.getLatLng())) {
                                                hasVisibleChild = true;
                                            }
                                        });
                                        if (hasVisibleChild) {
                                            if (marker.setOpacity) marker.setOpacity(1);
                                        } else {
                                            if (marker.setOpacity) marker.setOpacity(0.3);
                                        }
                                    }
                                });
                            }
                        }
                        
                        // Update map size after zoom
                        setTimeout(function() {
                            map.invalidateSize();
                        }, 300);
                    }
                });
            }
            
            // Toggle map instructions
            const infoToggle = document.getElementById('mapInfoToggle');
            const instructions = document.getElementById('mapInstructions');
            if (infoToggle && instructions) {
                infoToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    instructions.classList.toggle('hidden');
                });
                
                // Close instructions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!instructions.contains(e.target) && !infoToggle.contains(e.target)) {
                        instructions.classList.add('hidden');
                    }
                });
            }

            // Toggle map legend
            const legendToggle = document.getElementById('mapLegendToggle');
            const legendPanel = document.getElementById('mapLegendPanel');
            if (legendToggle && legendPanel) {
                legendToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    legendPanel.classList.toggle('hidden');
                });
                
                // Close legend when clicking outside
                document.addEventListener('click', function(e) {
                    if (!legendPanel.contains(e.target) && !legendToggle.contains(e.target)) {
                        legendPanel.classList.add('hidden');
                    }
                });
            }

            // Toggle state filter panel
            const stateFilterToggle = document.getElementById('stateFilterToggle');
            const stateFilterPanel = document.getElementById('stateFilterPanel');
            if (stateFilterToggle && stateFilterPanel) {
                stateFilterToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    stateFilterPanel.classList.toggle('hidden');
                });
                
                // Close filter panel when clicking outside
                document.addEventListener('click', function(e) {
                    if (!stateFilterPanel.contains(e.target) && !stateFilterToggle.contains(e.target)) {
                        stateFilterPanel.classList.add('hidden');
                    }
                });
            }
            
            // Current location functionality
            let userLocationMarker = null;
            let userLocationCircle = null;
            const currentLocationBtn = document.getElementById('currentLocationBtn');
            if (currentLocationBtn) {
                currentLocationBtn.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser.');
                        return;
                    }
                    
                    // Show loading state
                    const btnIcon = currentLocationBtn.querySelector('i');
                    const originalClass = btnIcon.className;
                    btnIcon.className = 'fas fa-spinner fa-spin text-primary-600';
                    currentLocationBtn.disabled = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Remove existing user location marker and circle if any
                            if (userLocationMarker) {
                                map.removeLayer(userLocationMarker);
                            }
                            if (userLocationCircle) {
                                map.removeLayer(userLocationCircle);
                            }
                            
                            // Create custom user location marker
                            const userIcon = L.divIcon({
                                className: 'user-location-marker',
                                html: `<div style="background: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 4px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                            
                            // Add user location marker
                            userLocationMarker = L.marker([lat, lng], { icon: userIcon });
                            userLocationMarker.addTo(map);
                            
                            // Add pulsing circle
                            userLocationCircle = L.circle([lat, lng], {
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.2,
                                radius: 100
                            }).addTo(map);
                            
                            // Zoom to user location
                            map.setView([lat, lng], 14, {
                                animate: true,
                                duration: 0.5
                            });
                            
                            // Reset button state
                            btnIcon.className = originalClass;
                            currentLocationBtn.disabled = false;
                        },
                        function(error) {
                            alert('Unable to retrieve your location. Please make sure location services are enabled.');
                            btnIcon.className = originalClass;
                            currentLocationBtn.disabled = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                });
            }
            
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }
    
    // Try to initialize immediately
    if (typeof L !== 'undefined') {
        initMap();
    } else {
        // Wait for Leaflet to load
        let attempts = 0;
        const checkLeaflet = setInterval(function() {
            attempts++;
            if (typeof L !== 'undefined') {
                clearInterval(checkLeaflet);
                initMap();
            } else if (attempts > 50) {
                clearInterval(checkLeaflet);
                console.error('Leaflet library failed to load after 5 seconds');
            }
        }, 100);
    }
});

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<style>
#farmMap {
    width: 100% !important;
    height: 600px !important;
    min-height: 600px !important;
    display: block !important;
    position: relative;
}

#farmMap .leaflet-container {
    width: 100%;
    height: 100%;
    z-index: 1;
    font-family: inherit;
}

.custom-popup {
    border-radius: 8px !important;
}

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 8px !important;
    padding: 0 !important;
    box-shadow: none !important;
    background: transparent !important;
}

.custom-popup .leaflet-popup-content {
    margin: 0 !important;
    line-height: 1.5;
}

.custom-popup .leaflet-popup-tip {
    background: #f59e0b !important;
}

.custom-farm-marker {
    background: transparent !important;
    border: none !important;
}

/* Marker Cluster Styles */
.marker-cluster {
    background: rgba(245, 158, 11, 0.6) !important;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.3);
}

.marker-cluster div {
    background: #f59e0b !important;
    color: white !important;
    font-weight: bold;
}

.leaflet-control-layers {
    background: white !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    border: 1px solid #e5e7eb !important;
}

.leaflet-control-layers-toggle {
    background-image: none !important;
    background-color: white !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    width: 36px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.leaflet-control-layers-toggle:before {
    content: '\f0c9';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: #374151;
    font-size: 16px;
}

.leaflet-control-scale {
    background: rgba(255, 255, 255, 0.9) !important;
    border-radius: 4px !important;
    padding: 4px 8px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    border: 1px solid #e5e7eb !important;
}

/* Legend and Control Styling */
.legend-control {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.line-clamp-3 {
    display: -webkit-box;
    line-clamp: 3;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Popup button hover effect */
.custom-popup button:hover {
    background: #f3f4f6 !important;
    transition: all 0.2s;
}
</style>

<script>
// Refresh price data
async function refreshPrice() {
    const refreshIcon = document.getElementById('refreshIcon');
    const currentPrice = document.getElementById('currentPrice');
    const priceInfo = document.getElementById('priceInfo');
    const lastUpdated = document.getElementById('lastUpdated');
    const updateTimestamp = document.getElementById('updateTimestamp');
    
    if (!refreshIcon) return;
    
    // Show loading
    refreshIcon.classList.add('fa-spin');
    
    try {
        const response = await fetch('<?= BASE_URL ?>api/fetch-price.php?action=fetch');
        const result = await response.json();
        
        if (result.success && result.data) {
            const data = result.data;
            if (currentPrice) {
                currentPrice.textContent = 'RM' + parseFloat(data.price).toFixed(2);
            }
            
            if (priceInfo) {
                let infoText = data.unit || 'per piece';
                if (data.week && data.year) {
                    infoText += ' (Week ' + data.week + ', ' + data.year + ')';
                }
                priceInfo.textContent = infoText;
            }
            
            const updateText = data.update_date || new Date(data.last_updated).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            if (lastUpdated) {
                lastUpdated.textContent = 'Last updated: ' + updateText;
            }
            if (updateTimestamp) {
                updateTimestamp.textContent = 'Last updated: ' + updateText;
            }
            
            // Reload page to show updated state tables
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Price Updated!',
                    text: 'Latest price data has been fetched successfully. Refreshing page...',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } else {
            throw new Error(result.error || 'Failed to fetch price');
        }
    } catch (error) {
        console.error('Error fetching price:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'Could not fetch latest price. Please try again later.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    } finally {
        refreshIcon.classList.remove('fa-spin');
    }
}

// State price comparison chart (ApexCharts)
document.addEventListener('DOMContentLoaded', function() {
    const chartEl = document.getElementById('statePriceChart');
    if (!chartEl || typeof ApexCharts === 'undefined') {
        return;
    }

    const stateAvgData = <?php
    $chartStateAverages = $currentPriceData['state_averages'] ?? [];
    if (!empty($chartStateAverages)) {
        usort($chartStateAverages, function($a, $b) {
            $aPrice = is_array($a) ? (float)($a['average_price'] ?? 0) : 0;
            $bPrice = is_array($b) ? (float)($b['average_price'] ?? 0) : 0;
            return $aPrice <=> $bPrice;
        });
    }
    $chartData = array_map(function($avg) {
        return [
            'state' => is_array($avg) ? ($avg['state'] ?? 'N/A') : 'N/A',
            'average_price' => (float)(is_array($avg) ? ($avg['average_price'] ?? 0) : 0),
        ];
    }, $chartStateAverages);
    echo json_encode($chartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>;

    if (!stateAvgData || !stateAvgData.length) {
        return;
    }

    const categories = stateAvgData.map(item => item.state);
    const prices = stateAvgData.map(item => item.average_price);

    const options = {
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
        },
        series: [{
            name: 'Average Price (RM)',
            data: prices
        }],
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    fontSize: '12px',
                    colors: '#4b5563'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Price (RM)',
                style: {
                    fontSize: '12px',
                    color: '#4b5563'
                }
            },
            labels: {
                formatter: function (val) {
                    return 'RM' + val.toFixed(2);
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return 'RM' + val.toFixed(2);
            },
            style: {
                fontSize: '11px',
                colors: ['#111827']
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return 'RM' + val.toFixed(2);
                }
            }
        },
        colors: ['#22c55e'],
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        },
        legend: {
            show: false
        }
    };

    const chart = new ApexCharts(chartEl, options);
    chart.render();
});
</script>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
