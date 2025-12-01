<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance()->getConnection();
$currentPage = 'farm';

$message = '';
$messageType = '';
$editId = $_GET['edit'] ?? null;
$farm = null;

if ($editId) {
    $stmt = $db->prepare("SELECT * FROM farms WHERE id = ? AND user_id = ?");
    $stmt->execute([$editId, $_SESSION['user_id']]);
    $farm = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $farmId = $_POST['farm_id'] ?? null;
    
    $data = [
        'farm_name' => Helper::sanitize($_POST['farm_name'] ?? ''),
        'farm_size' => Helper::sanitize($_POST['farm_size'] ?? ''),
        'address' => Helper::sanitize($_POST['address'] ?? ''),
        'latitude' => $_POST['latitude'] ?? null,
        'longitude' => $_POST['longitude'] ?? null,
    ];
    
    if ($action === 'delete' && $farmId) {
        try {
            // Load farm with images and owner for permission check
            $stmt = $db->prepare("SELECT images, user_id FROM farms WHERE id = ?");
            $stmt->execute([$farmId]);
            $farmData = $stmt->fetch();

            if ($farmData && ($auth->isAdmin() || $farmData['user_id'] == $_SESSION['user_id'])) {
                if (!empty($farmData['images'])) {
                    $images = json_decode($farmData['images'], true);
                    if (is_array($images)) {
                        foreach ($images as $image) {
                            Helper::deleteFile($image);
                        }
                    }
                }

                $stmt = $db->prepare("DELETE FROM farms WHERE id = ?");
                if ($stmt->execute([$farmId])) {
                    $_SESSION['success_message'] = 'Farm deleted successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete farm.';
                }
            } else {
                $_SESSION['error_message'] = 'You are not allowed to delete this farm.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'An error occurred while deleting the farm.';
        }
        Helper::redirect(BASE_URL . 'farm.php');
    } elseif ($action === 'create' || $action === 'update') {
        // Handle images
        $images = [];
        if (!empty($_FILES['farm_images']['name'][0])) {
            foreach ($_FILES['farm_images']['tmp_name'] as $key => $tmpName) {
                $file = [
                    'name' => $_FILES['farm_images']['name'][$key],
                    'type' => $_FILES['farm_images']['type'][$key],
                    'tmp_name' => $tmpName,
                    'error' => $_FILES['farm_images']['error'][$key],
                    'size' => $_FILES['farm_images']['size'][$key]
                ];
                
                $upload = Helper::uploadFile($file, 'farms');
                if ($upload['success']) {
                    $images[] = $upload['path'];
                }
            }
        }
        
        // Images marked for removal (only relevant on update)
        $removeImages = isset($_POST['remove_images']) ? (array)$_POST['remove_images'] : [];
        
        if ($action === 'update' && $farmId) {
            // Keep existing images (minus any that are being removed)
            $stmt = $db->prepare("SELECT images FROM farms WHERE id = ? AND user_id = ?");
            $stmt->execute([$farmId, $_SESSION['user_id']]);
            $existing = $stmt->fetch();
            $existingImages = [];
            if ($existing && $existing['images']) {
                $existingImages = json_decode($existing['images'], true) ?: [];
            }

            // Delete removed images from disk and filter them out
            if (!empty($removeImages) && !empty($existingImages)) {
                foreach ($existingImages as $idx => $imgPath) {
                    if (in_array($imgPath, $removeImages, true)) {
                        Helper::deleteFile($imgPath);
                        unset($existingImages[$idx]);
                    }
                }
                $existingImages = array_values($existingImages);
            }

            // Merge remaining existing images with newly uploaded ones
            if (!empty($existingImages)) {
                $images = array_merge($existingImages, $images);
            }
            
            $stmt = $db->prepare("UPDATE farms SET farm_name = ?, farm_size = ?, address = ?, latitude = ?, longitude = ?, images = ? WHERE id = ? AND user_id = ?");
            $imagesJson = !empty($images) ? json_encode($images) : null;
            if ($stmt->execute([$data['farm_name'], $data['farm_size'], $data['address'], $data['latitude'], $data['longitude'], $imagesJson, $farmId, $_SESSION['user_id']])) {
                $_SESSION['success_message'] = 'Farm updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update farm.';
            }
            Helper::redirect(BASE_URL . 'farm.php');
        } else {
            $stmt = $db->prepare("INSERT INTO farms (user_id, farm_name, farm_size, address, latitude, longitude, images) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $imagesJson = !empty($images) ? json_encode($images) : null;
            if ($stmt->execute([$_SESSION['user_id'], $data['farm_name'], $data['farm_size'], $data['address'], $data['latitude'], $data['longitude'], $imagesJson])) {
                $_SESSION['success_message'] = 'Farm created successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to create farm.';
            }
            Helper::redirect(BASE_URL . 'farm.php');
        }
    }
}

// Get all farms
$stmt = $db->prepare("SELECT * FROM farms WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$farms = $stmt->fetchAll();

$googleMapsKey = Helper::getSetting('google_maps_api_key');
$defaultLat = Helper::getSetting('leaflet_default_lat', '3.1390');
$defaultLng = Helper::getSetting('leaflet_default_lng', '101.6869');

$pageTitle = 'My Farm';
$additionalJS = [];
if ($googleMapsKey) {
    $additionalJS[] = "https://maps.googleapis.com/maps/api/js?key={$googleMapsKey}&libraries=places";
}
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <!-- Header Section -->
                <div class="flex flex-col gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                                <i class="fas fa-seedling mr-2 sm:mr-3 text-primary-600"></i>
                                <span>My Farm</span>
                            </h1>
                            <p class="text-sm sm:text-base text-gray-600">Manage your farm locations and details</p>
                        </div>
                    </div>
                    <button type="button" onclick="openFarmModal()" data-no-transition class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base w-full sm:w-auto">
                        <i class="fas fa-plus mr-2"></i>Add Farm
                    </button>
                </div>
                
                <!-- Farms Table -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-list mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Farms List</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <?php if (empty($farms)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-seedling text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">No farms found</p>
                                <p class="text-gray-400 text-sm mt-2">Add your first farm to get started</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="data-table min-w-full divide-y divide-gray-200">
                                            <thead class="hidden sm:table-header-group">
                                                <tr>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Farm Name</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Size</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Address</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Images</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($farms as $f): ?>
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-seedling mr-2 text-primary-600 flex-shrink-0"></i>
                                                                <div class="min-w-0 flex-1">
                                                                    <div class="text-xs sm:text-sm font-semibold text-gray-900"><?= htmlspecialchars($f['farm_name']) ?></div>
                                                                    <!-- Mobile: Show size below name -->
                                                                    <div class="mt-1 sm:hidden text-xs text-gray-600">
                                                                        <i class="fas fa-ruler-combined mr-1 text-gray-400"></i>
                                                                        <?= htmlspecialchars($f['farm_size']) ?>
                                                                    </div>
                                                                    <!-- Mobile: Show address below size -->
                                                                    <div class="mt-1 sm:hidden text-xs text-gray-600 truncate">
                                                                        <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                                                        <?= htmlspecialchars(substr($f['address'], 0, 40)) ?>...
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-ruler-combined mr-1.5 text-gray-400"></i>
                                                                <span><?= htmlspecialchars($f['farm_size']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                                                <span class="truncate max-w-[200px]"><?= htmlspecialchars($f['address']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <?php if ($f['latitude'] && $f['longitude']): ?>
                                                                <a href="https://www.google.com/maps?q=<?= $f['latitude'] ?>,<?= $f['longitude'] ?>" target="_blank" class="btn-view inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap">
                                                                    <i class="fas fa-map-marker-alt sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">View Map</span>
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-gray-400 italic text-xs sm:text-sm">Not set</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="hidden lg:table-cell px-6 py-4 text-sm">
                                                            <?php if ($f['images']): ?>
                                                                <?php $images = json_decode($f['images'], true); ?>
                                                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                                    <i class="fas fa-images mr-1"></i>
                                                                    <span><?= count($images ?? []) ?> image(s)</span>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-gray-400 text-xs">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                                                <button type="button" onclick="editFarm(<?= $f['id'] ?>)" class="btn-primary inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Edit">
                                                                    <i class="fas fa-edit sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Edit</span>
                                                                </button>
                                                                <button type="button" onclick="deleteFarm(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes($f['farm_name'])) ?>')" class="btn-delete inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Delete" data-no-global-delete="true">
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

<!-- Farm Modal -->
<div id="farmModal" class="<?= $editId ? 'flex' : 'hidden' ?> fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-3xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200 rounded-t-xl sm:rounded-t-2xl flex justify-between items-center sticky top-0 z-10">
            <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-seedling mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                <span><?= $editId ? 'Edit' : 'Add' ?> Farm</span>
            </h5>
            <button type="button" onclick="closeFarmModal()" class="modal-close p-1 sm:p-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        <form method="POST" action="" enctype="multipart/form-data" id="farmForm" class="p-4 sm:p-6">
            <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
            <input type="hidden" name="farm_id" id="farm_id" value="<?= $editId ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                <div>
                    <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Farm Name <span class="text-red-500">*</span></label>
                    <input type="text" id="farm_name" name="farm_name" value="<?= $farm ? htmlspecialchars($farm['farm_name']) : '' ?>" required
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                </div>
                
                <div>
                    <label for="farm_size" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Farm Size</label>
                    <input type="text" id="farm_size" name="farm_size" value="<?= $farm ? htmlspecialchars($farm['farm_size'] ?? '') : '' ?>" placeholder="e.g., 10 acres"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                </div>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Address</label>
                <input type="text" id="address" name="address" value="<?= $farm ? htmlspecialchars($farm['address'] ?? '') : '' ?>" placeholder="Search address..."
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                <p class="mt-1 text-xs text-gray-500">Start typing to search for address</p>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <button type="button" onclick="openMapModal()" class="btn-view inline-flex items-center justify-center px-3 sm:px-4 py-2 text-sm sm:text-base w-full sm:w-auto mb-2 sm:mb-0">
                    <i class="fas fa-map-marker-alt mr-2"></i>Select Location on Map
                </button>
                <div class="mt-2 sm:mt-0 sm:ml-3 sm:inline-block">
                    <span class="text-xs sm:text-sm text-gray-600 font-medium">Lat: <span id="latDisplay" class="text-primary-600"><?= $farm && $farm['latitude'] ? $farm['latitude'] : '-' ?></span>, Lng: <span id="lngDisplay" class="text-primary-600"><?= $farm && $farm['longitude'] ? $farm['longitude'] : '-' ?></span></span>
                </div>
            </div>
            
            <input type="hidden" id="latitude" name="latitude" value="<?= $farm ? ($farm['latitude'] ?? '') : '' ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?= $farm ? ($farm['longitude'] ?? '') : '' ?>">
            
            <div class="mb-3 sm:mb-4">
                <label for="farm_images" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Farm Images</label>
                <input type="file" id="farm_images" name="farm_images[]" multiple accept="image/*"
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 text-sm sm:text-base">
            </div>

            <?php if ($farm && !empty($farm['images'])): ?>
                <?php $existingImages = json_decode($farm['images'], true) ?: []; ?>
                <?php if (!empty($existingImages)): ?>
                    <div class="mb-3 sm:mb-4">
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
            <?php endif; ?>
            
            <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-3 sm:mb-4"></div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-gray-200 bg-gray-50 -mx-4 sm:-mx-6 -mb-4 sm:-mb-6 px-4 sm:px-6 py-3 sm:py-4 rounded-b-xl sm:rounded-b-2xl sticky bottom-0">
                <button type="button" onclick="closeFarmModal()" class="btn-outline-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="btn-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Save Farm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Map Modal -->
<div id="mapModal" class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto flex flex-col">
        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200 rounded-t-xl sm:rounded-t-2xl flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 flex-shrink-0">
            <div class="flex items-center justify-between gap-3">
                <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                    <span>Select Farm Location</span>
                </h5>
                <button type="button" onclick="closeMapModal()" class="modal-close p-1 sm:p-2">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>
            <button type="button" onclick="useCurrentLocation('farm')" class="btn-primary inline-flex items-center justify-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm w-full sm:w-auto">
                <i class="fas fa-location-arrow mr-2"></i>Use Current Location
            </button>
        </div>
        <div class="p-3 sm:p-4 lg:p-6 flex-1 min-h-0">
            <div id="map" style="height: 300px; min-height: 300px;" class="rounded-lg sm:rounded-xl overflow-hidden border-2 border-gray-200 w-full"></div>
        </div>
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t-2 border-gray-200 rounded-b-xl sm:rounded-b-2xl flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 flex-shrink-0">
            <button type="button" onclick="closeMapModal()" class="btn-outline-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-2 sm:order-1">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
            <button type="button" onclick="saveLocation()" class="btn-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-1 sm:order-2">
                <i class="fas fa-save mr-2"></i>Save Location
            </button>
        </div>
    </div>
</div>

<script>
let map, marker;
const defaultLat = <?= $defaultLat ?>;
const defaultLng = <?= $defaultLng ?>;

// Modal functions - Ensure they're available globally
window.openFarmModal = function() {
    const modal = document.getElementById('farmModal');
    if (!modal) {
        console.error('Farm modal not found');
        return;
    }
    
    // Prevent double opening - check both hidden class and display style
    if (!modal.classList.contains('hidden') && modal.style.display !== 'none') {
        console.log('Modal already open');
        return;
    }
    
    console.log('Opening farm modal');
    // Remove hidden class first (Tailwind uses !important)
    modal.classList.remove('hidden');
    // Set display using inline style to override Tailwind's !important
    modal.style.setProperty('display', 'flex', 'important');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent body scroll
};

// Also define it without window for backwards compatibility
function openFarmModal() {
    window.openFarmModal();
}

window.closeFarmModal = function() {
    const modal = document.getElementById('farmModal');
    if (!modal) return;
    
    // Hide using both methods
    modal.style.setProperty('display', 'none', 'important');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = ''; // Restore body scroll
    
    // Reset form only if not submitting
    const form = document.getElementById('farmForm');
    if (form && !form.dataset.submitting) {
        form.reset();
        document.getElementById('farm_id').value = '';
        const actionInput = form.querySelector('input[name="action"]');
        if (actionInput) actionInput.value = 'create';
        document.getElementById('imagePreview').innerHTML = '';
    }
};

// Also define it without window for backwards compatibility
function closeFarmModal() {
    window.closeFarmModal();
}

window.openMapModal = function() {
    const modal = document.getElementById('mapModal');
    if (!modal) return;
    
    // Prevent double opening
    if (!modal.classList.contains('hidden') && modal.style.display !== 'none') return;
    
    // Remove hidden class first (Tailwind uses !important)
    modal.classList.remove('hidden');
    // Set display using inline style to override Tailwind's !important
    modal.style.setProperty('display', 'flex', 'important');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent body scroll
    
    // Initialize map with longer delay to ensure modal is fully visible
    setTimeout(() => {
        initMap();
        // Trigger resize after modal is fully open
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 200);
    }, 150);
};

window.closeMapModal = function() {
    const modal = document.getElementById('mapModal');
    if (!modal) return;
    
    // Hide using both methods
    modal.style.setProperty('display', 'none', 'important');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = ''; // Restore body scroll
};

// Also define them without window for backwards compatibility
function openMapModal() {
    window.openMapModal();
}

function closeMapModal() {
    window.closeMapModal();
}

function initMap() {
    if (!map) {
        map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Trigger resize after map initialization to ensure proper rendering
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        const currentLat = document.getElementById('latitude').value || defaultLat;
        const currentLng = document.getElementById('longitude').value || defaultLng;
        marker = L.marker([currentLat, currentLng], {draggable: true}).addTo(map);
        
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
        
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    } else {
        // Re-validate size if map already exists
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    document.getElementById('latDisplay').textContent = lat.toFixed(6);
    document.getElementById('lngDisplay').textContent = lng.toFixed(6);
}

function saveLocation() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    if (lat && lng) {
        closeMapModal();
    }
}

// Use browser's current location for farm/shop
function useCurrentLocation(type) {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Update hidden inputs and labels
            if (typeof updateCoordinates === 'function') {
                updateCoordinates(lat, lng);
            }

            // Center map + marker if available
            if (typeof map !== 'undefined' && map && typeof marker !== 'undefined' && marker) {
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
            }
        },
        function() {
            alert('Unable to retrieve your location. Please check your browser permissions.');
        }
    );
}

// Google Places Autocomplete
<?php if ($googleMapsKey): ?>
let autocomplete;
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('address'),
        { types: ['address'] }
    );
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();
            updateCoordinates(lat, lng);
            if (map && marker) {
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
            }
        }
    });
}

