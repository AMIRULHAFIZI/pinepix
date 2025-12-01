<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance()->getConnection();
$user = $auth->getUser();
$currentPage = 'biodata';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => Helper::sanitize($_POST['name'] ?? ''),
        'phone' => Helper::sanitize($_POST['phone'] ?? ''),
        'address' => Helper::sanitize($_POST['address'] ?? ''),
        'gender' => Helper::sanitize($_POST['gender'] ?? ''),
        'ic_passport' => Helper::sanitize($_POST['ic_passport'] ?? ''),
        'business_category' => Helper::sanitize($_POST['business_category'] ?? ''),
    ];

    $shouldUpdateProfileImage = false;
    $newProfileImagePath = null;

    // Handle removing existing profile image (without uploading a new one)
    if (!empty($_POST['remove_profile_image']) && $user && !empty($user['profile_image'])) {
        Helper::deleteFile($user['profile_image']);
        $shouldUpdateProfileImage = true;
        $newProfileImagePath = null; // Set profile_image to NULL in DB
    }
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload = Helper::uploadFile($_FILES['profile_image'], 'profiles');
        if ($upload['success']) {
            // Delete old image if exists
            if ($user && !empty($user['profile_image'])) {
                Helper::deleteFile($user['profile_image']);
            }
            $newProfileImagePath = $upload['path'];
            $shouldUpdateProfileImage = true;
        }
    }
    
    $sql = "UPDATE users SET name = ?, phone = ?, address = ?, gender = ?, ic_passport = ?, business_category = ?";
    $params = [$data['name'], $data['phone'], $data['address'], $data['gender'], $data['ic_passport'], $data['business_category']];

    if ($shouldUpdateProfileImage) {
        $sql .= ", profile_image = ?";
        $params[] = $newProfileImagePath;
    }

    $sql .= " WHERE id = ?";
    $params[] = $_SESSION['user_id'];

    $stmt = $db->prepare($sql);

    try {
        if ($stmt->execute($params)) {
            $message = 'Biodata updated successfully!';
            $messageType = 'success';
            $user = $auth->getUser(); // Refresh user data
        } else {
            $message = 'Failed to update biodata.';
            $messageType = 'error';
        }
    } catch (Exception $e) {
        $message = 'An error occurred while updating biodata.';
        $messageType = 'error';
    }
}

$pageTitle = 'My Biodata';
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
                        <i class="fas fa-id-card mr-2 sm:mr-3 text-primary-600"></i>
                        <span>My Biodata</span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">Manage your personal information and profile details</p>
                </div>
                
                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-user-edit mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Personal Information</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 lg:p-8">
                        <form method="POST" action="" enctype="multipart/form-data" class="space-y-4 sm:space-y-5 lg:space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Email</label>
                                    <input type="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed text-sm sm:text-base">
                                    <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Phone Number</label>
                                    <?php
                                    $phone = $user['phone'] ?? '';
                                    // Remove country code prefix if exists
                                    $phoneNumber = preg_replace('/^\+\d{1,4}/', '', $phone);
                                    ?>
                                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phoneNumber) ?>" placeholder="60123456789"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Gender</label>
                                    <select id="gender" name="gender" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base bg-white">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                                <div>
                                    <label for="ic_passport" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">IC/Passport</label>
                                    <input type="text" id="ic_passport" name="ic_passport" value="<?= htmlspecialchars($user['ic_passport'] ?? '') ?>" placeholder="010203040506"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                                </div>
                                
                                <div>
                                    <label for="business_category" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Business Category</label>
                                    <select id="business_category" name="business_category" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base bg-white">
                                        <option value="">Select Category</option>
                                        <option value="Pineapple Farming" <?= ($user['business_category'] ?? '') === 'Pineapple Farming' ? 'selected' : '' ?>>Pineapple Farming</option>
                                        <option value="Pineapple Processing" <?= ($user['business_category'] ?? '') === 'Pineapple Processing' ? 'selected' : '' ?>>Pineapple Processing</option>
                                        <option value="Pineapple Retail" <?= ($user['business_category'] ?? '') === 'Pineapple Retail' ? 'selected' : '' ?>>Pineapple Retail</option>
                                        <option value="Pineapple Export" <?= ($user['business_category'] ?? '') === 'Pineapple Export' ? 'selected' : '' ?>>Pineapple Export</option>
                                        <option value="Agri-Tourism" <?= ($user['business_category'] ?? '') === 'Agri-Tourism' ? 'selected' : '' ?>>Agri-Tourism</option>
                                        <option value="Other" <?= ($user['business_category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Address</label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none text-sm sm:text-base"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div>
                                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Profile Image</label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" data-preview="#profilePreview"
                                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 text-sm sm:text-base">
                            <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row sm:items-start sm:space-x-4">
                                    <div class="flex justify-center sm:justify-start">
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img src="<?= BASE_URL . $user['profile_image'] ?>" id="profilePreview" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/200" id="profilePreview" class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden">
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <label class="mt-3 sm:mt-0 inline-flex items-center text-sm text-gray-700 cursor-pointer select-none">
                                            <input type="checkbox" name="remove_profile_image" value="1" class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span>Remove current profile image</span>
                                        </label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="submit" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 w-full sm:w-auto text-sm sm:text-base">
                                    <i class="fas fa-save mr-2"></i>Update Biodata
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            
            // Show confirmation first
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update your biodata information?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    }
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while we update your biodata',
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

<?php if ($message): ?>
Swal.fire({
    icon: '<?= $messageType === 'success' ? 'success' : 'error' ?>',
    title: '<?= $messageType === 'success' ? 'Success!' : 'Error!' ?>',
    text: '<?= addslashes($message) ?>',
    confirmButtonColor: '#d97706',
    timer: 3000,
    timerProgressBar: true
});
<?php endif; ?>

// Profile image preview
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profile_image');
    const profilePreview = document.getElementById('profilePreview');
    
    if (profileImageInput && profilePreview) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                    profilePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>

<style>
/* Mobile-specific improvements for biodata page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea,
    select {
        font-size: 16px !important;
    }
    
    /* Better spacing for file input */
    input[type="file"] {
        font-size: 14px !important;
    }
    
    /* Profile image responsive sizing */
    #profilePreview {
        max-width: 100%;
        height: auto;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    /* Optimize form spacing for tablets */
    .grid.gap-3 {
        gap: 1rem;
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
