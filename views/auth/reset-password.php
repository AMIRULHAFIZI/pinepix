<?php
$pageTitle = 'Reset Password';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 p-4 py-16">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <img src="<?= BASE_URL ?>assets/images/logoblack.png" alt="PinePix Logo" class="h-24 object-contain">
                    </div>
                    <p class="text-gray-600 text-lg">Set new password</p>
                </div>
                
                <?php if (isset($error) && $error): ?>
                    <div class="mb-4 p-4 bg-red-50 border-2 border-red-200 rounded-lg text-red-800 flex items-center justify-between">
                        <span><i class="fas fa-exclamation-circle mr-2"></i><?= $error ?></span>
                        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success) && $success): ?>
                    <div class="mb-4 p-4 bg-green-50 border-2 border-green-200 rounded-lg text-green-800">
                        <i class="fas fa-check-circle mr-2"></i><?= $success ?>
                    </div>
                    <div class="text-center">
                        <a href="<?= BASE_URL ?>auth/login.php" class="inline-block bg-primary-400 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-500 transition shadow-lg hover:shadow-xl">
                            Go to Login
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="" class="space-y-4">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input type="password" id="password" name="password" required minlength="6"
                                       class="block w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition">
                                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password-toggle-icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                                       class="block w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition">
                                <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="confirm_password-toggle-icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-primary-400 text-white py-3 rounded-lg font-semibold hover:bg-primary-500 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </button>
                        
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>auth/login.php" class="text-sm text-primary-400 hover:text-primary-500 inline-flex items-center">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Login
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-toggle-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
