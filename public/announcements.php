<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$db = Database::getInstance()->getConnection();
$currentPage = 'announcements';

$viewId = $_GET['view'] ?? null;
$editId = $_GET['edit'] ?? null;

// View single announcement
if ($viewId && !$auth->isLoggedIn()) {
    // Allow guests to view
} elseif (!$auth->isLoggedIn()) {
    Helper::redirect(BASE_URL . 'auth/login.php');
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->isLoggedIn()) {
    $action = $_POST['action'] ?? 'create';
    $announcementId = $_POST['announcement_id'] ?? null;
    
    $data = [
        'title' => Helper::sanitize($_POST['title'] ?? ''),
        'type' => Helper::sanitize($_POST['type'] ?? 'other'),
        'description' => Helper::sanitize($_POST['description'] ?? ''),
    ];

    // Handle multiple images
    $images = [];
    
    // Handle new image uploads (multiple files)
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'type' => $_FILES['images']['type'][$key],
                    'tmp_name' => $tmpName,
                    'error' => $_FILES['images']['error'][$key],
                    'size' => $_FILES['images']['size'][$key]
                ];
                
                $upload = Helper::uploadFile($file, 'announcements');
                if ($upload['success']) {
                    $images[] = $upload['path'];
                }
            }
        }
    }
    
    // Handle image removal (on update)
    $removeImages = $_POST['remove_images'] ?? [];
    if (!empty($removeImages) && $announcementId) {
        foreach ($removeImages as $imgPath) {
            Helper::deleteFile($imgPath);
        }
    }
    
    if ($action === 'delete' && $announcementId) {
        try {
            // Load announcement to check ownership and image
            $stmt = $db->prepare("SELECT image, created_by FROM announcements WHERE id = ?");
            $stmt->execute([$announcementId]);
            $ann = $stmt->fetch();

            if ($ann && ($auth->isAdmin() || ($auth->isEntrepreneur() && $ann['created_by'] == $_SESSION['user_id']))) {
                // Delete all images (both old single image and new images array)
                if (!empty($ann['image'])) {
                    Helper::deleteFile($ann['image']);
                }
                if (!empty($ann['images'])) {
                    $imageArray = json_decode($ann['images'], true);
                    if (is_array($imageArray)) {
                        foreach ($imageArray as $img) {
                            Helper::deleteFile($img);
                        }
                    }
                }

                $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
                if ($stmt->execute([$announcementId])) {
                    $_SESSION['success_message'] = 'Announcement deleted successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete announcement.';
                }
            } else {
                $_SESSION['error_message'] = 'You are not allowed to delete this announcement.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'An error occurred while deleting the announcement.';
        }
        Helper::redirect(BASE_URL . 'announcements.php');
    } elseif ($action === 'create' || $action === 'update') {
        if ($action === 'update' && $announcementId) {
            // Get existing images
            $stmt = $db->prepare("SELECT images FROM announcements WHERE id = ?");
            $stmt->execute([$announcementId]);
            $existing = $stmt->fetch();
            $existingImages = [];
            
            if ($existing && !empty($existing['images'])) {
                $existingImages = json_decode($existing['images'], true) ?: [];
            }
            
            // Remove deleted images from array
            if (!empty($removeImages)) {
                $existingImages = array_filter($existingImages, function($img) use ($removeImages) {
                    return !in_array($img, $removeImages);
                });
                $existingImages = array_values($existingImages); // Re-index
            }
            
            // Merge with new uploads
            $allImages = array_merge($existingImages, $images);
            $imagesJson = !empty($allImages) ? json_encode($allImages) : null;
            
            $sql = "UPDATE announcements SET title = ?, type = ?, description = ?, images = ?";
            $params = [$data['title'], $data['type'], $data['description'], $imagesJson];
            
            $sql .= " WHERE id = ?";
            $params[] = $announcementId;
            
            try {
                if ($auth->isAdmin()) {
                    $stmt = $db->prepare($sql);
                    if ($stmt->execute($params)) {
                        $_SESSION['success_message'] = 'Announcement updated successfully!';
                    } else {
                        $_SESSION['error_message'] = 'Failed to update announcement.';
                    }
                } elseif ($auth->isEntrepreneur()) {
                    $sql .= " AND created_by = ?";
                    $params[] = $_SESSION['user_id'];
                    $stmt = $db->prepare($sql);
                    if ($stmt->execute($params)) {
                        $_SESSION['success_message'] = 'Announcement updated successfully!';
                    } else {
                        $_SESSION['error_message'] = 'Failed to update announcement.';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'An error occurred while updating the announcement.';
            }
        } else {
            try {
                $imagesJson = !empty($images) ? json_encode($images) : null;
                $sql = "INSERT INTO announcements (title, type, description, images, created_by) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                if ($stmt->execute([$data['title'], $data['type'], $data['description'], $imagesJson, $_SESSION['user_id']])) {
                    $_SESSION['success_message'] = 'Announcement created successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to create announcement.';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'An error occurred while creating the announcement.';
            }
        }
        Helper::redirect(BASE_URL . 'announcements.php');
    }
}

// Get announcement for editing
$editAnnouncement = null;
if ($editId) {
    $stmt = $db->prepare("SELECT a.*, u.name as created_by_name FROM announcements a 
                          LEFT JOIN users u ON a.created_by = u.id 
                          WHERE a.id = ?");
    $stmt->execute([$editId]);
    $editAnnouncement = $stmt->fetch();
    
    if (!$editAnnouncement || (!$auth->isAdmin() && $editAnnouncement['created_by'] != $_SESSION['user_id'])) {
        Helper::redirect(BASE_URL . 'announcements.php');
    }
}

// Get announcements
if ($viewId) {
    $stmt = $db->prepare("SELECT a.*, u.name as created_by_name FROM announcements a 
                          LEFT JOIN users u ON a.created_by = u.id 
                          WHERE a.id = ?");
    $stmt->execute([$viewId]);
    $announcement = $stmt->fetch();
    
    if (!$announcement) {
        Helper::redirect(BASE_URL . 'announcements.php');
    }
} else {
    if ($auth->isAdmin()) {
        $stmt = $db->query("SELECT a.*, u.name as created_by_name FROM announcements a 
                            LEFT JOIN users u ON a.created_by = u.id 
                            ORDER BY a.created_at DESC");
    } else {
        $stmt = $db->query("SELECT a.*, u.name as created_by_name FROM announcements a 
                            LEFT JOIN users u ON a.created_by = u.id 
                            ORDER BY a.created_at DESC");
    }
    $announcements = $stmt->fetchAll();
}

$pageTitle = $viewId ? 'View Announcement' : 'Announcements';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php if ($auth->isLoggedIn()): ?>
        <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    <?php endif; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <?php if ($viewId && $announcement): ?>
                    <!-- Single Announcement View -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 flex items-center flex-wrap">
                            <i class="fas fa-bullhorn mr-2 sm:mr-3 text-primary-600"></i>
                            <span class="break-words"><?= htmlspecialchars_decode($announcement['title'] ?? '', ENT_QUOTES) ?></span>
                        </h1>
                        <a href="<?= BASE_URL ?>announcements.php" class="btn-outline-primary inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 text-sm sm:text-base w-full sm:w-auto whitespace-nowrap">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                    </div>
                    
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <?php 
                        // Get images (support both old 'image' field and new 'images' field)
                        $announcementImages = [];
                        if (!empty($announcement['images'])) {
                            $announcementImages = json_decode($announcement['images'], true) ?: [];
                        } elseif (!empty($announcement['image'])) {
                            $announcementImages = [$announcement['image']];
                        }
                        ?>
                        <?php if (!empty($announcementImages)): ?>
                            <div class="swiper announcementViewSwiper w-full max-h-96 sm:max-h-[28rem] bg-black">
                                <div class="swiper-wrapper">
                                    <?php foreach ($announcementImages as $img): ?>
                                        <div class="swiper-slide flex items-center justify-center">
                                            <img src="<?= BASE_URL . $img ?>" alt="<?= htmlspecialchars_decode($announcement['title'] ?? '', ENT_QUOTES) ?>" class="w-full h-full object-contain">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($announcementImages) > 1): ?>
                                    <div class="swiper-button-next text-white"></div>
                                    <div class="swiper-button-prev text-white"></div>
                                    <div class="swiper-pagination"></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                                <span class="inline-flex items-center px-3 py-1 text-xs sm:text-sm font-semibold rounded-full <?= $announcement['type'] === 'price' ? 'bg-green-100 text-green-800' : ($announcement['type'] === 'promotion' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') ?>">
                                    <?= ucfirst($announcement['type']) ?>
                                </span>
                                <span class="text-xs sm:text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i><?= Helper::formatDate($announcement['created_at']) ?>
                                </span>
                            </div>
                            <p class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line break-words"><?= htmlspecialchars_decode($announcement['description'] ?? '', ENT_QUOTES) ?></p>
                            <?php if ($announcement['created_by_name']): ?>
                                <p class="text-xs sm:text-sm text-gray-500 mt-4">
                                    Posted by: <?= htmlspecialchars_decode($announcement['created_by_name'] ?? '', ENT_QUOTES) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Announcements List -->
                    <div class="flex flex-col gap-4 sm:gap-6 mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                                    <i class="fas fa-bullhorn mr-2 sm:mr-3 text-primary-600"></i>
                                    <span>Announcements</span>
                                </h1>
                                <p class="text-sm sm:text-base text-gray-600">Stay updated with the latest news and updates</p>
                            </div>
                        </div>
                        <?php if ($auth->isLoggedIn() && ($auth->isAdmin() || $auth->isEntrepreneur())): ?>
                            <button type="button" onclick="openAnnouncementModal()" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base w-full sm:w-auto">
                                <i class="fas fa-plus mr-2"></i>Create Announcement
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Announcements Table -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-list mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                                <span>Announcements List</span>
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <?php if (empty($announcements)): ?>
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-bullhorn text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No announcements found</p>
                                    <p class="text-gray-400 text-sm mt-2">Create your first announcement to get started</p>
                                </div>
                            <?php else: ?>
                                <div class="overflow-x-auto -mx-4 sm:mx-0">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden">
                                            <table class="data-table min-w-full divide-y divide-gray-200">
                                                <thead class="hidden sm:table-header-group">
                                                    <tr>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Image</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Description</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Created By</th>
                                                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    <?php foreach ($announcements as $ann): ?>
                                                        <tr class="hover:bg-gray-50 transition-colors">
                                                            <!-- Desktop: Image column (first on large screens) -->
                                                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                                                                <?php 
                                                                $annImages = [];
                                                                if (!empty($ann['images'])) {
                                                                    $annImages = json_decode($ann['images'], true) ?: [];
                                                                } elseif (!empty($ann['image'])) {
                                                                    $annImages = [$ann['image']];
                                                                }
                                                                ?>
                                                                <?php if (!empty($annImages)): ?>
                                                                    <img src="<?= BASE_URL . $annImages[0] ?>" alt="<?= htmlspecialchars_decode($ann['title'] ?? '', ENT_QUOTES) ?>" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200">
                                                                <?php else: ?>
                                                                    <div class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                                                        <i class="fas fa-image text-gray-400 text-xl"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <!-- Title column - Mobile shows everything, Desktop shows just title -->
                                                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                                <div class="flex items-start gap-3">
                                                                    <!-- Mobile: Show image on left -->
                                                                    <?php if (!empty($annImages)): ?>
                                                                        <img src="<?= BASE_URL . $annImages[0] ?>" alt="<?= htmlspecialchars($ann['title']) ?>" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg border-2 border-gray-200 flex-shrink-0 lg:hidden">
                                                                    <?php else: ?>
                                                                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center flex-shrink-0 lg:hidden">
                                                                            <i class="fas fa-image text-gray-400 text-base sm:text-xl"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="min-w-0 flex-1">
                                                                        <div class="text-xs sm:text-sm font-semibold text-gray-900"><?= htmlspecialchars_decode($ann['title'] ?? '', ENT_QUOTES) ?></div>
                                                                        <!-- Mobile: Show type, description, date, and created by below title -->
                                                                        <div class="flex flex-wrap items-center gap-2 sm:hidden mb-2 mt-1">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold <?= $ann['type'] === 'price' ? 'bg-green-100 text-green-800' : ($ann['type'] === 'promotion' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') ?>">
                                                                                <?= ucfirst($ann['type']) ?>
                                                                            </span>
                                                                            <span class="text-xs text-gray-500">
                                                                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                                                                <?= Helper::formatDate($ann['created_at']) ?>
                                                                            </span>
                                                                        </div>
                                                                        <div class="text-xs text-gray-600 mb-2 sm:hidden line-clamp-2">
                                                                            <?= htmlspecialchars_decode(substr($ann['description'] ?? '', 0, 80), ENT_QUOTES) ?><?= strlen($ann['description'] ?? '') > 80 ? '...' : '' ?>
                                                                        </div>
                                                                        <?php if ($ann['created_by_name']): ?>
                                                                            <div class="text-xs text-gray-500 sm:hidden">
                                                                                <i class="fas fa-user mr-1 text-gray-400"></i>
                                                                                <?= htmlspecialchars($ann['created_by_name'] ?? 'System') ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <!-- Type column (hidden on mobile, shown on desktop) -->
                                                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold <?= $ann['type'] === 'price' ? 'bg-green-100 text-green-800' : ($ann['type'] === 'promotion' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') ?>">
                                                                    <?= ucfirst($ann['type']) ?>
                                                                </span>
                                                            </td>
                                                            <!-- Description column (hidden on mobile/tablet) -->
                                                            <td class="hidden md:table-cell px-6 py-4">
                                                                    <div class="text-sm text-gray-600 max-w-xs truncate">
                                                                    <?= htmlspecialchars_decode(substr($ann['description'] ?? '', 0, 100), ENT_QUOTES) ?><?= strlen($ann['description'] ?? '') > 100 ? '...' : '' ?>
                                                                </div>
                                                            </td>
                                                            <!-- Created date column (hidden on mobile/tablet) -->
                                                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                                    <span><?= Helper::formatDate($ann['created_at']) ?></span>
                                                                </div>
                                                            </td>
                                                            <!-- Created by column (hidden on mobile/tablet/desktop, shown on xl) -->
                                                            <td class="hidden xl:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-user mr-1.5 text-gray-400"></i>
                                                                    <span><?= htmlspecialchars_decode($ann['created_by_name'] ?? 'System', ENT_QUOTES) ?></span>
                                                                </div>
                                                            </td>
                                                            <!-- Actions column -->
                                                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                                                    <a href="?view=<?= $ann['id'] ?>" class="btn-view inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="View">
                                                                        <i class="fas fa-eye sm:mr-1.5"></i>
                                                                        <span class="hidden sm:inline">View</span>
                                                                    </a>
                                                                    <?php if ($auth->isLoggedIn() && ($auth->isAdmin() || ($auth->isEntrepreneur() && $ann['created_by'] == $_SESSION['user_id']))): ?>
                                                                        <button type="button" onclick="editAnnouncement(<?= $ann['id'] ?>)" class="btn-primary inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Edit">
                                                                            <i class="fas fa-edit sm:mr-1.5"></i>
                                                                            <span class="hidden sm:inline">Edit</span>
                                                                        </button>
                                                                        <button type="button" onclick="deleteAnnouncement(<?= $ann['id'] ?>, '<?= htmlspecialchars(addslashes($ann['title'])) ?>')" class="btn-delete inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Delete" data-no-global-delete="true">
                                                                            <i class="fas fa-trash sm:mr-1.5"></i>
                                                                            <span class="hidden sm:inline">Delete</span>
                                                                        </button>
                                                                    <?php endif; ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal (for logged in users) -->
<?php if ($auth->isLoggedIn() && ($auth->isAdmin() || $auth->isEntrepreneur())): ?>
<div id="announcementModal" class="<?= $editAnnouncement ? 'flex' : 'hidden' ?> fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-2xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200 rounded-t-xl sm:rounded-t-2xl flex justify-between items-center sticky top-0 z-10">
            <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-bullhorn mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                <span><?= $editAnnouncement ? 'Edit' : 'Create' ?> Announcement</span>
            </h5>
            <button type="button" onclick="closeAnnouncementModal()" class="modal-close p-1 sm:p-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        <form method="POST" action="" enctype="multipart/form-data" class="p-4 sm:p-6">
            <input type="hidden" name="action" value="<?= $editAnnouncement ? 'update' : 'create' ?>">
            <?php if ($editAnnouncement): ?>
                <input type="hidden" name="announcement_id" value="<?= $editAnnouncement['id'] ?>">
            <?php endif; ?>
            
            <div class="mb-3 sm:mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="<?= $editAnnouncement ? htmlspecialchars($editAnnouncement['title']) : '' ?>" required
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Type <span class="text-red-500">*</span></label>
                <select id="type" name="type" required
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                    <option value="price" <?= $editAnnouncement && $editAnnouncement['type'] === 'price' ? 'selected' : '' ?>>Price</option>
                    <option value="promotion" <?= $editAnnouncement && $editAnnouncement['type'] === 'promotion' ? 'selected' : '' ?>>Promotion</option>
                    <option value="roadshow" <?= $editAnnouncement && $editAnnouncement['type'] === 'roadshow' ? 'selected' : '' ?>>Roadshow</option>
                    <option value="news" <?= $editAnnouncement && $editAnnouncement['type'] === 'news' ? 'selected' : '' ?>>News</option>
                    <option value="other" <?= $editAnnouncement && $editAnnouncement['type'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Description <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="5" required
                          class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none text-sm sm:text-base"><?= $editAnnouncement ? htmlspecialchars($editAnnouncement['description']) : '' ?></textarea>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="images" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Images</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 text-sm sm:text-base">
                <p class="mt-1 text-xs text-gray-500">You can select multiple images</p>
                
                <?php 
                // Get existing images (support both old 'image' field and new 'images' field)
                $existingImages = [];
                if ($editAnnouncement) {
                    if (!empty($editAnnouncement['images'])) {
                        $existingImages = json_decode($editAnnouncement['images'], true) ?: [];
                    } elseif (!empty($editAnnouncement['image'])) {
                        // Backward compatibility: migrate single image to array
                        $existingImages = [$editAnnouncement['image']];
                    }
                }
                ?>
                
                <?php if (!empty($existingImages)): ?>
                    <div class="mt-3">
                        <p class="text-sm font-medium text-gray-700 mb-2">Existing Images</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                            <?php foreach ($existingImages as $img): ?>
                                <div class="relative group border-2 border-gray-200 rounded-lg overflow-hidden">
                                    <img src="<?= BASE_URL . $img ?>" class="w-full h-24 sm:h-32 object-cover">
                                    <label class="absolute inset-x-0 bottom-0 bg-black/50 text-white text-xs sm:text-sm px-2 py-1 flex items-center justify-center space-x-1 cursor-pointer">
                                        <input type="checkbox" name="remove_images[]" value="<?= htmlspecialchars($img) ?>" class="rounded border-gray-300 text-red-500 focus:ring-red-500 mr-1">
                                        <span>Remove</span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div id="announcementImagePreview" class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mt-3"></div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-gray-200 bg-gray-50 -mx-4 sm:-mx-6 -mb-4 sm:-mb-6 px-4 sm:px-6 py-3 sm:py-4 rounded-b-xl sm:rounded-b-2xl sticky bottom-0">
                <button type="button" onclick="closeAnnouncementModal()" class="btn-outline-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="btn-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i><?= $editAnnouncement ? 'Update' : 'Create' ?> Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAnnouncementModal() {
    const modal = document.getElementById('announcementModal');
    if (!modal) return;
    
    // Prevent double opening
    if (!modal.classList.contains('hidden') && modal.style.display !== 'none') return;
    
    modal.classList.remove('hidden');
    modal.style.setProperty('display', 'flex', 'important');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeAnnouncementModal() {
    const modal = document.getElementById('announcementModal');
    if (!modal) return;
    
    modal.style.setProperty('display', 'none', 'important');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Close modal when clicking outside (ensure only added once)
(function() {
    const modal = document.getElementById('announcementModal');
    if (modal) {
        // Remove existing listeners by cloning
        const newModal = modal.cloneNode(true);
        modal.parentNode.replaceChild(newModal, modal);
        
        document.getElementById('announcementModal').addEventListener('click', function(e) {
            if (e.target === this) closeAnnouncementModal();
        });
    }
})();
</script>
<?php endif; ?>

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
function editAnnouncement(announcementId) {
    window.location.href = '<?= BASE_URL ?>announcements.php?edit=' + announcementId;
}

function deleteAnnouncement(announcementId, announcementTitle) {
    console.log('Attempting to delete announcement', { announcementId, announcementTitle });
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete announcement "${announcementTitle}"? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Delete confirmed for announcement', announcementId);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            
            const announcementIdInput = document.createElement('input');
            announcementIdInput.type = 'hidden';
            announcementIdInput.name = 'announcement_id';
            announcementIdInput.value = announcementId;
            form.appendChild(announcementIdInput);
            
            document.body.appendChild(form);
            console.log('Submitting announcement delete form with payload:', {
                action: actionInput.value,
                announcement_id: announcementIdInput.value
            });
            form.submit();
        }
    });
}

// Form submission with confirmation for announcement modal
document.addEventListener('DOMContentLoaded', function() {
    const announcementForm = document.querySelector('#announcementModal form');
    if (announcementForm) {
        // Mark form to skip global handler
        announcementForm.setAttribute('data-has-confirmation', 'true');
        
        announcementForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const submitBtn = announcementForm.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            const isUpdate = announcementForm.querySelector('input[name="action"]').value === 'update';
            const actionText = isUpdate ? 'update' : 'create';
            const actionTextCapital = isUpdate ? 'Update' : 'Create';
            
            Swal.fire({
                title: `Are you sure?`,
                text: `Do you want to ${actionText} this announcement?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Yes, ${actionText} it!`,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    }
                    Swal.fire({
                        title: `${actionTextCapital}ing Announcement...`,
                        text: `Please wait while we ${actionText} your announcement`,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    announcementForm.submit();
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
    
    // Auto-open modal if editing
    <?php if ($editAnnouncement): ?>
    openAnnouncementModal();
    <?php endif; ?>
});
</script>

<script>
// Multiple image preview for announcements
document.addEventListener('DOMContentLoaded', function() {
    const imagesInput = document.getElementById('images');
    const previewContainer = document.getElementById('announcementImagePreview');
    
    if (imagesInput && previewContainer) {
        imagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            previewContainer.innerHTML = ''; // Clear existing previews
            
            if (files.length === 0) return;
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 sm:h-32 object-cover rounded-lg border-2 border-gray-200">
                            <button type="button" onclick="removeAnnouncementImagePreview(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }
});

function removeAnnouncementImagePreview(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
}
</script>

<?php if ($viewId && !empty($announcementImages)): ?>
<script>
// Initialize Swiper for announcement view carousel
document.addEventListener('DOMContentLoaded', function() {
    const announcementSwiper = new Swiper('.announcementViewSwiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: <?= count($announcementImages) > 1 ? 'true' : 'false' ?>,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });
});
</script>
<?php endif; ?>

<style>
/* Mobile-specific improvements for announcements page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    textarea,
    select {
        font-size: 16px !important;
    }
    
    /* Better spacing for file input */
    input[type="file"] {
        font-size: 14px !important;
    }
    
    /* Image responsive sizing */
    .image-preview-container img,
    #announcementModal img {
        max-width: 100%;
        height: auto;
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

/* Line clamp utility for description truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
