<?php
/**
 * Setup Demo User Script
 * 
 * This script creates a demo user with the correct password hash.
 * Run this script once to set up the demo user for testing.
 */

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Database configuration
$dbHost = $_ENV['DB_HOST'] ?? 'localhost:3306';
$dbName = $_ENV['DB_NAME'] ?? 'billing_system';
$dbUser = $_ENV['DB_USER'] ?? 'root';
$dbPass = $_ENV['DB_PASS'] ?? '';

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    
    // Check if demo user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'demo'");
    $stmt->execute();
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        echo "Demo user already exists. Updating password...\n";
        
        // Update demo user password
        $demoPasswordHash = password_hash('demo123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'demo'");
        $stmt->execute([$demoPasswordHash]);
        
        echo "Demo user password updated successfully.\n";
    } else {
        echo "Creating demo user...\n";
        
        // Create demo user
        $demoPasswordHash = password_hash('demo123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'demo',
            'demo@billing-portal.com',
            $demoPasswordHash,
            'Demo',
            'User',
            'user',
            'active'
        ]);
        
        echo "Demo user created successfully.\n";
    }
    
    // Check if admin user exists and update password
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    $adminUser = $stmt->fetch();
    
    if ($adminUser) {
        echo "Admin user exists. Updating password...\n";
        
        // Update admin user password
        $adminPasswordHash = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $stmt->execute([$adminPasswordHash]);
        
        echo "Admin user password updated successfully.\n";
    } else {
        echo "Creating admin user...\n";
        
        // Create admin user
        $adminPasswordHash = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'admin',
            'admin@billing-portal.com',
            $adminPasswordHash,
            'Admin',
            'User',
            'admin',
            'active'
        ]);
        
        echo "Admin user created successfully.\n";
    }
    
    echo "\n✅ Setup completed successfully!\n";
    echo "\nDemo Credentials:\n";
    echo "Username: demo\n";
    echo "Password: demo123\n";
    echo "\nAdmin Credentials:\n";
    echo "Username: admin\n";
    echo "Password: password\n";
    echo "\nYou can now login to the billing portal.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration.\n";
} 