if (typeof google !== 'undefined' && google.maps) {
    initAutocomplete();
}
<?php endif; ?>

// Form submission with confirmation
document.addEventListener('DOMContentLoaded', function() {
    const farmForm = document.getElementById('farmForm');
    if (farmForm) {
        // Mark form to skip global handler
        farmForm.setAttribute('data-has-confirmation', 'true');
        
        farmForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const submitBtn = farmForm.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            const isUpdate = farmForm.querySelector('input[name="action"]').value === 'update';
            const actionText = isUpdate ? 'update' : 'save';
            const actionTextCapital = isUpdate ? 'Update' : 'Save';
            
            Swal.fire({
                title: `Are you sure?`,
                text: `Do you want to ${actionText} this farm information?`,
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
                        title: `${actionTextCapital}ing Farm...`,
                        text: `Please wait while we ${actionText} your farm information`,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    farmForm.submit();
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

function editFarm(farmId) {
    window.location.href = '<?= BASE_URL ?>farm.php?edit=' + farmId;
}

// Load farm data for editing when page loads with editId
<?php if ($farm): ?>
document.addEventListener('DOMContentLoaded', function() {
    openFarmModal();
    <?php if ($farm['latitude'] && $farm['longitude']): ?>
    updateCoordinates(<?= $farm['latitude'] ?>, <?= $farm['longitude'] ?>);
    <?php endif; ?>
});
<?php endif; ?>

// Close modals when clicking outside (ensure only added once)
(function() {
    const farmModal = document.getElementById('farmModal');
    const mapModal = document.getElementById('mapModal');
    
    if (farmModal) {
        // Remove existing listeners by cloning
        const newFarmModal = farmModal.cloneNode(true);
        farmModal.parentNode.replaceChild(newFarmModal, farmModal);
        
        document.getElementById('farmModal').addEventListener('click', function(e) {
            if (e.target === this) closeFarmModal();
        });
    }
    
    if (mapModal) {
        // Remove existing listeners by cloning
        const newMapModal = mapModal.cloneNode(true);
        mapModal.parentNode.replaceChild(newMapModal, mapModal);
        
        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });
    }
})();
</script>

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
function deleteFarm(farmId, farmName) {
    console.log('Attempting to delete farm', { farmId, farmName });
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete farm "${farmName}"? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Delete confirmed for farm', farmId);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            
            const farmIdInput = document.createElement('input');
            farmIdInput.type = 'hidden';
            farmIdInput.name = 'farm_id';
            farmIdInput.value = farmId;
            form.appendChild(farmIdInput);
            
            document.body.appendChild(form);
            console.log('Submitting farm delete form with payload:', {
                action: actionInput.value,
                farm_id: farmIdInput.value
            });
            form.submit();
        }
    });
}
</script>

