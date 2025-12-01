<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    Helper::redirect(BASE_URL . 'dashboard.php');
}

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

// Validate token on page load
if (!empty($token) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $error = 'Invalid or expired reset token. Please request a new password reset link.';
        $token = ''; // Clear invalid token
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        $result = $auth->resetPassword($token, $password);
        
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

include VIEWS_PATH . 'auth/reset-password.php';
