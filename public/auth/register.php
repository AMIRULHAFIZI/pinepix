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
    $data = [
        'name' => Helper::sanitize($_POST['name'] ?? ''),
        'email' => Helper::sanitize($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'phone' => Helper::sanitize($_POST['phone'] ?? ''),
        'address' => Helper::sanitize($_POST['address'] ?? ''),
        'gender' => Helper::sanitize($_POST['gender'] ?? ''),
        'ic_passport' => Helper::sanitize($_POST['ic_passport'] ?? ''),
        'business_category' => Helper::sanitize($_POST['business_category'] ?? ''),
    ];
    
    $result = $auth->register($data);
    
    if ($result['success']) {
        Helper::redirect(BASE_URL . 'dashboard.php');
    } else {
        $error = $result['message'];
    }
}

include VIEWS_PATH . 'auth/register.php';
