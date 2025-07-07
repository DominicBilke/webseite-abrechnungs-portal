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
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'billing_system');
define('DB_USER', 'root_billing_pages');
define('DB_PASS', 'hggZKAnj1%ni%3w1');

// Application configuration
define('APP_NAME', 'Billing Pages');
define('APP_URL', 'http://billing-pages.com');
define('APP_ENV', 'production');
define('APP_DEBUG', true);

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
@ini_set('session.cookie_httponly', 1);
@ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
@ini_set('session.cookie_samesite', 'Strict');
@ini_set('session.gc_maxlifetime', SESSION_LIFETIME); 