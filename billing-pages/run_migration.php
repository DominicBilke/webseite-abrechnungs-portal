<?php
/**
 * Database Migration Script
 * This script adds the missing columns for billing functionality
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "Starting database migration...\n";

    // Check if client_id column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM invoices LIKE 'client_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding client_id column to invoices table...\n";
        $pdo->exec("ALTER TABLE invoices ADD COLUMN client_id INT NULL AFTER user_id");
        $pdo->exec("ALTER TABLE invoices ADD INDEX idx_client_id (client_id)");
        $pdo->exec("ALTER TABLE invoices ADD CONSTRAINT fk_invoices_client_id FOREIGN KEY (client_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ client_id column added to invoices table\n";
    } else {
        echo "✓ client_id column already exists in invoices table\n";
    }

    // Check if invoice_id column already exists in work_entries
    $stmt = $pdo->query("SHOW COLUMNS FROM work_entries LIKE 'invoice_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding invoice_id and billed columns to work_entries table...\n";
        $pdo->exec("ALTER TABLE work_entries ADD COLUMN invoice_id INT NULL AFTER user_id");
        $pdo->exec("ALTER TABLE work_entries ADD COLUMN billed TINYINT(1) DEFAULT 0 AFTER invoice_id");
        $pdo->exec("ALTER TABLE work_entries ADD INDEX idx_invoice_id (invoice_id)");
        $pdo->exec("ALTER TABLE work_entries ADD INDEX idx_billed (billed)");
        $pdo->exec("ALTER TABLE work_entries ADD CONSTRAINT fk_work_entries_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL");
        echo "✓ invoice_id and billed columns added to work_entries table\n";
    } else {
        echo "✓ invoice_id column already exists in work_entries table\n";
    }

    echo "\nMigration completed successfully!\n";
    echo "The billing functionality should now work properly.\n";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
?> 