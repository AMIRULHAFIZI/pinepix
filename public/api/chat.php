<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

header('Content-Type: application/json');

$auth = new Auth();
$db = Database::getInstance()->getConnection();

// Allow guests limited access
$isGuest = !$auth->isLoggedIn();
$userId = $isGuest ? null : $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Helper::jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';
$mode = $input['mode'] ?? 'faq';

if (empty($message)) {
    Helper::jsonResponse(['error' => 'Message is required'], 400);
}

$response = '';
$responseText = '';

if ($mode === 'faq') {
    // Search FAQ knowledge base
    $stmt = $db->prepare("SELECT * FROM faq_knowledge WHERE question LIKE ? OR answer LIKE ? LIMIT 5");
    $searchTerm = "%{$message}%";
    $stmt->execute([$searchTerm, $searchTerm]);
    $faqs = $stmt->fetchAll();
    
    if (!empty($faqs)) {
        $responseText = "Based on our knowledge base:\n\n";
        foreach ($faqs as $faq) {
            $responseText .= "Q: {$faq['question']}\nA: {$faq['answer']}\n\n";
        }
    } else {
        $responseText = "I couldn't find a matching answer in our FAQ. Please try rephrasing your question or contact support.";
    }
    
    $response = $responseText;
} elseif ($mode === 'ai') {
    // Use Gemini API
    $geminiKey = Helper::getSetting('gemini_api_key');
    
    if (empty($geminiKey)) {
        Helper::jsonResponse(['error' => 'Gemini API key not configured'], 500);
    }
    
    // Prepare context from FAQ
    $stmt = $db->query("SELECT question, answer FROM faq_knowledge LIMIT 10");
    $faqs = $stmt->fetchAll();
    $context = "You are a helpful assistant for the PinePix - Pineapple Entrepreneur Information Management System. ";
    $context .= "Here is some context from our FAQ:\n\n";
    foreach ($faqs as $faq) {
        $context .= "Q: {$faq['question']}\nA: {$faq['answer']}\n\n";
    }
    $context .= "\nUser question: {$message}\n\nPlease provide a helpful answer.";
    
    // Call Gemini API
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$geminiKey}";
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $context]
                ]
            ]
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $result = json_decode($apiResponse, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $response = $result['candidates'][0]['content']['parts'][0]['text'];
            $responseText = $response;
        } else {
            $response = "I apologize, but I couldn't generate a response. Please try again.";
            $responseText = $response;
        }
    } else {
        $error = json_decode($apiResponse, true);
        $response = "Error connecting to AI service. Please try again later.";
        $responseText = $response;
    }
}

// Save to chat logs
$stmt = $db->prepare("INSERT INTO chat_logs (user_id, message, response, mode) VALUES (?, ?, ?, ?)");
$stmt->execute([$userId, $message, $responseText, $mode]);

Helper::jsonResponse([
    'success' => true,
    'response' => $response,
    'mode' => $mode
]);
