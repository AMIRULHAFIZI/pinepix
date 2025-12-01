<?php
/**
 * Cron job to update pineapple prices
 * Run this script every hour or as needed
 * Example cron: 0 * * * * php /path/to/cron/update-prices.php
 */

require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../helpers/PriceScraper.php';

// Fetch new price data
$priceData = PriceScraper::fetchPineapplePrice();

if ($priceData) {
    // Store in database
    $stored = PriceScraper::storeInDatabase($priceData);
    
    if ($stored) {
        echo "Price updated successfully: RM" . $priceData['price'] . "\n";
        echo "Week: " . $priceData['week'] . ", Year: " . $priceData['year'] . "\n";
    } else {
        echo "Failed to store price data in database\n";
    }
} else {
    echo "Failed to fetch price data\n";
}

