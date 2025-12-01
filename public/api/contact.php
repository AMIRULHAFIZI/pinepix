<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

header('Content-Type: application/json');

// Allow POST requests only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Helper::jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$subject = trim($input['subject'] ?? '');
$message = trim($input['message'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

if (!empty($errors)) {
    Helper::jsonResponse(['success' => false, 'error' => implode(', ', $errors)], 400);
}

// Sanitize inputs
$name = Helper::sanitize($name);
$email = Helper::sanitize($email);
$subject = Helper::sanitize($subject);
$message = Helper::sanitize($message);

// Send email
require_once __DIR__ . '/../../helpers/Mail.php';

try {
    $emailSent = Mail::sendContactForm($name, $email, $subject, $message);
    
    if ($emailSent) {
        Helper::jsonResponse([
            'success' => true,
            'message' => 'Thank you for contacting us. We will get back to you soon.'
        ]);
    } else {
        error_log("Failed to send contact form email from: $email");
        Helper::jsonResponse([
            'success' => false,
            'error' => 'Failed to send email. Please try again later or contact us directly.'
        ], 500);
    }
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    Helper::jsonResponse([
        'success' => false,
        'error' => 'An error occurred. Please try again later.'
    ], 500);
}

