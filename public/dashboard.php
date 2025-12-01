<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance()->getConnection();
$user = $auth->getUser();
$currentPage = 'dashboard';

// Get statistics
$stats = [];

// Total farms
$stmt = $db->prepare("SELECT COUNT(*) as count FROM farms WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$stats['farms'] = $stmt->fetch()['count'];

// Total shops
$stmt = $db->prepare("SELECT COUNT(*) as count FROM shops WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$stats['shops'] = $stmt->fetch()['count'];

// Total announcements
if ($auth->isAdmin()) {
    $stmt = $db->query("SELECT COUNT(*) as count FROM announcements");
} else {
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM announcements WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
$stats['announcements'] = $stmt->fetch()['count'];

// Recent announcements
if ($auth->isAdmin()) {
    $stmt = $db->query("SELECT a.*, u.name as created_by_name FROM announcements a 
                        LEFT JOIN users u ON a.created_by = u.id 
                        ORDER BY a.created_at DESC LIMIT 5");
} else {
    $stmt = $db->prepare("SELECT a.*, u.name as created_by_name FROM announcements a 
                          LEFT JOIN users u ON a.created_by = u.id 
                          WHERE a.created_by = ? 
                          ORDER BY a.created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
}
$recentAnnouncements = $stmt->fetchAll();

// Chart Data - Announcements by Type
if ($auth->isAdmin()) {
    $stmt = $db->query("SELECT type, COUNT(*) as count FROM announcements GROUP BY type");
} else {
    $stmt = $db->prepare("SELECT type, COUNT(*) as count FROM announcements WHERE created_by = ? GROUP BY type");
    $stmt->execute([$_SESSION['user_id']]);
}
$typeData = $stmt->fetchAll();
$announcementsByType = [];
foreach ($typeData as $row) {
    $announcementsByType[$row['type']] = (int)$row['count'];
}

// Chart Data - Price Trend (Last 12 months)
if ($auth->isAdmin()) {
    $stmt = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                        FROM announcements 
                        WHERE type = 'price' 
                        AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                        GROUP BY month 
                        ORDER BY month ASC");
} else {
    $stmt = $db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                          FROM announcements 
                          WHERE type = 'price' 
                          AND created_by = ?
                          AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                          GROUP BY month 
                          ORDER BY month ASC");
    $stmt->execute([$_SESSION['user_id']]);
}
$priceTrend = $stmt->fetchAll();

// Chart Data - Activity Over Time (Last 6 months)
if ($auth->isAdmin()) {
    $stmt = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                        FROM announcements 
                        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                        GROUP BY month 
                        ORDER BY month ASC");
} else {
    $stmt = $db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                          FROM announcements 
                          WHERE created_by = ?
                          AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                          GROUP BY month 
                          ORDER BY month ASC");
    $stmt->execute([$_SESSION['user_id']]);
}
$activityTrend = $stmt->fetchAll();

$pageTitle = 'Dashboard';
include VIEWS_PATH . 'partials/header.php';
include VIEWS_PATH . 'dashboard.php';
