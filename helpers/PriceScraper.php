<?php
class PriceScraper {
    private static $cacheFile = __DIR__ . '/../cache/pineapple_price.json';
    private static $cacheDuration = 3600; // 1 hour cache
    
    /**
     * Malaysian states with their URL slugs and IDs
     */
    private static $stateUrls = [
        'Johor' => ['slug' => 'johor-1', 'id' => 1],
        'Kedah' => ['slug' => 'kedah-2', 'id' => 2],
        'Kelantan' => ['slug' => 'kelantan-3', 'id' => 3],
        'Melaka' => ['slug' => 'melaka-4', 'id' => 4],
        'Negeri Sembilan' => ['slug' => 'negeri_sembilan-5', 'id' => 5],
        'Pahang' => ['slug' => 'pahang-6', 'id' => 6],
        'Penang' => ['slug' => 'pulau_pinang-7', 'id' => 7],
        'Perak' => ['slug' => 'perak-8', 'id' => 8],
        'Perlis' => ['slug' => 'perlis-9', 'id' => 9],
        'Selangor' => ['slug' => 'selangor-10', 'id' => 10],
        'Terengganu' => ['slug' => 'terengganu-11', 'id' => 11],
        'Sabah' => ['slug' => 'sabah-12', 'id' => 12],
        'Sarawak' => ['slug' => 'sarawak-13', 'id' => 13],
        'Kuala Lumpur' => ['slug' => 'kuala_lumpur-14', 'id' => 14],
        'Labuan' => ['slug' => 'labuan-15', 'id' => 15],
        'Putrajaya' => ['slug' => 'putrajaya-16', 'id' => 16],
    ];
    
    /**
     * Fetch HTML from URL
     * @param string $url
     * @return string|false
     */
    private static function fetchHtml($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || empty($html)) {
            return false;
        }
        
