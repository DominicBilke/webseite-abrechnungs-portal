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

    // Check if company_id column already exists in work_entries
    $stmt = $pdo->query("SHOW COLUMNS FROM work_entries LIKE 'company_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding company_id column to work_entries table...\n";
        $pdo->exec("ALTER TABLE work_entries ADD COLUMN company_id INT NULL AFTER user_id");
        $pdo->exec("ALTER TABLE work_entries ADD INDEX idx_company_id (company_id)");
        $pdo->exec("ALTER TABLE work_entries ADD CONSTRAINT fk_work_entries_company_id FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ company_id column added to work_entries table\n";
    } else {
        echo "✓ company_id column already exists in work_entries table\n";
    }

    // Check if invoice_id column already exists in work_entries
    $stmt = $pdo->query("SHOW COLUMNS FROM work_entries LIKE 'invoice_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding invoice_id and billed columns to work_entries table...\n";
        $pdo->exec("ALTER TABLE work_entries ADD COLUMN invoice_id INT NULL AFTER company_id");
        $pdo->exec("ALTER TABLE work_entries ADD COLUMN billed TINYINT(1) DEFAULT 0 AFTER invoice_id");
        $pdo->exec("ALTER TABLE work_entries ADD INDEX idx_invoice_id (invoice_id)");
        $pdo->exec("ALTER TABLE work_entries ADD INDEX idx_billed (billed)");
        
        // Only add foreign key constraint if invoices table exists and has records
        $stmt = $pdo->query("SHOW TABLES LIKE 'invoices'");
        if ($stmt->rowCount() > 0) {
            try {
                $pdo->exec("ALTER TABLE work_entries ADD CONSTRAINT fk_work_entries_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL");
                echo "✓ Foreign key constraint added for invoice_id\n";
            } catch (PDOException $e) {
                echo "⚠ Warning: Could not add foreign key constraint for invoice_id: " . $e->getMessage() . "\n";
                echo "   This is normal if the invoices table is empty or has different structure.\n";
            }
        } else {
            echo "⚠ Warning: invoices table does not exist yet, skipping foreign key constraint\n";
        }
        echo "✓ invoice_id and billed columns added to work_entries table\n";
    } else {
        echo "✓ invoice_id column already exists in work_entries table\n";
    }

    // Check if company_id column already exists in money_entries
    $stmt = $pdo->query("SHOW COLUMNS FROM money_entries LIKE 'company_id'");
    if ($stmt->rowCount() == 0) {
        echo "Adding company_id column to money_entries table...\n";
        $pdo->exec("ALTER TABLE money_entries ADD COLUMN company_id INT NULL AFTER user_id");
        $pdo->exec("ALTER TABLE money_entries ADD INDEX idx_company_id (company_id)");
        $pdo->exec("ALTER TABLE money_entries ADD CONSTRAINT fk_money_entries_company_id FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ company_id column added to money_entries table\n";
    } else {
        echo "✓ company_id column already exists in money_entries table\n";
    }

    // Check if type column already exists in money_entries
    $stmt = $pdo->query("SHOW COLUMNS FROM money_entries LIKE 'type'");
    if ($stmt->rowCount() == 0) {
        echo "Adding type column to money_entries table...\n";
        $pdo->exec("ALTER TABLE money_entries ADD COLUMN type ENUM('income', 'expense') DEFAULT 'income' AFTER payment_status");
        $pdo->exec("ALTER TABLE money_entries ADD INDEX idx_type (type)");
        echo "✓ type column added to money_entries table\n";
    } else {
        echo "✓ type column already exists in money_entries table\n";
    }

    echo "\nMigration completed successfully!\n";
    echo "The billing functionality should now work properly.\n";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
?> 