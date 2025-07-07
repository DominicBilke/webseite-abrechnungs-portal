<?php
/**
 * Add Foreign Key Constraints Script
 * This script adds foreign key constraints after all tables are created
 * to avoid circular dependency issues during initial table creation
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

    echo "Adding foreign key constraints...\n";

    // Add foreign key constraints for work_entries table
    try {
        $pdo->exec("ALTER TABLE work_entries ADD CONSTRAINT fk_work_entries_company_id FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ Foreign key constraint added: work_entries.company_id → companies.id\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Foreign key constraint already exists: work_entries.company_id → companies.id\n";
        } else {
            echo "⚠ Warning: Could not add foreign key constraint for work_entries.company_id: " . $e->getMessage() . "\n";
        }
    }

    try {
        $pdo->exec("ALTER TABLE work_entries ADD CONSTRAINT fk_work_entries_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL");
        echo "✓ Foreign key constraint added: work_entries.invoice_id → invoices.id\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Foreign key constraint already exists: work_entries.invoice_id → invoices.id\n";
        } else {
            echo "⚠ Warning: Could not add foreign key constraint for work_entries.invoice_id: " . $e->getMessage() . "\n";
        }
    }

    // Add foreign key constraints for money_entries table
    try {
        $pdo->exec("ALTER TABLE money_entries ADD CONSTRAINT fk_money_entries_company_id FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ Foreign key constraint added: money_entries.company_id → companies.id\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Foreign key constraint already exists: money_entries.company_id → companies.id\n";
        } else {
            echo "⚠ Warning: Could not add foreign key constraint for money_entries.company_id: " . $e->getMessage() . "\n";
        }
    }

    // Add foreign key constraints for invoices table
    try {
        $pdo->exec("ALTER TABLE invoices ADD CONSTRAINT fk_invoices_client_id FOREIGN KEY (client_id) REFERENCES companies(id) ON DELETE SET NULL");
        echo "✓ Foreign key constraint added: invoices.client_id → companies.id\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Foreign key constraint already exists: invoices.client_id → companies.id\n";
        } else {
            echo "⚠ Warning: Could not add foreign key constraint for invoices.client_id: " . $e->getMessage() . "\n";
        }
    }

    echo "\nForeign key constraints setup completed!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?> 