        return $html;
    }
    
    /**
     * Fetch pineapple price from ManaMurah.com (national average)
     * @return array|false
     */
    public static function fetchPineapplePrice() {
        $url = 'https://manamurah.com/barang/nenas_biasa_josapine-25';
        $html = self::fetchHtml($url);
        
        if (!$html) {
            return false;
        }
        
        // Parse HTML to extract price data
        $priceData = self::parsePriceData($html);
        
        if ($priceData) {
            // Fetch state-specific data
            $priceData['state_averages'] = self::fetchStateAverages();
            $priceData['state_lowest'] = self::fetchStateLowestPrices();
            
            // Save to cache
            self::saveCache($priceData);
            return $priceData;
        }
        
        return false;
    }
    
    /**
     * Fetch average prices for all states
     * @return array
     */
    private static function fetchStateAverages() {
        $stateAverages = [];
        
        foreach (self::$stateUrls as $stateName => $stateInfo) {
            $url = 'https://manamurah.com/barang/nenas_biasa_josapine-25/negeri/' . $stateInfo['slug'];
            $html = self::fetchHtml($url);
            
            if ($html) {
                $stateData = self::parseStatePage($html, $stateName);
                if ($stateData && isset($stateData['average_price'])) {
                    $stateAverages[] = $stateData;
                }
            }
            
            // Small delay to avoid overwhelming the server
            usleep(500000); // 0.5 second delay
        }
        
        return $stateAverages;
    }
    
    /**
     * Fetch lowest prices for all states
     * @return array
     */
    private static function fetchStateLowestPrices() {
        $stateLowest = [];
        
        foreach (self::$stateUrls as $stateName => $stateInfo) {
            $url = 'https://manamurah.com/barang/nenas_biasa_josapine-25/negeri/' . $stateInfo['slug'];
            $html = self::fetchHtml($url);
            
            if ($html) {
                $lowestData = self::parseStateLowestPrice($html, $stateName);
                if ($lowestData) {
                    $stateLowest[] = $lowestData;
                }
            }
            
            // Small delay to avoid overwhelming the server
            usleep(500000); // 0.5 second delay
        }
        
        return $stateLowest;
    }
    
    /**
     * Parse state-specific page for average price
     * @param string $html
     * @param string $stateName
     * @return array|false
     */
    private static function parseStatePage($html, $stateName) {
        $data = [
            'state' => $stateName,
            'average_price' => null,
            'price_change' => null,
            'percent_change' => null
        ];
        
        // Use DOMDocument for better parsing
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        
        // Look for the main price heading (usually in h1 or h2 with PriceCatcher class)
        $priceHeadings = $xpath->query("//h1[contains(@class, 'PriceCatcher')] | //h2[contains(@class, 'PriceCatcher')] | //h1 | //h2");
        
        foreach ($priceHeadings as $heading) {
            $text = trim($heading->textContent);
            // Look for price pattern in heading
            if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $text, $matches)) {
                $price = floatval(str_replace(',', '', $matches[1]));
                // This is likely the state average price
                $data['average_price'] = $price;
                break;
            }
        }
        
        // If not found in headings, try to find price in the main content area
        if ($data['average_price'] === null) {
            // Look for price near "Harga purata" text
            $priceSections = $xpath->query("//*[contains(text(), 'Harga purata') or contains(text(), 'harga purata')]");
            foreach ($priceSections as $section) {
                $parent = $section->parentNode;
                if ($parent) {
                    $text = $parent->textContent;
                    if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $text, $matches)) {
                        $data['average_price'] = floatval(str_replace(',', '', $matches[1]));
                        break;
                    }
                }
            }
        }
        
        // Fallback: find first RM price in the page
        if ($data['average_price'] === null) {
            if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $html, $matches)) {
                $data['average_price'] = floatval(str_replace(',', '', $matches[1]));
            }
        }
        
        // Look for price change indicators (usually shown as +/- with RM or %)
        if (preg_match('/([+\-]?\d+\.?\d*)\s*RM/i', $html, $changeMatch)) {
            $data['price_change'] = trim($changeMatch[1]) . ' RM';
        }
        
        // Look for percentage change
        if (preg_match('/([+\-]?\d+\.?\d*%)/i', $html, $percentMatch)) {
            $data['percent_change'] = trim($percentMatch[1]);
        }
        
        if ($data['average_price'] !== null) {
            return $data;
        }
        
        return false;
    }
    
    /**
     * Parse state-specific page for lowest price
     * @param string $html
     * @param string $stateName
     * @return array|false
     */
    private static function parseStateLowestPrice($html, $stateName) {
        // Use DOMDocument to find lowest price table or listing
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        
        $lowestPrice = null;
        $shopName = null;
        $date = null;
        
        // Look for tables containing price listings
        $tables = $xpath->query("//table");
        
        foreach ($tables as $table) {
            $tableText = $table->textContent;
            
            // Check if this table contains shop/price data
            if (stripos($tableText, 'Kedai') !== false || 
                stripos($tableText, 'Shop') !== false ||
                stripos($tableText, 'Harga') !== false) {
                
                $rows = $xpath->query(".//tr", $table);
                
                $prices = [];
                foreach ($rows as $row) {
                    $cells = $xpath->query(".//td | .//th", $row);
                    $rowData = [];
                    
                    foreach ($cells as $cell) {
                        $rowData[] = trim($cell->textContent);
                    }
                    
                    if (count($rowData) >= 2) {
                        $price = null;
                        $shop = null;
                        $rowDate = null;
                        
                        foreach ($rowData as $cellData) {
                            // Find price
                            if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $cellData, $priceMatch)) {
                                $price = floatval(str_replace(',', '', $priceMatch[1]));
                            }
                            
                            // Find date
                            if (preg_match('/(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/', $cellData, $dateMatch)) {
                                $rowDate = trim($dateMatch[1]);
                            }
                            
                            // Find shop name (text that's not price, date, or state)
                            if (!preg_match('/RM|\d{1,2}[\/\-]|' . preg_quote($stateName, '/') . '/i', $cellData) &&
                                strlen($cellData) > 3 && strlen($cellData) < 100) {
                                $shop = $cellData;
                            }
                        }
                        
                        if ($price) {
                            $prices[] = [
                                'price' => $price,
                                'shop' => $shop,
                                'date' => $rowDate
                            ];
                        }
                    }
                }
                
                if (!empty($prices)) {
                    // Find the lowest price
                    usort($prices, function($a, $b) {
                        return $a['price'] <=> $b['price'];
                    });
                    
                    $lowest = $prices[0];
                    $lowestPrice = $lowest['price'];
                    $shopName = $lowest['shop'];
                    $date = $lowest['date'];
                    break;
                }
            }
        }
        
        // Fallback: Find all prices and get the lowest
        if ($lowestPrice === null) {
            $priceElements = $xpath->query("//*[contains(text(), 'RM')]");
            
            $prices = [];
            foreach ($priceElements as $element) {
                $text = trim($element->textContent);
                if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $text, $matches)) {
                    $price = floatval(str_replace(',', '', $matches[1]));
                    $prices[] = [
                        'price' => $price,
                        'element' => $element
                    ];
                }
            }
            
            if (!empty($prices)) {
                usort($prices, function($a, $b) {
                    return $a['price'] <=> $b['price'];
                });
                
                $lowest = $prices[0];
                $lowestPrice = $lowest['price'];
                
                // Try to find shop name and date near the lowest price
                $parent = $lowest['element']->parentNode;
                if ($parent) {
                    $parentText = $parent->textContent;
                    
                    // Look for date pattern
                    if (preg_match('/(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/', $parentText, $dateMatch)) {
                        $date = trim($dateMatch[1]);
                    }
                    
                    // Look for shop name
                    $words = preg_split('/\s+/', $parentText);
                    foreach ($words as $word) {
                        $word = trim($word);
                        if (strlen($word) > 3 && strlen($word) < 50 && 
                            !preg_match('/RM|\d|' . preg_quote($stateName, '/') . '/i', $word)) {
                            $shopName = $word;
                            break;
                        }
                    }
                }
            }
        }
        
        if ($lowestPrice) {
            return [
                'state' => $stateName,
                'shop' => $shopName ?: 'Unknown',
                'date' => $date,
                'price' => $lowestPrice
            ];
        }
        
        return false;
    }
    
    /**
     * Parse price data from HTML
     * @param string $html
     * @return array|false
     */
    private static function parsePriceData($html) {
        $data = [
            'price' => null,
            'unit' => 'per piece',
            'week' => null,
            'year' => null,
            'update_date' => null,
            'source' => 'ManaMurah.com',
            'data_sources' => ['PriceCatcher KPDN', 'Open DOSM'],
            'last_updated' => date('Y-m-d H:i:s'),
            'state_averages' => [],
            'state_lowest' => []
        ];
        
        // Extract main price using regex patterns
        // Look for price pattern: RM4.62 or similar
        if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $html, $matches)) {
            $data['price'] = floatval(str_replace(',', '', $matches[1]));
        }
        
        // Extract week and year
        if (preg_match('/minggu\s*ke-(\d+)\s*tahun\s*(\d+)/i', $html, $matches)) {
            $data['week'] = intval($matches[1]);
            $data['year'] = intval($matches[2]);
        }
        
        // Extract update date
        if (preg_match('/Tarikh\s*Kemaskini[:\s]*([^<]+)/i', $html, $matches)) {
            $data['update_date'] = trim($matches[1]);
        }
        
        // Parse state average prices table
        $data['state_averages'] = self::parseStateAverages($html);
        
        // Parse state lowest prices table
        $data['state_lowest'] = self::parseStateLowest($html);
        
        // If we got at least the price, return data
        if ($data['price'] !== null) {
            return $data;
        }
        
        return false;
    }
    
    /**
     * Parse state average prices table
     * @param string $html
     * @return array
     */
    private static function parseStateAverages($html) {
        $stateAverages = [];
        
        // Use DOMDocument for better HTML parsing
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        
        // Look for table containing "Harga purata mengikut negeri" or similar
        // Find all tables
        $tables = $xpath->query("//table");
        
        foreach ($tables as $table) {
            $tableText = $table->textContent;
            
            // Check if this table contains state average price data
            if (stripos($tableText, 'Harga purata') !== false || 
                stripos($tableText, 'Negeri') !== false ||
                stripos($tableText, 'Perubahan') !== false) {
                
                // Get all rows
                $rows = $xpath->query(".//tr", $table);
                
                foreach ($rows as $row) {
                    $cells = $xpath->query(".//td | .//th", $row);
                    $rowData = [];
                    
                    foreach ($cells as $cell) {
                        $rowData[] = trim($cell->textContent);
                    }
                    
                    // Check if row has state name and price
                    if (count($rowData) >= 2) {
                        $state = null;
                        $price = null;
                        $priceChange = null;
                        $percentChange = null;
                        
                        // Find state name (usually first column)
                        $malaysianStates = [
                            'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
                            'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak',
                            'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'
                        ];
                        
                        foreach ($rowData as $cellData) {
                            // Check if cell contains a state name
                            foreach ($malaysianStates as $ms) {
                                if (stripos($cellData, $ms) !== false) {
                                    $state = $ms;
                                    break 2;
                                }
                            }
                        }
                        
                        // Find price (look for RM pattern)
                        foreach ($rowData as $cellData) {
                            if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $cellData, $priceMatch)) {
                                if ($price === null) {
                                    $price = floatval(str_replace(',', '', $priceMatch[1]));
                                } elseif ($priceChange === null) {
                                    // Second price might be price change
                                    $priceChange = trim($cellData);
                                }
                            }
                            
                            // Look for percentage change
                            if (preg_match('/([+\-]?\d+\.?\d*%)/i', $cellData, $percentMatch)) {
                                $percentChange = trim($percentMatch[1]);
                            }
                        }
                        
                        if ($state && $price) {
                            $stateAverages[] = [
                                'state' => $state,
                                'average_price' => $price,
                                'price_change' => $priceChange,
                                'percent_change' => $percentChange
                            ];
                        }
                    }
                }
                
                // If we found data, break
                if (!empty($stateAverages)) {
                    break;
                }
            }
        }
        
        // Fallback: Try regex if DOM parsing fails
        if (empty($stateAverages)) {
            $malaysianStates = [
                'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
                'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak',
                'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'
            ];
            
            foreach ($malaysianStates as $state) {
                // Look for state followed by price
                $pattern = '/' . preg_quote($state, '/') . '[^<]*?RM\s*([\d,]+\.?\d*)/i';
                if (preg_match($pattern, $html, $matches)) {
                    $stateAverages[] = [
                        'state' => $state,
                        'average_price' => floatval(str_replace(',', '', $matches[1])),
                        'price_change' => null,
                        'percent_change' => null
                    ];
                }
            }
        }
        
        return $stateAverages;
    }
    
    /**
     * Parse state lowest prices table
     * @param string $html
     * @return array
     */
    private static function parseStateLowest($html) {
        $stateLowest = [];
        
        // Use DOMDocument for better HTML parsing
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($dom);
        
        // Look for table containing "Harga paling rendah" or similar
        $tables = $xpath->query("//table");
        
        foreach ($tables as $table) {
            $tableText = $table->textContent;
            
            // Check if this table contains lowest price data
            if (stripos($tableText, 'Harga paling rendah') !== false || 
                stripos($tableText, 'paling rendah') !== false ||
                stripos($tableText, 'Kedai') !== false) {
                
                $rows = $xpath->query(".//tr", $table);
                
                foreach ($rows as $row) {
                    $cells = $xpath->query(".//td | .//th", $row);
                    $rowData = [];
                    
                    foreach ($cells as $cell) {
                        $rowData[] = trim($cell->textContent);
                    }
                    
                    if (count($rowData) >= 2) {
                        $state = null;
                        $shop = null;
                        $date = null;
                        $price = null;
                        
                        $malaysianStates = [
                            'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
                            'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak',
                            'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'
                        ];
                        
                        foreach ($rowData as $idx => $cellData) {
                            // Find state
                            foreach ($malaysianStates as $ms) {
                                if (stripos($cellData, $ms) !== false) {
                                    $state = $ms;
                                    break;
                                }
                            }
                            
                            // Find price
                            if (preg_match('/RM\s*([\d,]+\.?\d*)/i', $cellData, $priceMatch)) {
                                $price = floatval(str_replace(',', '', $priceMatch[1]));
                            }
                            
                            // Find date (format: DD/MM/YYYY or DD-MM-YYYY)
                            if (preg_match('/(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/', $cellData, $dateMatch)) {
                                $date = trim($dateMatch[1]);
                            }
                            
                            // Shop name is usually text that's not state, price, or date
                            if ($state && !$shop && 
                                stripos($cellData, $state) === false && 
                                !preg_match('/RM|price|\d{1,2}[\/\-]\d{1,2}/i', $cellData) &&
                                strlen($cellData) > 2) {
                                $shop = $cellData;
                            }
                        }
                        
                        if ($state && $price) {
                            $stateLowest[] = [
                                'state' => $state,
                                'shop' => $shop ?: 'Unknown',
                                'date' => $date,
                                'price' => $price
                            ];
                        }
                    }
                }
                
                if (!empty($stateLowest)) {
                    break;
                }
            }
        }
        
        return $stateLowest;
    }
    
    /**
     * Get cached price data
     * @return array|false
     */
    public static function getCachedPrice() {
        $cacheDir = dirname(self::$cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        if (!file_exists(self::$cacheFile)) {
            return false;
        }
        
        $cacheTime = filemtime(self::$cacheFile);
        if (time() - $cacheTime > self::$cacheDuration) {
            return false; // Cache expired
        }
        
        $data = json_decode(file_get_contents(self::$cacheFile), true);
        return $data ?: false;
    }
    
    /**
     * Save price data to cache
     * @param array $data
     */
    private static function saveCache($data) {
        $cacheDir = dirname(self::$cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        file_put_contents(self::$cacheFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    /**
     * Get price data (from cache or fetch new)
     * @return array|false
     */
    public static function getPriceData() {
        // Try cache first
        $cached = self::getCachedPrice();
        if ($cached !== false) {
            return $cached;
        }
        
        // Fetch new data
        return self::fetchPineapplePrice();
    }
    
    /**
     * Store price in database
     * @param array $priceData
     * @return bool
     */
    public static function storeInDatabase($priceData) {
        $db = Database::getInstance()->getConnection();
        
        try {
            // Create main price table
            $db->exec("CREATE TABLE IF NOT EXISTS pineapple_prices (
                id INT AUTO_INCREMENT PRIMARY KEY,
                price DECIMAL(10,2) NOT NULL,
                unit VARCHAR(50) DEFAULT 'per piece',
                week INT,
                year INT,
                update_date VARCHAR(255),
                source VARCHAR(255),
                data_sources TEXT,
                state_averages TEXT,
                state_lowest TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_created_at (created_at)
            )");
            
            // Create state averages table
            $db->exec("CREATE TABLE IF NOT EXISTS pineapple_state_averages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                price_id INT,
                state VARCHAR(100) NOT NULL,
                average_price DECIMAL(10,2) NOT NULL,
                price_change VARCHAR(50),
                percent_change VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (price_id) REFERENCES pineapple_prices(id) ON DELETE CASCADE,
                INDEX idx_state (state),
                INDEX idx_price_id (price_id)
            )");
            
            // Create state lowest prices table
            $db->exec("CREATE TABLE IF NOT EXISTS pineapple_state_lowest (
                id INT AUTO_INCREMENT PRIMARY KEY,
                price_id INT,
                state VARCHAR(100) NOT NULL,
                shop VARCHAR(255),
                date VARCHAR(50),
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (price_id) REFERENCES pineapple_prices(id) ON DELETE CASCADE,
                INDEX idx_state (state),
                INDEX idx_price_id (price_id)
            )");
            
            // Insert main price record
            $stmt = $db->prepare("INSERT INTO pineapple_prices (price, unit, week, year, update_date, source, data_sources, state_averages, state_lowest) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $dataSources = json_encode($priceData['data_sources'] ?? []);
            $stateAverages = json_encode($priceData['state_averages'] ?? []);
            $stateLowest = json_encode($priceData['state_lowest'] ?? []);
            
            $stmt->execute([
                $priceData['price'],
                $priceData['unit'] ?? 'per piece',
                $priceData['week'],
                $priceData['year'],
                $priceData['update_date'],
                $priceData['source'] ?? 'ManaMurah.com',
                $dataSources,
                $stateAverages,
                $stateLowest
            ]);
            
            $priceId = $db->lastInsertId();
            
            // Insert state averages
            if (!empty($priceData['state_averages'])) {
                $stmtAvg = $db->prepare("INSERT INTO pineapple_state_averages (price_id, state, average_price, price_change, percent_change) 
                                         VALUES (?, ?, ?, ?, ?)");
                foreach ($priceData['state_averages'] as $avg) {
                    $stmtAvg->execute([
                        $priceId,
                        $avg['state'],
                        $avg['average_price'],
                        $avg['price_change'] ?? null,
                        $avg['percent_change'] ?? null
                    ]);
                }
            }
            
            // Insert state lowest prices
            if (!empty($priceData['state_lowest'])) {
                $stmtLow = $db->prepare("INSERT INTO pineapple_state_lowest (price_id, state, shop, date, price) 
                                         VALUES (?, ?, ?, ?, ?)");
                foreach ($priceData['state_lowest'] as $low) {
                    $stmtLow->execute([
                        $priceId,
                        $low['state'],
                        $low['shop'] ?? null,
                        $low['date'] ?? null,
                        $low['price']
                    ]);
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error storing price data: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Try to handle foreign key constraint errors by dropping and recreating tables
            try {
                $db->exec("SET FOREIGN_KEY_CHECKS=0");
                $db->exec("DROP TABLE IF EXISTS pineapple_state_lowest");
                $db->exec("DROP TABLE IF EXISTS pineapple_state_averages");
                $db->exec("DROP TABLE IF EXISTS pineapple_prices");
                $db->exec("SET FOREIGN_KEY_CHECKS=1");
                
                // Retry once
                return self::storeInDatabase($priceData);
            } catch (Exception $retryException) {
                error_log("Retry also failed: " . $retryException->getMessage());
                return false;
            }
        }
    }
    
    /**
     * Get latest price from database
     * @return array|false
     */
    public static function getLatestPriceFromDB() {
        $db = Database::getInstance()->getConnection();
        
        try {
            $stmt = $db->query("SELECT * FROM pineapple_prices ORDER BY created_at DESC LIMIT 1");
            $price = $stmt->fetch();
            
            if ($price) {
                // Get state averages
                $stmtAvg = $db->prepare("SELECT * FROM pineapple_state_averages WHERE price_id = ? ORDER BY state");
                $stmtAvg->execute([$price['id']]);
                $price['state_averages_data'] = $stmtAvg->fetchAll();
                
                // Get state lowest prices
                $stmtLow = $db->prepare("SELECT * FROM pineapple_state_lowest WHERE price_id = ? ORDER BY state");
                $stmtLow->execute([$price['id']]);
                $price['state_lowest_data'] = $stmtLow->fetchAll();
            }
            
            return $price;
        } catch (Exception $e) {
            return false;
        }
    }
}

