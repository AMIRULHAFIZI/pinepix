<?php
/**
 * Environment Variable Loader
 * Loads .env file and makes variables available via getenv() and $_ENV
 */
class Env {
    private static $loaded = false;
    
    /**
     * Load environment variables from .env file
     */
    public static function load() {
        if (self::$loaded) {
            return;
        }
        
        $envFile = __DIR__ . '/../.env';
        
        if (!file_exists($envFile)) {
            // Try to create from .env.example if it exists
            $exampleFile = __DIR__ . '/../.env.example';
            if (file_exists($exampleFile)) {
                error_log('Warning: .env file not found. Please copy .env.example to .env and configure it.');
            }
            return;
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse KEY=VALUE format
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
                
                // Set environment variable if not already set
                if (!getenv($key)) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::load();
        return getenv($key) !== false ? getenv($key) : $default;
    }
    
    /**
     * Check if environment variable exists
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        self::load();
        return getenv($key) !== false;
    }
}

