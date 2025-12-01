<?php
$pageTitle = 'Forgot Password';
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
                    <p class="text-gray-600 text-lg">Reset your password</p>
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
                    <div class="mb-4 p-4 bg-green-50 border-2 border-green-200 rounded-lg text-green-800 flex items-center justify-between">
                        <span><i class="fas fa-check-circle mr-2"></i><?= $success ?></span>
                        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" id="email" name="email" required autofocus
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">We'll send you a reset link</p>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-400 text-white py-3 rounded-lg font-semibold hover:bg-primary-500 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
                    </button>
                    
                    <div class="text-center">
                        <a href="<?= BASE_URL ?>auth/login.php" class="text-sm text-primary-400 hover:text-primary-500 inline-flex items-center">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
