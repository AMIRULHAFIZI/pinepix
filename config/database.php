<?php
/**
 * Database and Application Configuration
 * Values are loaded from .env file if available, otherwise uses defaults
 */

// Database Configuration (from .env or defaults)
define('DB_HOST', Env::get('DB_HOST', 'localhost'));
define('DB_NAME', Env::get('DB_NAME', 'pinepix'));
define('DB_USER', Env::get('DB_USER', 'root'));
define('DB_PASS', Env::get('DB_PASS', ''));
define('DB_CHARSET', Env::get('DB_CHARSET', 'utf8mb4'));

// Application Configuration
define('BASE_URL', Env::get('BASE_URL', 'http://localhost:3000/'));
define('APP_NAME', Env::get('APP_NAME', 'PinePix'));
define('APP_VERSION', Env::get('APP_VERSION', '1.0.0'));

// Paths
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

// File Upload Settings
$maxFileSize = Env::get('MAX_FILE_SIZE', '5242880'); // Default: 5MB
define('MAX_FILE_SIZE', is_numeric($maxFileSize) ? (int)$maxFileSize : 5 * 1024 * 1024);

$allowedTypes = Env::get('ALLOWED_IMAGE_TYPES', 'image/jpeg,image/png,image/jpg,image/gif');
define('ALLOWED_IMAGE_TYPES', explode(',', $allowedTypes));

// Session Configuration
$sessionLifetime = Env::get('SESSION_LIFETIME', '86400'); // Default: 24 hours
define('SESSION_LIFETIME', is_numeric($sessionLifetime) ? (int)$sessionLifetime : 3600 * 24);

// API Keys (from .env or empty - can also be set via admin panel)
define('GEMINI_API_KEY', Env::get('GEMINI_API_KEY', ''));
define('GOOGLE_MAPS_API_KEY', Env::get('GOOGLE_MAPS_API_KEY', ''));

// Email Configuration
define('MAIL_FROM_EMAIL', Env::get('MAIL_FROM_EMAIL', 'pinepixmalaysia@gmail.com'));
define('MAIL_REPLY_TO', Env::get('MAIL_REPLY_TO', 'pinepixmalaysia@gmail.com'));

// SMTP Configuration
define('MAIL_SMTP_HOST', Env::get('MAIL_SMTP_HOST', 'smtp.gmail.com'));
define('MAIL_SMTP_PORT', Env::get('MAIL_SMTP_PORT', '587'));
define('MAIL_SMTP_USER', Env::get('MAIL_SMTP_USER', ''));
define('MAIL_SMTP_PASS', Env::get('MAIL_SMTP_PASS', ''));

// Timezone
$timezone = Env::get('TIMEZONE', 'Asia/Kuala_Lumpur');
date_default_timezone_set($timezone);
