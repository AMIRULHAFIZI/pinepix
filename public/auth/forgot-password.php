<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    Helper::redirect(BASE_URL . 'dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Helper::sanitize($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Please enter your email address';
    } else {
        $result = $auth->forgotPassword($email);
        
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

include VIEWS_PATH . 'auth/forgot-password.php';
