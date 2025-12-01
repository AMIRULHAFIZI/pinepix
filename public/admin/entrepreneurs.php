<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();
$auth->requireAdmin();

$db = Database::getInstance()->getConnection();
$currentPage = 'entrepreneurs';

$message = '';
$messageType = '';

$editId = $_GET['edit'] ?? null;
$editUser = null;

if ($editId) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND role = 'entrepreneur'");
    $stmt->execute([$editId]);
    $editUser = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = $_POST['user_id'] ?? null;
    
    if ($action === 'delete' && $userId) {
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'entrepreneur'");
            $stmt->execute([$userId]);
            $message = 'Entrepreneur deleted successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Failed to delete entrepreneur.';
            $messageType = 'error';
        }
    } elseif ($action === 'update' && $userId) {
        try {
            $data = [
                'name' => Helper::sanitize($_POST['name'] ?? ''),
                'phone' => Helper::sanitize($_POST['phone'] ?? ''),
                'address' => Helper::sanitize($_POST['address'] ?? ''),
                'gender' => Helper::sanitize($_POST['gender'] ?? ''),
                'ic_passport' => Helper::sanitize($_POST['ic_passport'] ?? ''),
                'business_category' => Helper::sanitize($_POST['business_category'] ?? ''),
            ];
            
            $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, address = ?, gender = ?, ic_passport = ?, business_category = ? WHERE id = ? AND role = 'entrepreneur'");
            if ($stmt->execute([$data['name'], $data['phone'], $data['address'], $data['gender'], $data['ic_passport'], $data['business_category'], $userId])) {
                $_SESSION['success_message'] = 'Entrepreneur updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update entrepreneur.';
            }
            Helper::redirect(BASE_URL . 'admin/entrepreneurs.php');
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'An error occurred while updating the entrepreneur.';
            Helper::redirect(BASE_URL . 'admin/entrepreneurs.php');
        }
    }
}

// Get all entrepreneurs with farm and shop counts
$stmt = $db->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM farms WHERE user_id = u.id) as farm_count,
           (SELECT COUNT(*) FROM shops WHERE user_id = u.id) as shop_count
    FROM users u 
    WHERE u.role = 'entrepreneur'
    ORDER BY u.created_at DESC
");
$entrepreneurs = $stmt->fetchAll();

