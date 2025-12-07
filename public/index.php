<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

// Public landing page
$auth = new Auth();
$db = Database::getInstance()->getConnection();

// Get latest announcements
$stmt = $db->query("SELECT a.*, u.name as created_by_name FROM announcements a 
                    LEFT JOIN users u ON a.created_by = u.id 
                    ORDER BY a.created_at DESC LIMIT 6");
$announcements = $stmt->fetchAll();

// Get all farms for map
$stmt = $db->query("SELECT f.*, u.name as farmer_name FROM farms f 
                    JOIN users u ON f.user_id = u.id 
                    WHERE f.latitude IS NOT NULL AND f.longitude IS NOT NULL");
$farms = $stmt->fetchAll();

// Get all shops for map
$stmt = $db->query("SELECT s.*, u.name as shop_owner_name FROM shops s 
                    JOIN users u ON s.user_id = u.id 
                    WHERE s.latitude IS NOT NULL AND s.longitude IS NOT NULL");
$shops = $stmt->fetchAll();

// Get statistics for landing page
// Total entrepreneurs
$stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'entrepreneur'");
$totalEntrepreneurs = $stmt->fetch()['count'];

// Total locations (count farms and shops with valid coordinates)
$stmt = $db->query("SELECT COUNT(DISTINCT CONCAT(latitude, ',', longitude)) as count 
                    FROM (
                        SELECT latitude, longitude FROM farms WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                        UNION
                        SELECT latitude, longitude FROM shops WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                    ) as locations");
$totalLocations = $stmt->fetch()['count'] ?: 0;

// Total FAQ entries
$stmt = $db->query("SELECT COUNT(*) as count FROM faq_knowledge");
$totalFAQ = $stmt->fetch()['count'];

// Get latest price announcement (if any)
$stmt = $db->query("SELECT * FROM announcements WHERE type = 'price' ORDER BY created_at DESC LIMIT 1");
$latestPrice = $stmt->fetch();

// Get current pineapple price (from scraper or database)
require_once __DIR__ . '/../helpers/PriceScraper.php';
$currentPriceData = PriceScraper::getPriceData();

// If no cached data, try database
if (!$currentPriceData) {
    $dbPrice = PriceScraper::getLatestPriceFromDB();
    if ($dbPrice) {
        $currentPriceData = [
            'price' => floatval($dbPrice['price']),
            'unit' => $dbPrice['unit'],
            'week' => $dbPrice['week'],
            'year' => $dbPrice['year'],
            'update_date' => $dbPrice['update_date'],
            'source' => $dbPrice['source'],
            'data_sources' => json_decode($dbPrice['data_sources'], true) ?: ['PriceCatcher KPDN', 'Open DOSM'],
            'last_updated' => $dbPrice['created_at'],
            'state_averages' => isset($dbPrice['state_averages_data']) ? $dbPrice['state_averages_data'] : (json_decode($dbPrice['state_averages'] ?? '[]', true) ?: []),
            'state_lowest' => isset($dbPrice['state_lowest_data']) ? $dbPrice['state_lowest_data'] : (json_decode($dbPrice['state_lowest'] ?? '[]', true) ?: [])
        ];
    }
}

// Default values if no data available
if (!$currentPriceData) {
    $currentPriceData = [
        'price' => 4.62,
        'unit' => 'per piece',
        'week' => 48,
        'year' => 2025,
        'update_date' => '30 November 2025',
        'source' => 'ManaMurah.com',
        'data_sources' => ['PriceCatcher KPDN', 'Open DOSM'],
        'last_updated' => date('Y-m-d H:i:s')
    ];
}

include VIEWS_PATH . 'public/index.php';
