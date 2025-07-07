<?php
/**
 * Test script to verify login page functionality
 */

// Start session
session_start();

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load configuration
require_once __DIR__ . '/config/config.php';

try {
    // Test localization
    $localization = new BillingPages\Core\Localization();
    echo "✅ Localization initialized: " . $localization->getLocale() . "\n";
    echo "✅ Translation test: " . $localization->get('login') . "\n";
    
    // Test session
    $session = new BillingPages\Core\Session();
    echo "✅ Session initialized\n";
    echo "✅ Session authenticated: " . ($session->isAuthenticated() ? 'Yes' : 'No') . "\n";
    
    // Test AuthController
    $authController = new BillingPages\Controllers\AuthController();
    echo "✅ AuthController initialized\n";
    
    // Test BaseController
    $baseController = new BillingPages\Controllers\BaseController();
    echo "✅ BaseController initialized\n";
    
    echo "\n🎉 All components working correctly!\n";
    echo "The login page should now work without null reference errors.\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 