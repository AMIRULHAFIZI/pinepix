<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    Helper::redirect(BASE_URL . 'dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Helper::sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        Helper::redirect(BASE_URL . 'dashboard.php');
    } else {
        $error = $result['message'];
    }
}

include VIEWS_PATH . 'auth/login.php';
