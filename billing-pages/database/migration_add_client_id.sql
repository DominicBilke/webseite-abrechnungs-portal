-- Migration: Add missing columns for billing functionality
-- This migration adds the missing client_id and invoice_id columns

-- Add client_id column to invoices table
ALTER TABLE invoices 
ADD COLUMN client_id INT NULL AFTER user_id,
ADD INDEX idx_client_id (client_id);

-- Add foreign key constraint for invoices
ALTER TABLE invoices 
ADD CONSTRAINT fk_invoices_client_id 
FOREIGN KEY (client_id) REFERENCES companies(id) ON DELETE SET NULL;

-- Add invoice_id and billed columns to work_entries table
ALTER TABLE work_entries 
ADD COLUMN invoice_id INT NULL AFTER user_id,
ADD COLUMN billed TINYINT(1) DEFAULT 0 AFTER invoice_id,
ADD INDEX idx_invoice_id (invoice_id),
ADD INDEX idx_billed (billed);

-- Add foreign key constraint for work_entries
ALTER TABLE work_entries 
ADD CONSTRAINT fk_work_entries_invoice_id 
FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL;

-- Update existing invoices to link to companies based on client_name
-- This is a data migration step - you may need to run this manually for existing data
-- UPDATE invoices i 
-- INNER JOIN companies c ON i.client_name = c.company_name AND i.user_id = c.user_id
-- SET i.client_id = c.id 
-- WHERE i.client_id IS NULL; 