-- Migration: Add missing columns for billing functionality
-- This migration adds the missing client_id, invoice_id, and company_id columns

-- Add client_id column to invoices table
ALTER TABLE invoices 
ADD COLUMN client_id INT NULL AFTER user_id,
ADD INDEX idx_client_id (client_id);

-- Add foreign key constraint for invoices
ALTER TABLE invoices 
ADD CONSTRAINT fk_invoices_client_id 
FOREIGN KEY (client_id) REFERENCES companies(id) ON DELETE SET NULL;

-- Add company_id column to work_entries table
ALTER TABLE work_entries 
ADD COLUMN company_id INT NULL AFTER user_id,
ADD INDEX idx_company_id (company_id);

-- Add foreign key constraint for work_entries company_id
ALTER TABLE work_entries 
ADD CONSTRAINT fk_work_entries_company_id 
FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL;

-- Add invoice_id and billed columns to work_entries table
ALTER TABLE work_entries 
ADD COLUMN invoice_id INT NULL AFTER company_id,
ADD COLUMN billed TINYINT(1) DEFAULT 0 AFTER invoice_id,
ADD INDEX idx_invoice_id (invoice_id),
ADD INDEX idx_billed (billed);

-- Add foreign key constraint for work_entries invoice_id
ALTER TABLE work_entries 
ADD CONSTRAINT fk_work_entries_invoice_id 
FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL;

-- Add company_id column to money_entries table
ALTER TABLE money_entries 
ADD COLUMN company_id INT NULL AFTER user_id,
ADD INDEX idx_company_id (company_id);

-- Add foreign key constraint for money_entries
ALTER TABLE money_entries 
ADD CONSTRAINT fk_money_entries_company_id 
FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL;

-- Add type column to money_entries table
ALTER TABLE money_entries 
ADD COLUMN type ENUM('income', 'expense') DEFAULT 'income' AFTER payment_status,
ADD INDEX idx_type (type);

-- Update existing invoices to link to companies based on client_name
-- This is a data migration step - you may need to run this manually for existing data
-- UPDATE invoices i 
-- INNER JOIN companies c ON i.client_name = c.company_name AND i.user_id = c.user_id
-- SET i.client_id = c.id 
-- WHERE i.client_id IS NULL; 