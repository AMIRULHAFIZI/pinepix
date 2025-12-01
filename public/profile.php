<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance()->getConnection();
$viewUserId = $_GET['user_id'] ?? $_SESSION['user_id'];
$isOwnProfile = $viewUserId == $_SESSION['user_id'];

// Get user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$viewUserId]);
$user = $stmt->fetch();

if (!$user) {
    Helper::redirect(BASE_URL . 'dashboard.php');
}

// Get farms
$stmt = $db->prepare("SELECT * FROM farms WHERE user_id = ?");
$stmt->execute([$viewUserId]);
$farms = $stmt->fetchAll();

// Get shops
$stmt = $db->prepare("SELECT * FROM shops WHERE user_id = ?");
$stmt->execute([$viewUserId]);
$shops = $stmt->fetchAll();

// Get social links
$stmt = $db->prepare("SELECT * FROM social_links WHERE user_id = ?");
$stmt->execute([$viewUserId]);
$socialLinks = $stmt->fetch();

$pageTitle = 'Profile';
$currentPage = 'profile';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gray-50">
    <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <div class="flex flex-col gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-user mr-2 sm:mr-3 text-primary-600"></i>
                            <span><?= $isOwnProfile ? 'My' : '' ?> Profile</span>
                        </h2>
                    </div>
                    <?php if ($isOwnProfile): ?>
                        <a href="<?= BASE_URL ?>biodata.php" class="inline-flex items-center justify-center px-4 sm:px-5 py-2.5 sm:py-3 bg-gradient-to-r from-primary-600 to-purple-600 text-white rounded-lg font-semibold hover:from-primary-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl text-sm sm:text-base w-full sm:w-auto">
                            <i class="fas fa-edit mr-2"></i>Edit Profile
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl sm:rounded-lg shadow-sm border border-gray-200 text-center p-4 sm:p-6">
                            <?php if ($user['profile_image']): ?>
                                <img src="<?= BASE_URL . $user['profile_image'] ?>" class="w-24 h-24 sm:w-32 sm:h-32 lg:w-32 lg:h-32 rounded-full mx-auto mb-3 sm:mb-4 object-cover border-4 border-primary-100" alt="Profile">
                            <?php else: ?>
                                <div class="w-24 h-24 sm:w-32 sm:h-32 lg:w-32 lg:h-32 rounded-full mx-auto mb-3 sm:mb-4 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user-circle text-5xl sm:text-6xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <h4 class="text-lg sm:text-xl font-semibold text-gray-900 mb-1 sm:mb-2 break-words"><?= htmlspecialchars($user['name']) ?></h4>
                            <p class="text-sm sm:text-base text-gray-600 mb-2 sm:mb-3 break-all"><?= htmlspecialchars($user['email']) ?></p>
                            <?php if ($user['business_category']): ?>
                                <span class="inline-block px-2 sm:px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-xs sm:text-sm font-medium mb-3 sm:mb-4"><?= htmlspecialchars($user['business_category']) ?></span>
                            <?php endif; ?>
                            
                            <?php if ($socialLinks): ?>
                                <hr class="my-3 sm:my-4 border-gray-200">
                                <div class="flex justify-center flex-wrap gap-2">
                                    <?php if (!empty($socialLinks['facebook'])): ?>
                                        <a href="<?= htmlspecialchars($socialLinks['facebook']) ?>" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" title="Facebook">
                                            <i class="fab fa-facebook text-sm sm:text-base"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($socialLinks['instagram'])): ?>
                                        <a href="<?= htmlspecialchars($socialLinks['instagram']) ?>" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition" title="Instagram">
                                            <i class="fab fa-instagram text-sm sm:text-base"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($socialLinks['website'])): ?>
                                        <a href="<?= htmlspecialchars($socialLinks['website']) ?>" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition" title="Website">
                                            <i class="fas fa-globe text-sm sm:text-base"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-3 space-y-4 sm:space-y-6">
                        <div class="bg-white rounded-xl sm:rounded-lg shadow-sm border border-gray-200">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900">Personal Information</h5>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <strong class="block text-xs sm:text-sm font-medium text-gray-500 mb-1">Phone:</strong>
                                        <p class="text-sm sm:text-base text-gray-900 break-all"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
                                    </div>
                                    <div>
                                        <strong class="block text-xs sm:text-sm font-medium text-gray-500 mb-1">Gender:</strong>
                                        <p class="text-sm sm:text-base text-gray-900"><?= ucfirst($user['gender'] ?? 'Not specified') ?></p>
                                    </div>
                                    <div>
                                        <strong class="block text-xs sm:text-sm font-medium text-gray-500 mb-1">IC/Passport:</strong>
                                        <p class="text-sm sm:text-base text-gray-900 break-all"><?= htmlspecialchars($user['ic_passport'] ?? 'Not provided') ?></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <strong class="block text-xs sm:text-sm font-medium text-gray-500 mb-1">Address:</strong>
                                        <p class="text-sm sm:text-base text-gray-900 whitespace-pre-line break-words"><?= htmlspecialchars($user['address'] ?? 'Not provided') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl sm:rounded-lg shadow-sm border border-gray-200">
                            <button type="button"
                                    class="w-full px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between text-left focus:outline-none"
                                    onclick="toggleProfileSection('farmsSection')">
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900">
                                    Farms <span class="text-xs sm:text-sm font-normal text-gray-500">(<?= count($farms) ?>)</span>
                                </h5>
                                <i id="farmsSectionChevron" class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                            </button>
                            <div id="farmsSection" class="p-4 sm:p-6">
                                <?php if (empty($farms)): ?>
                                    <p class="text-sm sm:text-base text-gray-500">No farms registered.</p>
                                <?php else: ?>
                                    <div class="space-y-3">
                                        <?php foreach ($farms as $farm): ?>
                                            <div class="p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                <?php 
                                                $farmImages = [];
                                                if (!empty($farm['images'])) {
                                                    $farmImages = json_decode($farm['images'], true) ?: [];
                                                }
                                                ?>
                                                <?php if (!empty($farmImages)): ?>
                                                    <div class="swiper farmSwiper-<?= $farm['id'] ?> w-full h-48 mb-3 rounded-lg overflow-hidden">
                                                        <div class="swiper-wrapper">
                                                            <?php foreach ($farmImages as $img): ?>
                                                                <div class="swiper-slide flex items-center justify-center">
                                                                    <img src="<?= BASE_URL . $img ?>" alt="<?= htmlspecialchars($farm['farm_name']) ?>" class="w-full h-full object-cover">
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <?php if (count($farmImages) > 1): ?>
                                                            <div class="swiper-button-next"></div>
                                                            <div class="swiper-button-prev"></div>
                                                            <div class="swiper-pagination"></div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <h6 class="text-sm sm:text-base font-semibold text-gray-900 mb-1 break-words"><?= htmlspecialchars($farm['farm_name']) ?></h6>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1"><?= htmlspecialchars($farm['farm_size']) ?></p>
                                                <p class="text-xs sm:text-sm text-gray-500 break-words"><?= htmlspecialchars($farm['address']) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl sm:rounded-lg shadow-sm border border-gray-200">
                            <button type="button"
                                    class="w-full px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between text-left focus:outline-none"
                                    onclick="toggleProfileSection('shopsSection')">
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900">
                                    Shops <span class="text-xs sm:text-sm font-normal text-gray-500">(<?= count($shops) ?>)</span>
                                </h5>
                                <i id="shopsSectionChevron" class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                            </button>
                            <div id="shopsSection" class="p-4 sm:p-6">
                                <?php if (empty($shops)): ?>
                                    <p class="text-sm sm:text-base text-gray-500">No shops registered.</p>
                                <?php else: ?>
                                    <div class="space-y-3">
                                        <?php foreach ($shops as $shop): ?>
                                            <div class="p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                <?php 
                                                $shopImages = [];
                                                if (!empty($shop['images'])) {
                                                    $shopImages = json_decode($shop['images'], true) ?: [];
                                                }
                                                ?>
                                                <?php if (!empty($shopImages)): ?>
                                                    <div class="swiper shopSwiper-<?= $shop['id'] ?> w-full h-48 mb-3 rounded-lg overflow-hidden">
                                                        <div class="swiper-wrapper">
                                                            <?php foreach ($shopImages as $img): ?>
                                                                <div class="swiper-slide flex items-center justify-center">
                                                                    <img src="<?= BASE_URL . $img ?>" alt="<?= htmlspecialchars($shop['shop_name']) ?>" class="w-full h-full object-cover">
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <?php if (count($shopImages) > 1): ?>
                                                            <div class="swiper-button-next"></div>
                                                            <div class="swiper-button-prev"></div>
                                                            <div class="swiper-pagination"></div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <h6 class="text-sm sm:text-base font-semibold text-gray-900 mb-1 break-words"><?= htmlspecialchars($shop['shop_name']) ?></h6>
                                                <p class="text-xs sm:text-sm text-gray-600 mb-1 break-words"><?= htmlspecialchars($shop['address']) ?></p>
                                                <p class="text-xs sm:text-sm text-gray-500"><?= htmlspecialchars($shop['operation_hours']) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
