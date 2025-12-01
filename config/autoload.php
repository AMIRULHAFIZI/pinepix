<?php
// Load environment variables FIRST
require_once __DIR__ . '/../helpers/Env.php';
Env::load();

// Load configuration (will use environment variables if available)
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/db_connection.php';

// Simple autoloader for classes
spl_autoload_register(function ($class) {
    $paths = [
        MODELS_PATH . $class . '.php',
        CONTROLLERS_PATH . $class . '.php',
        ROOT_PATH . 'helpers/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});
