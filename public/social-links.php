<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance()->getConnection();
$currentPage = 'social-links';

$message = '';
$messageType = '';

// Get existing social links
$stmt = $db->prepare("SELECT * FROM social_links WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$socialLinks = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'facebook' => Helper::sanitize($_POST['facebook'] ?? ''),
        'instagram' => Helper::sanitize($_POST['instagram'] ?? ''),
        'tiktok' => Helper::sanitize($_POST['tiktok'] ?? ''),
        'website' => Helper::sanitize($_POST['website'] ?? ''),
        'shopee' => Helper::sanitize($_POST['shopee'] ?? ''),
        'lazada' => Helper::sanitize($_POST['lazada'] ?? ''),
    ];
    
    try {
        if ($socialLinks) {
            $stmt = $db->prepare("UPDATE social_links SET facebook = ?, instagram = ?, tiktok = ?, website = ?, shopee = ?, lazada = ? WHERE user_id = ?");
            $result = $stmt->execute([$data['facebook'], $data['instagram'], $data['tiktok'], $data['website'], $data['shopee'], $data['lazada'], $_SESSION['user_id']]);
        } else {
            $stmt = $db->prepare("INSERT INTO social_links (user_id, facebook, instagram, tiktok, website, shopee, lazada) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$_SESSION['user_id'], $data['facebook'], $data['instagram'], $data['tiktok'], $data['website'], $data['shopee'], $data['lazada']]);
        }
        
        if ($result) {
            $_SESSION['success_message'] = 'Social links updated successfully!';
            $stmt = $db->prepare("SELECT * FROM social_links WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $socialLinks = $stmt->fetch();
        } else {
            $_SESSION['error_message'] = 'Failed to update social links.';
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'An error occurred while updating social links.';
    }
    Helper::redirect(BASE_URL . 'social-links.php');
}

$pageTitle = 'Social Links';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-4xl mx-auto w-full">
                <!-- Header Section -->
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                        <i class="fas fa-share-alt mr-2 sm:mr-3 text-primary-600"></i>
                        <span>Social Media Links</span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">Connect your social media profiles and online stores</p>
                </div>
                
                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-link mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Social Links</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 lg:p-8">
                        <form method="POST" action="" class="space-y-4 sm:space-y-5 lg:space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                                    </label>
                                    <input type="url" id="facebook" name="facebook" 
                                           value="<?= htmlspecialchars($socialLinks['facebook'] ?? '') ?>" 
                                           placeholder="https://facebook.com/yourpage"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                                    </label>
                                    <input type="url" id="instagram" name="instagram" 
                                           value="<?= htmlspecialchars($socialLinks['instagram'] ?? '') ?>" 
                                           placeholder="https://instagram.com/yourprofile"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fab fa-tiktok mr-2"></i>TikTok
                                    </label>
                                    <input type="url" id="tiktok" name="tiktok" 
                                           value="<?= htmlspecialchars($socialLinks['tiktok'] ?? '') ?>" 
                                           placeholder="https://tiktok.com/@yourprofile"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fas fa-globe text-blue-500 mr-2"></i>Website
                                    </label>
                                    <input type="url" id="website" name="website" 
                                           value="<?= htmlspecialchars($socialLinks['website'] ?? '') ?>" 
                                           placeholder="https://yourwebsite.com"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label for="shopee" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fas fa-shopping-cart text-orange-500 mr-2"></i>Shopee
                                    </label>
                                    <input type="url" id="shopee" name="shopee" 
                                           value="<?= htmlspecialchars($socialLinks['shopee'] ?? '') ?>" 
                                           placeholder="https://shopee.com.my/yourstore"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="lazada" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        <i class="fas fa-store text-orange-600 mr-2"></i>Lazada
                                    </label>
                                    <input type="url" id="lazada" name="lazada" 
                                           value="<?= htmlspecialchars($socialLinks['lazada'] ?? '') ?>" 
                                           placeholder="https://lazada.com.my/shop/yourstore"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-4 sm:pt-6 border-t-2 border-gray-200">
                                <button type="submit" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 w-full sm:w-auto text-sm sm:text-base">
                                    <i class="fas fa-save mr-2"></i>Save Social Links
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if ($socialLinks && array_filter(array_slice($socialLinks, 2), function($v) { return !empty($v); })): ?>
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border-2 border-gray-200 mt-6 sm:mt-8 overflow-hidden">
                        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                            <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-eye mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                                <span>Preview</span>
                            </h5>
                        </div>
                        <div class="p-4 sm:p-6 lg:p-8 text-center">
                            <p class="mb-4 sm:mb-6 text-sm sm:text-base text-gray-600 font-medium">Your social media links:</p>
                            <div class="flex justify-center flex-wrap gap-3 sm:gap-4">
                                <?php if (!empty($socialLinks['facebook'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['facebook']) ?>" target="_blank" class="btn-view inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fab fa-facebook mr-2"></i>Facebook
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($socialLinks['instagram'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['instagram']) ?>" target="_blank" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-pink-600 text-white rounded-lg sm:rounded-xl font-semibold shadow-md hover:bg-pink-700 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fab fa-instagram mr-2"></i>Instagram
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($socialLinks['tiktok'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['tiktok']) ?>" target="_blank" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-900 text-white rounded-lg sm:rounded-xl font-semibold shadow-md hover:bg-gray-800 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fab fa-tiktok mr-2"></i>TikTok
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($socialLinks['website'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['website']) ?>" target="_blank" class="btn-view inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fas fa-globe mr-2"></i>Website
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($socialLinks['shopee'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['shopee']) ?>" target="_blank" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-orange-500 text-white rounded-lg sm:rounded-xl font-semibold shadow-md hover:bg-orange-600 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fas fa-shopping-cart mr-2"></i>Shopee
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($socialLinks['lazada'])): ?>
                                    <a href="<?= htmlspecialchars($socialLinks['lazada']) ?>" target="_blank" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-orange-600 text-white rounded-lg sm:rounded-xl font-semibold shadow-md hover:bg-orange-700 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 text-sm sm:text-base whitespace-nowrap">
                                        <i class="fas fa-store mr-2"></i>Lazada
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= addslashes($_SESSION['success_message']) ?>',
        confirmButtonColor: '#d97706',
        timer: 3000,
        timerProgressBar: true
    });
});
</script>
<?php unset($_SESSION['success_message']); endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '<?= addslashes($_SESSION['error_message']) ?>',
        confirmButtonColor: '#dc2626'
    });
});
</script>
<?php unset($_SESSION['error_message']); endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        // Mark form to skip global handler
        form.setAttribute('data-has-confirmation', 'true');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save your social media links?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    }
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we update your social links',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                } else {
                    // Reset button state if cancelled
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }
            });
            
            return false;
        });
    }
});
</script>

<style>
/* Mobile-specific improvements for social links page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="url"],
    input[type="text"],
    textarea,
    select {
        font-size: 16px !important;
    }
    
    /* Better button spacing on mobile */
    .btn-view,
    .btn-primary {
        width: 100%;
        justify-content: center;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    /* Better spacing for form fields */
    .grid.grid-cols-1.md\:grid-cols-2 > div {
        padding: 0.5rem;
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

/* Social media buttons responsive adjustments */
@media (max-width: 640px) {
    .flex.justify-center.flex-wrap.gap-3 > a {
        flex: 1 1 calc(50% - 0.375rem);
        min-width: 0;
    }
}

@media (min-width: 641px) and (max-width: 768px) {
    .flex.justify-center.flex-wrap.gap-4 > a {
        flex: 1 1 calc(33.333% - 0.5rem);
        min-width: 0;
    }
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
