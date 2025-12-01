<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../helpers/PriceScraper.php';

// Allow public access for price data
$action = $_GET['action'] ?? 'get';

if ($action === 'fetch') {
    // Force fetch new data
    $priceData = PriceScraper::fetchPineapplePrice();
    
    if ($priceData) {
        // Store in database
        PriceScraper::storeInDatabase($priceData);
        Helper::jsonResponse([
            'success' => true,
            'data' => $priceData,
            'message' => 'Price data fetched successfully'
        ]);
    } else {
        Helper::jsonResponse([
            'success' => false,
            'error' => 'Failed to fetch price data'
        ], 500);
    }
} else {
    // Get cached or latest data
    $priceData = PriceScraper::getPriceData();
    
    if (!$priceData) {
        // Try database
        $dbPrice = PriceScraper::getLatestPriceFromDB();
        if ($dbPrice) {
            $priceData = [
                'price' => floatval($dbPrice['price']),
                'unit' => $dbPrice['unit'],
                'week' => $dbPrice['week'],
                'year' => $dbPrice['year'],
                'update_date' => $dbPrice['update_date'],
                'source' => $dbPrice['source'],
                'data_sources' => json_decode($dbPrice['data_sources'], true) ?: [],
                'last_updated' => $dbPrice['created_at'],
                'state_averages' => isset($dbPrice['state_averages_data']) ? $dbPrice['state_averages_data'] : (json_decode($dbPrice['state_averages'] ?? '[]', true) ?: []),
                'state_lowest' => isset($dbPrice['state_lowest_data']) ? $dbPrice['state_lowest_data'] : (json_decode($dbPrice['state_lowest'] ?? '[]', true) ?: [])
            ];
        }
    }
    
    if ($priceData) {
        Helper::jsonResponse([
            'success' => true,
            'data' => $priceData
        ]);
    } else {
        Helper::jsonResponse([
            'success' => false,
            'error' => 'No price data available'
        ], 404);
    }
}