></div>

<script>
// Initialize Swiper for farm and shop carousels and setup accordions
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($farms as $farm): ?>
        <?php 
        $farmImages = [];
        if (!empty($farm['images'])) {
            $farmImages = json_decode($farm['images'], true) ?: [];
        }
        ?>
        <?php if (!empty($farmImages) && count($farmImages) > 1): ?>
            new Swiper('.farmSwiper-<?= $farm['id'] ?>', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.farmSwiper-<?= $farm['id'] ?> .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.farmSwiper-<?= $farm['id'] ?> .swiper-button-next',
                    prevEl: '.farmSwiper-<?= $farm['id'] ?> .swiper-button-prev',
                },
            });
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php foreach ($shops as $shop): ?>
        <?php 
        $shopImages = [];
        if (!empty($shop['images'])) {
            $shopImages = json_decode($shop['images'], true) ?: [];
        }
        ?>
        <?php if (!empty($shopImages) && count($shopImages) > 1): ?>
            new Swiper('.shopSwiper-<?= $shop['id'] ?>', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.shopSwiper-<?= $shop['id'] ?> .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.shopSwiper-<?= $shop['id'] ?> .swiper-button-next',
                    prevEl: '.shopSwiper-<?= $shop['id'] ?> .swiper-button-prev',
                },
            });
        <?php endif; ?>
    <?php endforeach; ?>
});