$pageTitle = 'Manage Entrepreneurs';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <!-- Header Section -->
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                        <i class="fas fa-users mr-2 sm:mr-3 text-primary-600"></i>
                        <span>Manage Entrepreneurs</span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">View and manage all registered entrepreneurs in the system</p>
                </div>
                
                <!-- Entrepreneurs Table -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-list mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Entrepreneurs List</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <?php if (empty($entrepreneurs)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">No entrepreneurs found</p>
                                <p class="text-gray-400 text-sm mt-2">No entrepreneurs have been registered yet</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="data-table min-w-full divide-y divide-gray-200">
                                            <thead class="hidden sm:table-header-group">
                                                <tr>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Phone</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Farms</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Shops</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($entrepreneurs as $entrepreneur): ?>
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="flex items-center">
                                                                <?php if ($entrepreneur['profile_image']): ?>
                                                                    <img src="<?= BASE_URL . $entrepreneur['profile_image'] ?>" alt="" class="w-10 h-10 rounded-full mr-3 object-cover border-2 border-gray-200 flex-shrink-0">
                                                                <?php else: ?>
                                                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-3 border-2 border-gray-200 flex-shrink-0">
                                                                        <span class="text-primary-600 font-semibold text-sm"><?= strtoupper(substr($entrepreneur['name'], 0, 1)) ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="min-w-0 flex-1">
                                                                    <div class="text-xs sm:text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($entrepreneur['name']) ?></div>
                                                                    <!-- Mobile: Show email below name -->
                                                                    <div class="mt-1 sm:hidden">
                                                                        <div class="text-xs text-gray-600 truncate">
                                                                            <i class="fas fa-envelope mr-1 text-gray-400"></i>
                                                                            <?= htmlspecialchars($entrepreneur['email']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Mobile: Show phone and date below email -->
                                                                    <div class="mt-1 sm:hidden flex items-center gap-3 text-xs text-gray-500">
                                                                        <span>
                                                                            <i class="fas fa-phone mr-1 text-gray-400"></i>
                                                                            <?= htmlspecialchars($entrepreneur['phone'] ?? '-') ?>
                                                                        </span>
                                                                        <span>
                                                                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                                                            <?= Helper::formatDate($entrepreneur['created_at']) ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-envelope mr-1.5 text-gray-400"></i>
                                                                <span class="truncate max-w-[200px]"><?= htmlspecialchars($entrepreneur['email']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-phone mr-1.5 text-gray-400"></i>
                                                                <span><?= htmlspecialchars($entrepreneur['phone'] ?? '-') ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                                <i class="fas fa-seedling mr-1"></i>
                                                                <span><?= $entrepreneur['farm_count'] ?></span>
                                                            </span>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                                <i class="fas fa-store mr-1"></i>
                                                                <span><?= $entrepreneur['shop_count'] ?></span>
                                                            </span>
                                                        </td>
                                                        <td class="hidden lg:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                                <span><?= Helper::formatDate($entrepreneur['created_at']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                                                <a href="<?= BASE_URL ?>profile.php?user_id=<?= $entrepreneur['id'] ?>" class="btn-view inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="View">
                                                                    <i class="fas fa-eye sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">View</span>
                                                                </a>
                                                                <button type="button" onclick="editEntrepreneur(<?= $entrepreneur['id'] ?>)" class="btn-primary inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Edit">
                                                                    <i class="fas fa-edit sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Edit</span>
                                                                </button>
                                                                <button type="button" onclick="deleteEntrepreneur(<?= $entrepreneur['id'] ?>, '<?= htmlspecialchars(addslashes($entrepreneur['name'])) ?>')" class="btn-delete inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Delete">
                                                                    <i class="fas fa-trash sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Delete</span>
                                                                </button>
                                                            </div>
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

<!-- Edit Entrepreneur Modal -->
<?php if ($editUser): ?>
<div id="editEntrepreneurModal" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4" style="display: flex;">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-2xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200 rounded-t-xl sm:rounded-t-2xl flex justify-between items-center sticky top-0 z-10">
            <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-edit mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                <span>Edit Entrepreneur</span>
            </h5>
            <button type="button" onclick="closeEditModal()" class="modal-close p-1 sm:p-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        <form method="POST" action="" class="p-4 sm:p-6">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" value="<?= $editUser['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($editUser['name'] ?? '') ?>" required
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                </div>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Address</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none text-sm sm:text-base"><?= htmlspecialchars($editUser['address'] ?? '') ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Gender</label>
                    <select id="gender" name="gender" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base bg-white">
                        <option value="">Select Gender</option>
                        <option value="male" <?= ($editUser['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($editUser['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($editUser['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="ic_passport" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">IC/Passport</label>
                    <input type="text" id="ic_passport" name="ic_passport" value="<?= htmlspecialchars($editUser['ic_passport'] ?? '') ?>" placeholder="010203040506"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                </div>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="business_category" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Business Category</label>
                <select id="business_category" name="business_category" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base bg-white">
                    <option value="">Select Category</option>
                    <option value="Pineapple Farming" <?= ($editUser['business_category'] ?? '') === 'Pineapple Farming' ? 'selected' : '' ?>>Pineapple Farming</option>
                    <option value="Pineapple Processing" <?= ($editUser['business_category'] ?? '') === 'Pineapple Processing' ? 'selected' : '' ?>>Pineapple Processing</option>
                    <option value="Pineapple Retail" <?= ($editUser['business_category'] ?? '') === 'Pineapple Retail' ? 'selected' : '' ?>>Pineapple Retail</option>
                    <option value="Pineapple Export" <?= ($editUser['business_category'] ?? '') === 'Pineapple Export' ? 'selected' : '' ?>>Pineapple Export</option>
                    <option value="Agri-Tourism" <?= ($editUser['business_category'] ?? '') === 'Agri-Tourism' ? 'selected' : '' ?>>Agri-Tourism</option>
                    <option value="Other" <?= ($editUser['business_category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-gray-200 bg-gray-50 -mx-4 sm:-mx-6 -mb-4 sm:-mb-6 px-4 sm:px-6 py-3 sm:py-4 rounded-b-xl sm:rounded-b-2xl sticky bottom-0">
                <button type="button" onclick="closeEditModal()" class="btn-outline-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="btn-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Update Entrepreneur
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if ($message): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: '<?= $messageType === 'success' ? 'success' : 'error' ?>',
        title: '<?= $messageType === 'success' ? 'Success!' : 'Error!' ?>',
        text: '<?= addslashes($message) ?>',
        confirmButtonColor: '#d97706',
        timer: 3000,
        timerProgressBar: true
    });
});
</script>
<?php endif; ?>

<script>
function editEntrepreneur(userId) {
    window.location.href = '<?= BASE_URL ?>admin/entrepreneurs.php?edit=' + userId;
}

function closeEditModal() {
    window.location.href = '<?= BASE_URL ?>admin/entrepreneurs.php';
}

function deleteEntrepreneur(userId, userName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete entrepreneur "${userName}"? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = userId;
            form.appendChild(userIdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Form submission with confirmation for edit modal
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.querySelector('#editEntrepreneurModal form');
    if (editForm) {
        // Mark form to skip global handler
        editForm.setAttribute('data-has-confirmation', 'true');
        
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const submitBtn = editForm.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this entrepreneur information?',
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
                        text: 'Please wait while we update the entrepreneur information',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    editForm.submit();
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
/* Mobile-specific improvements for entrepreneurs page */
@media (max-width: 640px) {
    /* Improve table scrolling on mobile */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    
    /* Better spacing for action buttons */
    .flex.flex-wrap.items-center.gap-1\.5 > * {
        min-width: auto;
    }
    
    /* Ensure modal doesn't overflow on very small screens */
    #editEntrepreneurModal > div {
        margin: 0.5rem;
        max-height: calc(100vh - 1rem);
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    /* Optimize table spacing for tablets */
    table.data-table td,
    table.data-table th {
        padding: 0.75rem 0.5rem;
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
