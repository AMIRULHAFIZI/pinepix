<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();
$auth->requireAdmin();

$db = Database::getInstance()->getConnection();
$currentPage = 'faq';

$message = '';
$messageType = '';
$editId = $_GET['edit'] ?? null;
$faq = null;

if ($editId) {
    $stmt = $db->prepare("SELECT * FROM faq_knowledge WHERE id = ?");
    $stmt->execute([$editId]);
    $faq = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $faqId = $_POST['faq_id'] ?? null;
    
    $data = [
        'question' => Helper::sanitize($_POST['question'] ?? ''),
        'answer' => Helper::sanitize($_POST['answer'] ?? ''),
    ];
    
    if ($action === 'delete' && $faqId) {
        try {
            $stmt = $db->prepare("DELETE FROM faq_knowledge WHERE id = ?");
            if ($stmt->execute([$faqId])) {
                $_SESSION['success_message'] = 'FAQ deleted successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to delete FAQ.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'An error occurred while deleting the FAQ.';
        }
        Helper::redirect(BASE_URL . 'admin/faq.php');
    } elseif ($action === 'create' || $action === 'update') {
        try {
            if ($action === 'update' && $faqId) {
                $stmt = $db->prepare("UPDATE faq_knowledge SET question = ?, answer = ? WHERE id = ?");
                if ($stmt->execute([$data['question'], $data['answer'], $faqId])) {
                    $_SESSION['success_message'] = 'FAQ updated successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to update FAQ.';
                }
            } else {
                $stmt = $db->prepare("INSERT INTO faq_knowledge (question, answer) VALUES (?, ?)");
                if ($stmt->execute([$data['question'], $data['answer']])) {
                    $_SESSION['success_message'] = 'FAQ created successfully!';
                } else {
                    $_SESSION['error_message'] = 'Failed to create FAQ.';
                }
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'An error occurred while saving the FAQ.';
        }
        Helper::redirect(BASE_URL . 'admin/faq.php');
    }
}

// Get all FAQs
$stmt = $db->query("SELECT * FROM faq_knowledge ORDER BY created_at DESC");
$faqs = $stmt->fetchAll();

$pageTitle = 'FAQ Knowledge Base';
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
                                <i class="fas fa-question-circle mr-2 sm:mr-3 text-primary-600"></i>
                                <span>FAQ Knowledge Base</span>
                            </h1>
                            <p class="text-sm sm:text-base text-gray-600">Manage frequently asked questions for the chatbot</p>
                        </div>
                    </div>
                    <button type="button" onclick="openFaqModal()" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base w-full sm:w-auto">
                        <i class="fas fa-plus mr-2"></i>Add FAQ
                    </button>
                </div>
                
                <!-- FAQ Table -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-list mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>FAQs List</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <?php if (empty($faqs)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-question-circle text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">No FAQs found</p>
                                <p class="text-gray-400 text-sm mt-2">Add your first FAQ to get started</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="data-table min-w-full divide-y divide-gray-200">
                                            <thead class="hidden sm:table-header-group">
                                                <tr>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Question</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">Answer</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($faqs as $f): ?>
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="text-xs sm:text-sm font-semibold text-gray-900 mb-1">
                                                                <i class="fas fa-question-circle mr-1 sm:mr-2 text-primary-600"></i>
                                                                <?= htmlspecialchars($f['question']) ?>
                                                            </div>
                                                            <div class="text-xs text-gray-600 sm:hidden line-clamp-2 mt-1">
                                                                <i class="fas fa-comment-dots mr-1 text-gray-400"></i>
                                                                <?= htmlspecialchars(substr($f['answer'], 0, 80)) ?><?= strlen($f['answer']) > 80 ? '...' : '' ?>
                                                            </div>
                                                            <div class="text-xs text-gray-500 sm:hidden mt-1">
                                                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                                                <?= Helper::formatDate($f['created_at']) ?>
                                                            </div>
                                                        </td>
                                                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                                                            <i class="fas fa-comment-dots mr-2 text-gray-400"></i>
                                                            <?= htmlspecialchars(substr($f['answer'], 0, 100)) ?><?= strlen($f['answer']) > 100 ? '...' : '' ?>
                                                        </td>
                                                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                                <span><?= Helper::formatDate($f['created_at']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                                                <button type="button" onclick="loadFAQ(<?= $f['id'] ?>, '<?= addslashes($f['question']) ?>', '<?= addslashes($f['answer']) ?>'); openFaqModal()" 
                                                                        class="btn-primary inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Edit">
                                                                    <i class="fas fa-edit sm:mr-1.5"></i>
                                                                    <span class="hidden sm:inline">Edit</span>
                                                                </button>
                                                                <button type="button" onclick="deleteFAQ(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes(substr($f['question'], 0, 50))) ?>')" class="btn-delete inline-flex items-center justify-center px-2 sm:px-3 py-1.5 sm:py-2 text-xs whitespace-nowrap" title="Delete">
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

<!-- FAQ Modal -->
<div id="faqModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm p-3 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-2xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
        <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200 rounded-t-xl sm:rounded-t-2xl flex justify-between items-center sticky top-0 z-10">
            <h5 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-question-circle mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                <span><?= $editId ? 'Edit' : 'Add' ?> FAQ</span>
            </h5>
            <button type="button" onclick="closeFaqModal()" class="modal-close p-1 sm:p-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        <form method="POST" action="" id="faqForm" class="p-4 sm:p-6">
            <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
            <input type="hidden" name="faq_id" id="faq_id" value="<?= $editId ?>">
            
            <div class="mb-3 sm:mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Question <span class="text-red-500">*</span></label>
                <textarea id="question" name="question" rows="2" required
                          class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none text-sm sm:text-base"></textarea>
            </div>
            
            <div class="mb-3 sm:mb-4">
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Answer <span class="text-red-500">*</span></label>
                <textarea id="answer" name="answer" rows="5" required
                          class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none text-sm sm:text-base"></textarea>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-gray-200 bg-gray-50 -mx-4 sm:-mx-6 -mb-4 sm:-mb-6 px-4 sm:px-6 py-3 sm:py-4 rounded-b-xl sm:rounded-b-2xl sticky bottom-0">
                <button type="button" onclick="closeFaqModal()" class="btn-outline-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="btn-primary w-full sm:w-auto px-4 sm:px-6 py-2.5 text-sm sm:text-base order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Save FAQ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openFaqModal() {
    const modal = document.getElementById('faqModal');
    if (!modal) return;
    
    // Prevent double opening
    if (!modal.classList.contains('hidden') && modal.style.display !== 'none') return;
    
    modal.classList.remove('hidden');
    modal.style.setProperty('display', 'flex', 'important');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeFaqModal() {
    const modal = document.getElementById('faqModal');
    if (!modal) return;
    
    modal.style.setProperty('display', 'none', 'important');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    
    // Reset form only if not submitting
    const form = document.getElementById('faqForm');
    if (form && !form.dataset.submitting) {
        form.reset();
        document.getElementById('faq_id').value = '';
        const actionInput = form.querySelector('input[name="action"]');
        if (actionInput) actionInput.value = 'create';
    }
}

// Close modal when clicking outside (ensure only added once)
(function() {
    const modal = document.getElementById('faqModal');
    if (modal) {
        // Remove existing listeners by cloning
        const newModal = modal.cloneNode(true);
        modal.parentNode.replaceChild(newModal, modal);
        
        document.getElementById('faqModal').addEventListener('click', function(e) {
            if (e.target === this) closeFaqModal();
        });
        
        // Prevent form submission from closing modal prematurely
        const form = document.getElementById('faqForm');
        if (form) {
            // Mark form to skip global handler
            form.setAttribute('data-has-confirmation', 'true');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.innerHTML : '';
                
                const isUpdate = form.querySelector('input[name="action"]').value === 'update';
                const actionText = isUpdate ? 'update' : 'save';
                const actionTextCapital = isUpdate ? 'Update' : 'Save';
                
                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to ${actionText} this FAQ?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, ${actionText} it!`,
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.dataset.submitting = 'true';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                        }
                        Swal.fire({
                            title: `${actionTextCapital}ing FAQ...`,
                            text: `Please wait while we ${actionText} your FAQ`,
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
    }
})();
</script>

<script>
function loadFAQ(id, question, answer) {
    document.getElementById('faq_id').value = id;
    document.getElementById('question').value = question;
    document.getElementById('answer').value = answer;
    document.querySelector('#faqForm input[name="action"]').value = 'update';
}

// Reset form when modal closes (handled in closeFaqModal function)
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
function deleteFAQ(faqId, faqQuestion) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete FAQ "${faqQuestion}..."? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            
            const faqIdInput = document.createElement('input');
            faqIdInput.type = 'hidden';
            faqIdInput.name = 'faq_id';
            faqIdInput.value = faqId;
            form.appendChild(faqIdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
/* Mobile-specific improvements for FAQ page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    textarea,
    select {
        font-size: 16px !important;
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

/* Line clamp utility for answer truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
