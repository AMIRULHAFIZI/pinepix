<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();
$auth->requireAdmin();

$db = Database::getInstance()->getConnection();
$currentPage = 'settings';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $settings = [
            'google_maps_api_key' => Helper::sanitize($_POST['google_maps_api_key'] ?? ''),
            'leaflet_default_lat' => $_POST['leaflet_default_lat'] ?? '3.1390',
            'leaflet_default_lng' => $_POST['leaflet_default_lng'] ?? '101.6869',
            'gemini_api_key' => Helper::sanitize($_POST['gemini_api_key'] ?? ''),
            'site_name' => Helper::sanitize($_POST['site_name'] ?? 'PinePix'),
        ];
        
        foreach ($settings as $key => $value) {
            Helper::setSetting($key, $value);
        }
        
        $_SESSION['success_message'] = 'Settings updated successfully!';
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'An error occurred while updating settings.';
    }
    Helper::redirect(BASE_URL . 'admin/settings.php');
}

// Get current settings
$settings = [
    'google_maps_api_key' => Helper::getSetting('google_maps_api_key'),
    'leaflet_default_lat' => Helper::getSetting('leaflet_default_lat', '3.1390'),
    'leaflet_default_lng' => Helper::getSetting('leaflet_default_lng', '101.6869'),
    'gemini_api_key' => Helper::getSetting('gemini_api_key'),
    'site_name' => Helper::getSetting('site_name', 'PinePix'),
];

$pageTitle = 'System Settings';
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
                        <i class="fas fa-cog mr-2 sm:mr-3 text-primary-600"></i>
                        <span>System Settings</span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">Configure system-wide settings and API keys</p>
                </div>
                
                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-sliders-h mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Settings</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 lg:p-8">
                        <form method="POST" action="" class="space-y-4 sm:space-y-5 lg:space-y-6">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name']) ?>"
                                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div>
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Google Maps API</h5>
                                <div>
                                    <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Google Maps API Key</label>
                                    <input type="text" id="google_maps_api_key" name="google_maps_api_key" 
                                           value="<?= htmlspecialchars($settings['google_maps_api_key']) ?>" 
                                           placeholder="AIza..."
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                    <p class="mt-1 text-xs text-gray-500">Required for address autocomplete in farm/shop forms</p>
                                </div>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div>
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Leaflet Map Default Location</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                    <div>
                                        <label for="leaflet_default_lat" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Default Latitude</label>
                                        <input type="number" step="any" id="leaflet_default_lat" name="leaflet_default_lat" 
                                               value="<?= htmlspecialchars($settings['leaflet_default_lat']) ?>"
                                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                    </div>
                                    
                                    <div>
                                        <label for="leaflet_default_lng" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Default Longitude</label>
                                        <input type="number" step="any" id="leaflet_default_lng" name="leaflet_default_lng" 
                                               value="<?= htmlspecialchars($settings['leaflet_default_lng']) ?>"
                                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div>
                                <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">AI Chatbot (Gemini API)</h5>
                                <div>
                                    <label for="gemini_api_key" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Gemini API Key</label>
                                    <input type="text" id="gemini_api_key" name="gemini_api_key" 
                                           value="<?= htmlspecialchars($settings['gemini_api_key']) ?>" 
                                           placeholder="Enter your Gemini API key"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                    <p class="mt-1 text-xs text-gray-500">Required for AI chatbot functionality</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-4 sm:pt-6 border-t-2 border-gray-200">
                                <button type="submit" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 w-full sm:w-auto text-sm sm:text-base">
                                    <i class="fas fa-save mr-2"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
                text: 'Do you want to save these settings?',
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
                        title: 'Saving Settings...',
                        text: 'Please wait while we update your settings',
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
/* Mobile-specific improvements for settings page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    input[type="number"],
    textarea,
    select {
        font-size: 16px !important;
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
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