<script>
    // Multiple image preview for farm images
document.addEventListener('DOMContentLoaded', function() {
    const farmImagesInput = document.getElementById('farm_images');
    const imagePreview = document.getElementById('imagePreview');
    
    if (farmImagesInput && imagePreview) {
        farmImagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            imagePreview.innerHTML = ''; // Clear existing previews
            
            if (files.length === 0) return;
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-24 sm:h-32 object-cover rounded-lg border-2 border-gray-200';
                        
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity';
                        button.title = 'Remove';
                        button.onclick = function() { removeImagePreview(index); };
                        button.innerHTML = '<i class="fas fa-times"></i>';
                        
                        div.appendChild(img);
                        div.appendChild(button);
                        imagePreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }
});

function removeImagePreview(index) {
    const input = document.getElementById('farm_images');
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

<style>
/* Mobile-specific improvements for farm page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    input[type="email"],
    textarea,
    select {
        font-size: 16px !important;
    }
    
    /* Better spacing for file input */
    input[type="file"] {
        font-size: 14px !important;
    }
    
    /* Map responsive sizing */
    #map {
        height: 300px !important;
        min-height: 300px !important;
    }
    
    /* Image preview responsive grid */
    #imagePreview {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    /* Optimize table spacing for tablets */
    table.data-table td,
    table.data-table th {
        padding: 0.75rem 0.5rem;
    }
    
    /* Map sizing for tablets */
    #map {
        height: 400px !important;
    }
}

/* Desktop map sizing */
@media (min-width: 1025px) {
    #map {
        height: 500px !important;
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

/* Map modal responsive adjustments */
@media (max-width: 640px) {
    #mapModal .flex-col {
        max-height: calc(100vh - 1.5rem);
    }
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
