<?php
// Dashboard view - header already included in public/dashboard.php
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <!-- Header Section -->
                <div class="mb-6 sm:mb-8">
                    <div class="flex flex-col gap-4 sm:gap-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                                    <i class="fas fa-tachometer-alt mr-2 sm:mr-3 text-primary-600"></i>
                                    <span>Dashboard</span>
                                </h1>
                                <p class="text-sm sm:text-base text-gray-600">Welcome back, <span class="font-semibold text-primary-600"><?= htmlspecialchars($_SESSION['user_name']) ?></span>!</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
                            <a href="<?= BASE_URL ?>farm.php" class="inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base whitespace-nowrap">
                                <i class="fas fa-plus mr-2"></i>
                                <span>Add Farm</span>
                            </a>
                            <a href="<?= BASE_URL ?>shop.php" class="inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-white text-primary-600 border-2 border-primary-600 rounded-xl hover:bg-primary-50 transition shadow-md text-sm sm:text-base whitespace-nowrap">
                                <i class="fas fa-store mr-2"></i>
                                <span>Add Shop</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-6 sm:mb-8">
                    <!-- Farms Card -->
                    <div class="group bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-[1.02] sm:hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-green-100 text-xs sm:text-sm font-medium mb-1">My Farms</p>
                                    <h3 class="text-3xl sm:text-4xl font-bold"><?= $stats['farms'] ?></h3>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform flex-shrink-0 ml-2">
                                    <i class="fas fa-seedling text-xl sm:text-2xl"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>farm.php" class="text-green-100 hover:text-white text-xs sm:text-sm font-medium inline-flex items-center">
                                View all <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Shops Card -->
                    <div class="group bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-[1.02] sm:hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-blue-100 text-xs sm:text-sm font-medium mb-1">My Shops</p>
                                    <h3 class="text-3xl sm:text-4xl font-bold"><?= $stats['shops'] ?></h3>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform flex-shrink-0 ml-2">
                                    <i class="fas fa-store text-xl sm:text-2xl"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>shop.php" class="text-blue-100 hover:text-white text-xs sm:text-sm font-medium inline-flex items-center">
                                View all <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Announcements Card -->
                    <div class="group bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-[1.02] sm:hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-amber-100 text-xs sm:text-sm font-medium mb-1">Announcements</p>
                                    <h3 class="text-3xl sm:text-4xl font-bold"><?= $stats['announcements'] ?></h3>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform flex-shrink-0 ml-2">
                                    <i class="fas fa-bullhorn text-xl sm:text-2xl"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>announcements.php" class="text-amber-100 hover:text-white text-xs sm:text-sm font-medium inline-flex items-center">
                                View all <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Profile Card -->
                    <div class="group bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-[1.02] sm:hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0 pr-2">
                                    <p class="text-purple-100 text-xs sm:text-sm font-medium mb-1">Profile</p>
                                    <p class="text-xs sm:text-sm truncate"><?= htmlspecialchars($user['email'] ?? 'Not Set') ?></p>
                                </div>
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform flex-shrink-0">
                                    <i class="fas fa-user-circle text-xl sm:text-2xl"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>profile.php" class="text-purple-100 hover:text-white text-xs sm:text-sm font-medium inline-flex items-center">
                                Edit profile <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 lg:gap-6 mb-6 sm:mb-8">
                    <!-- Price Trend Chart -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-chart-line mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                                <span>Price Trend</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Price announcements over the last 12 months</p>
                        </div>
                        <div class="p-3 sm:p-4 lg:p-6">
                            <div id="priceTrendChart" style="min-height: 250px;" class="sm:min-h-[300px]"></div>
                        </div>
                    </div>
                    
                    <!-- Announcement Type Distribution -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-chart-pie mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                                <span>Announcement Types</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Distribution of announcement types</p>
                        </div>
                        <div class="p-3 sm:p-4 lg:p-6">
                            <div id="typeDistributionChart" style="min-height: 250px;" class="sm:min-h-[300px]"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Trend Chart -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-6 sm:mb-8">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-chart-area mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Activity Trend</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1">Announcements created over the last 6 months</p>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-6">
                        <div id="activityTrendChart" style="min-height: 250px;" class="sm:min-h-[300px]"></div>
                    </div>
                </div>
                
                <!-- Recent Announcements -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b border-gray-200">
                        <div class="flex flex-col xs:flex-row items-start xs:items-center justify-between gap-3">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-bullhorn mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                                <span>Recent Announcements</span>
                            </h3>
                            <a href="<?= BASE_URL ?>announcements.php" class="text-xs sm:text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center whitespace-nowrap">
                                View all <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-4 sm:p-4 lg:p-6">
                        <?php if (empty($recentAnnouncements)): ?>
                            <div class="text-center py-8 sm:py-12">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-2xl sm:text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-base sm:text-lg font-medium">No announcements yet</p>
                                <p class="text-gray-400 text-xs sm:text-sm mt-2">Create your first announcement to get started</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="data-table min-w-full divide-y divide-gray-200">
                                            <thead class="hidden sm:table-header-group">
                                                <tr>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Created By</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($recentAnnouncements as $announcement): ?>
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="text-xs sm:text-sm font-semibold text-gray-900"><?= htmlspecialchars($announcement['title']) ?></div>
                                                            <!-- Mobile: Show created by below title -->
                                                            <div class="mt-1 sm:hidden">
                                                                <div class="flex items-center">
                                                                    <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center mr-2">
                                                                        <span class="text-primary-600 text-xs font-semibold"><?= strtoupper(substr($announcement['created_by_name'] ?? 'N', 0, 1)) ?></span>
                                                                    </div>
                                                                    <span class="text-xs text-gray-600"><?= htmlspecialchars($announcement['created_by_name'] ?? 'N/A') ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold <?= $announcement['type'] === 'price' ? 'bg-green-100 text-green-800' : ($announcement['type'] === 'promotion' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') ?>">
                                                                <i class="fas fa-<?= $announcement['type'] === 'price' ? 'dollar-sign' : ($announcement['type'] === 'promotion' ? 'tag' : 'info-circle') ?> mr-1"></i>
                                                                <span class="hidden sm:inline"><?= ucfirst($announcement['type']) ?></span>
                                                            </span>
                                                        </td>
                                                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-2">
                                                                    <span class="text-primary-600 text-xs font-semibold"><?= strtoupper(substr($announcement['created_by_name'] ?? 'N', 0, 1)) ?></span>
                                                                </div>
                                                                <?= htmlspecialchars($announcement['created_by_name'] ?? 'N/A') ?>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-calendar-alt mr-1.5 text-gray-400 hidden sm:inline"></i>
                                                                <span><?= Helper::formatDate($announcement['created_at']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <a href="<?= BASE_URL ?>announcements.php?view=<?= $announcement['id'] ?>" class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-xs font-medium whitespace-nowrap">
                                                                <i class="fas fa-eye mr-1"></i>
                                                                <span class="hidden sm:inline">View</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price Trend Chart
    const priceTrendData = <?= json_encode($priceTrend ?: []) ?>;
    const priceTrendMonths = priceTrendData.length > 0 ? priceTrendData.map(item => item.month) : [];
    const priceTrendCounts = priceTrendData.length > 0 ? priceTrendData.map(item => parseInt(item.count)) : [0];
    
    // Responsive chart height
    const getChartHeight = () => {
        if (window.innerWidth < 640) return 250;
        return 300;
    };
    
    const priceTrendOptions = {
        series: [{
            name: 'Price Announcements',
            data: priceTrendCounts
        }],
        chart: {
            type: 'line',
            height: getChartHeight(),
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'inherit'
        },
        colors: ['#d97706'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5,
            colors: ['#d97706'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: { size: 7 }
        },
        xaxis: {
            categories: priceTrendMonths,
            labels: {
                style: { colors: '#6b7280', fontSize: window.innerWidth < 640 ? '10px' : '12px' },
                rotate: window.innerWidth < 640 ? -45 : 0,
                rotateAlways: window.innerWidth < 640
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#6b7280', fontSize: window.innerWidth < 640 ? '10px' : '12px' }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' announcement(s)'; } }
        },
        noData: {
            text: 'No price announcements available',
            align: 'center',
            verticalAlign: 'middle',
            style: { color: '#6b7280', fontSize: '14px' }
        }
    };
    
    let priceTrendChart;
    if (document.querySelector("#priceTrendChart")) {
        priceTrendChart = new ApexCharts(document.querySelector("#priceTrendChart"), priceTrendOptions);
        priceTrendChart.render();
    }
    
    // Announcement Type Distribution Chart
    const typeData = <?= json_encode($announcementsByType ?: []) ?>;
    const hasTypeData = Object.keys(typeData).length > 0;
    const typeLabels = hasTypeData ? Object.keys(typeData).map(type => type.charAt(0).toUpperCase() + type.slice(1)) : [];
    const typeValues = hasTypeData ? Object.values(typeData) : [];
    
    const typeColors = {
        'price': '#16a34a',
        'promotion': '#f59e0b',
        'roadshow': '#3b82f6',
        'news': '#8b5cf6',
        'other': '#6b7280'
    };
    
    const typeDistributionOptions = {
        series: hasTypeData ? typeValues : [],
        chart: {
            type: 'donut',
            height: getChartHeight(),
            fontFamily: 'inherit'
        },
        labels: typeLabels,
        colors: hasTypeData ? typeLabels.map(label => {
            const key = label.toLowerCase();
            return typeColors[key] || typeColors['other'];
        }) : [],
        legend: {
            position: 'bottom',
            fontSize: window.innerWidth < 640 ? '11px' : '14px',
            labels: { colors: '#374151' },
            itemMargin: {
                horizontal: window.innerWidth < 640 ? 5 : 10,
                vertical: window.innerWidth < 640 ? 5 : 10
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: { fontSize: '14px', fontWeight: 600 },
                        value: { fontSize: '18px', fontWeight: 700, color: '#111827' },
                        total: {
                            show: hasTypeData,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#6b7280',
                            formatter: function() { return hasTypeData ? typeValues.reduce((a, b) => a + b, 0) : 0; }
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' announcement(s)'; } }
        },
        noData: {
            text: 'No announcements available',
            align: 'center',
            verticalAlign: 'middle',
            style: { color: '#6b7280', fontSize: '14px' }
        }
    };
    
    let typeDistributionChart;
    if (document.querySelector("#typeDistributionChart")) {
        typeDistributionChart = new ApexCharts(document.querySelector("#typeDistributionChart"), typeDistributionOptions);
        typeDistributionChart.render();
    }
    
    // Activity Trend Chart
    const activityData = <?= json_encode($activityTrend ?: []) ?>;
    const activityMonths = activityData.length > 0 ? activityData.map(item => item.month) : [];
    const activityCounts = activityData.length > 0 ? activityData.map(item => parseInt(item.count)) : [0];
    
    const activityTrendOptions = {
        series: [{
            name: 'Announcements',
            data: activityCounts
        }],
        chart: {
            type: 'area',
            height: getChartHeight(),
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'inherit'
        },
        colors: ['#d97706'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5,
            colors: ['#d97706'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: { size: 7 }
        },
        xaxis: {
            categories: activityMonths,
            labels: {
                style: { colors: '#6b7280', fontSize: window.innerWidth < 640 ? '10px' : '12px' },
                rotate: window.innerWidth < 640 ? -45 : 0,
                rotateAlways: window.innerWidth < 640
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#6b7280', fontSize: window.innerWidth < 640 ? '10px' : '12px' }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function(val) { return val + ' announcement(s)'; } }
        },
        noData: {
            text: 'No activity data available',
            align: 'center',
            verticalAlign: 'middle',
            style: { color: '#6b7280', fontSize: '14px' }
        }
    };
    
    let activityTrendChart;
    if (document.querySelector("#activityTrendChart")) {
        activityTrendChart = new ApexCharts(document.querySelector("#activityTrendChart"), activityTrendOptions);
        activityTrendChart.render();
    }
    
    // Make charts responsive on window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.priceTrendChart) {
                window.priceTrendChart.updateOptions({ 
                    chart: { height: getChartHeight() },
                    xaxis: {
                        labels: {
                            fontSize: window.innerWidth < 640 ? '10px' : '12px',
                            rotate: window.innerWidth < 640 ? -45 : 0,
                            rotateAlways: window.innerWidth < 640
                        }
                    },
                    yaxis: {
                        labels: {
                            fontSize: window.innerWidth < 640 ? '10px' : '12px'
                        }
                    }
                });
            }
            if (window.typeDistributionChart) {
                window.typeDistributionChart.updateOptions({ 
                    chart: { height: getChartHeight() },
                    legend: {
                        fontSize: window.innerWidth < 640 ? '11px' : '14px',
                        itemMargin: {
                            horizontal: window.innerWidth < 640 ? 5 : 10,
                            vertical: window.innerWidth < 640 ? 5 : 10
                        }
                    }
                });
            }
            if (window.activityTrendChart) {
                window.activityTrendChart.updateOptions({ 
                    chart: { height: getChartHeight() },
                    xaxis: {
                        labels: {
                            fontSize: window.innerWidth < 640 ? '10px' : '12px',
                            rotate: window.innerWidth < 640 ? -45 : 0,
                            rotateAlways: window.innerWidth < 640
                        }
                    },
                    yaxis: {
                        labels: {
                            fontSize: window.innerWidth < 640 ? '10px' : '12px'
                        }
                    }
                });
            }
        }, 250);
    });
    
    // Store chart instances globally for resize handler
    if (document.querySelector("#priceTrendChart")) {
        window.priceTrendChart = priceTrendChart;
    }
    if (document.querySelector("#typeDistributionChart")) {
        window.typeDistributionChart = typeDistributionChart;
    }
    if (document.querySelector("#activityTrendChart")) {
        window.activityTrendChart = activityTrendChart;
    }
});
</script>

<style>
/* Ensure dashboard content is accessible on mobile */
@media (max-width: 1023px) {
    /* Full width on mobile when sidebar is hidden */
    .flex.min-h-\[calc\(100vh-4rem\)\] > .flex-1 {
        width: 100%;
        margin-left: 0;
    }
}

/* Improve touch targets on mobile */
@media (max-width: 768px) {
    /* Better spacing for cards on mobile */
    .bg-white.rounded-xl {
        margin-bottom: 1rem;
    }
    
    /* Improve table scrolling on mobile */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
}

/* Tablet specific adjustments */
@media (min-width: 768px) and (max-width: 1023px) {
    /* Optimize grid spacing for tablets */
    .grid.gap-4 {
        gap: 1rem;
    }
}
</style>

