<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

header('Content-Type: application/json');

$auth = new Auth();
$db = Database::getInstance()->getConnection();

if (!$auth->isLoggedIn()) {
    Helper::jsonResponse([
        'success' => true,
        'history' => []
    ]);
}

// Get chat history for logged in users
$stmt = $db->prepare("SELECT * FROM chat_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$_SESSION['user_id']]);
$chatHistory = array_reverse($stmt->fetchAll());

Helper::jsonResponse([
    'success' => true,
    'history' => $chatHistory
]);

