<?php
/**
 * Application Configuration
 */

// Error reporting
if ($_ENV['APP_DEBUG'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'billing_portal');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Application configuration
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Billing Pages');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? false);

// Session configuration
define('SESSION_SECRET', $_ENV['SESSION_SECRET'] ?? 'default-secret-key');
define('SESSION_LIFETIME', $_ENV['SESSION_LIFETIME'] ?? 3600);

// File upload configuration
define('UPLOAD_MAX_SIZE', $_ENV['UPLOAD_MAX_SIZE'] ?? 10485760);
define('UPLOAD_ALLOWED_TYPES', $_ENV['UPLOAD_ALLOWED_TYPES'] ?? 'jpg,jpeg,png,pdf,doc,docx');

// Default language
define('DEFAULT_LOCALE', $_ENV['DEFAULT_LOCALE'] ?? 'de');

// Set timezone
date_default_timezone_set('Europe/Berlin');

// Set session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME); 