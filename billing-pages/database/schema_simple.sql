-- Billing Portal Database Schema (Simplified - No Foreign Keys)
-- This schema creates tables without foreign key constraints to avoid circular dependencies
-- Run add_foreign_keys.php after this to add the constraints

-- Create database
CREATE DATABASE IF NOT EXISTS billing_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE billing_system;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('admin', 'manager', 'user') DEFAULT 'user',
    domain VARCHAR(100),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_domain (domain),
    INDEX idx_status (status)
);

-- Companies table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    company_address TEXT,
    company_phone VARCHAR(20),
    company_email VARCHAR(100),
    company_contact VARCHAR(100),
    company_vat VARCHAR(50),
    company_registration VARCHAR(50),
    company_bank TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_company_name (company_name),
    INDEX idx_status (status)
);

-- Tours table
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_name VARCHAR(100) NOT NULL,
    tour_date DATE NOT NULL,
    tour_start TIME,
    tour_end TIME,
    tour_duration DECIMAL(5,2),
    tour_distance DECIMAL(8,2),
    tour_route TEXT,
    tour_vehicle VARCHAR(50),
    tour_driver VARCHAR(100),
    tour_description TEXT,
    tour_rate DECIMAL(10,2),
    tour_total DECIMAL(10,2),
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_tour_date (tour_date),
    INDEX idx_status (status)
);

-- Tasks table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_name VARCHAR(100) NOT NULL,
    task_description TEXT,
    task_status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    task_priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    task_assigned VARCHAR(100),
    task_due_date DATE,
    task_completed_date DATETIME,
    task_estimated_hours DECIMAL(5,2),
    task_actual_hours DECIMAL(5,2),
    task_rate DECIMAL(10,2),
    task_total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_task_status (task_status),
    INDEX idx_task_priority (task_priority),
    INDEX idx_task_due_date (task_due_date)
);

-- Money entries table
CREATE TABLE money_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_id INT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    description TEXT,
    payment_method VARCHAR(50),
    payment_date DATE,
    payment_status ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
    type ENUM('income', 'expense') DEFAULT 'income',
    category VARCHAR(50),
    reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_company_id (company_id),
    INDEX idx_amount (amount),
    INDEX idx_payment_date (payment_date),
    INDEX idx_payment_status (payment_status),
    INDEX idx_type (type)
);

-- Invoices table
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    client_id INT NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE NOT NULL,
    client_name VARCHAR(100),
    client_address TEXT,
    client_email VARCHAR(100),
    subtotal DECIMAL(10,2) NOT NULL,
    tax_rate DECIMAL(5,2) DEFAULT 19.00,
    tax_amount DECIMAL(10,2),
    total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    payment_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_invoice_date (invoice_date),
    INDEX idx_due_date (due_date),
    INDEX idx_status (status)
);

-- Invoice items table
CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(8,2) DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_invoice_id (invoice_id)
);

-- Work entries table
CREATE TABLE work_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_id INT NULL,
    invoice_id INT NULL,
    billed TINYINT(1) DEFAULT 0,
    work_date DATE NOT NULL,
    work_hours DECIMAL(5,2) NOT NULL,
    work_description TEXT,
    work_type VARCHAR(50),
    work_rate DECIMAL(10,2),
    work_total DECIMAL(10,2),
    work_project VARCHAR(100),
    work_client VARCHAR(100),
    status ENUM('pending', 'approved', 'completed', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_company_id (company_id),
    INDEX idx_invoice_id (invoice_id),
    INDEX idx_billed (billed),
    INDEX idx_work_date (work_date),
    INDEX idx_status (status)
);

-- Settings table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_setting (user_id, setting_key),
    INDEX idx_user_id (user_id),
    INDEX idx_setting_key (setting_key)
);

-- Audit log table
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_table_name (table_name),
    INDEX idx_created_at (created_at)
);

-- Insert default admin user
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('admin', 'admin@billing-portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', 'active');

-- Insert demo user (password: demo123)
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('demo', 'demo@billing-portal.com', '$2y$10$YourHashedPasswordHere', 'Demo', 'User', 'user', 'active');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type) VALUES 
('app_name', 'Billing Portal', 'string'),
('default_currency', 'EUR', 'string'),
('default_tax_rate', '19.00', 'number'),
('invoice_prefix', 'INV-', 'string'),
('company_name', 'Your Company Name', 'string'),
('company_address', 'Your Company Address', 'string'),
('company_phone', '+49 123 456789', 'string'),
('company_email', 'info@yourcompany.com', 'string'),
('company_vat', 'DE123456789', 'string'),
('company_bank', 'Bank Name\nIBAN: DE12345678901234567890\nBIC: DEUTDEDB123', 'string'); 