function toggleProfileSection(id) {
    const section = document.getElementById(id);
    const chevron = document.getElementById(id + 'Chevron');
    if (!section || !chevron) return;
    
    const isHidden = section.classList.contains('hidden');
    if (isHidden) {
        section.classList.remove('hidden');
        chevron.classList.remove('rotate-180');
    } else {
        section.classList.add('hidden');
        chevron.classList.add('rotate-180');
    }
}
</script>

<style>
/* Mobile-specific improvements for profile page */
@media (max-width: 640px) {
    /* Better spacing for profile sections */
    .space-y-3 > * + * {
        margin-top: 0.75rem;
    }
    
    /* Ensure text doesn't overflow */
    .break-words {
        word-break: break-word;
        overflow-wrap: break-word;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    /* Better spacing for profile card */
    .lg\:col-span-1 > div {
        padding: 1.25rem;
    }
}

/* Ensure dashboard content is accessible on mobile */
@media (max-width: 1023px) {
    /* Full width on mobile when sidebar is hidden */
    .flex.min-h-\[calc\(100vh-4rem\)\] > .flex-1 {
        width: 100%;
        margin-left: 0;
    }
}

/* Profile image responsive sizing */
@media (max-width: 640px) {
    .w-24.h-24 {
        width: 6rem;
        height: 6rem;
    }
}

/* Social links button spacing */
@media (max-width: 640px) {
    .flex.justify-center.flex-wrap.gap-2 > a {
        width: 2.25rem;
        height: 2.25rem;
    }
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
