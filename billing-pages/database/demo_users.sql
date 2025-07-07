-- Demo Users for Billing Pages Portal
-- Run this script to create demo users for testing

-- Demo user (password: demo123)
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('demo', 'demo@billing-portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo', 'User', 'user', 'active')
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    status = 'active';

-- Admin user (password: password)
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('admin', 'admin@billing-portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', 'active')
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    status = 'active';

-- Sample companies for demo user
INSERT INTO companies (user_id, company_name, company_address, company_phone, company_email, company_contact, company_vat, company_registration, company_bank, status) VALUES 
((SELECT id FROM users WHERE username = 'demo'), 'Musterfirma GmbH', 'Musterstraße 123\n12345 Musterstadt', '+49 123 456789', 'info@musterfirma.de', 'Max Mustermann', 'DE123456789', 'HRB 12345', 'Deutsche Bank\nIBAN: DE12345678901234567890\nBIC: DEUTDEDB123', 'active'),
((SELECT id FROM users WHERE username = 'demo'), 'Test Company AG', 'Testweg 456\n54321 Teststadt', '+49 987 654321', 'contact@testcompany.de', 'Anna Test', 'DE987654321', 'HRB 98765', 'Commerzbank\nIBAN: DE98765432109876543210\nBIC: COBADEFF123', 'active');

-- Sample work entries for demo user
INSERT INTO work_entries (user_id, work_date, work_hours, work_description, work_type, work_rate, work_total, work_project, work_client, status) VALUES 
((SELECT id FROM users WHERE username = 'demo'), '2024-01-15', 8.0, 'Website development and design', 'development', 75.00, 600.00, 'Company Website', 'Musterfirma GmbH', 'completed'),
((SELECT id FROM users WHERE username = 'demo'), '2024-01-16', 6.5, 'Database optimization and maintenance', 'maintenance', 75.00, 487.50, 'System Maintenance', 'Test Company AG', 'completed'),
((SELECT id FROM users WHERE username = 'demo'), '2024-01-17', 4.0, 'Consulting and planning meeting', 'consulting', 100.00, 400.00, 'Project Planning', 'Musterfirma GmbH', 'completed'),
((SELECT id FROM users WHERE username = 'demo'), '2024-01-18', 7.0, 'Testing and quality assurance', 'testing', 75.00, 525.00, 'QA Testing', 'Test Company AG', 'completed');

-- Sample money entries for demo user
INSERT INTO money_entries (user_id, amount, currency, description, payment_method, payment_date, payment_status, category, reference, notes) VALUES 
((SELECT id FROM users WHERE username = 'demo'), 1500.00, 'EUR', 'Payment for website development', 'bank_transfer', '2024-01-20', 'paid', 'income', 'INV-2024-0001', 'Payment received for Musterfirma project'),
((SELECT id FROM users WHERE username = 'demo'), 800.00, 'EUR', 'Consulting fee', 'paypal', '2024-01-22', 'paid', 'income', 'INV-2024-0002', 'Consulting payment from Test Company'),
((SELECT id FROM users WHERE username = 'demo'), -150.00, 'EUR', 'Software license renewal', 'credit_card', '2024-01-25', 'paid', 'expense', 'LIC-2024-001', 'Annual software license'),
((SELECT id FROM users WHERE username = 'demo'), -50.00, 'EUR', 'Office supplies', 'cash', '2024-01-26', 'paid', 'expense', 'SUP-2024-001', 'Printer paper and ink');

-- Sample tasks for demo user
INSERT INTO tasks (user_id, task_name, task_description, task_status, task_priority, task_assigned, task_due_date, task_estimated_hours, task_rate, task_total) VALUES 
((SELECT id FROM users WHERE username = 'demo'), 'Complete website redesign', 'Redesign the main website with new branding', 'in_progress', 'high', 'Demo User', '2024-02-15', 20.0, 75.00, 1500.00),
((SELECT id FROM users WHERE username = 'demo'), 'Database migration', 'Migrate old database to new structure', 'pending', 'medium', 'Demo User', '2024-02-20', 8.0, 75.00, 600.00),
((SELECT id FROM users WHERE username = 'demo'), 'Security audit', 'Perform security audit of all systems', 'pending', 'urgent', 'Demo User', '2024-02-10', 12.0, 100.00, 1200.00),
((SELECT id FROM users WHERE username = 'demo'), 'Documentation update', 'Update technical documentation', 'completed', 'low', 'Demo User', '2024-01-30', 4.0, 75.00, 300.00);

-- Sample tours for demo user
INSERT INTO tours (user_id, tour_name, tour_date, tour_start, tour_end, tour_duration, tour_distance, tour_rate, tour_total, tour_description, tour_vehicle, tour_driver, status) VALUES 
((SELECT id FROM users WHERE username = 'demo'), 'Client Meeting - Musterfirma', '2024-01-15', '09:00:00', '11:00:00', 2.0, 25.5, 50.00, 100.00, 'Initial client meeting and project discussion', 'Company Car', 'Demo User', 'completed'),
((SELECT id FROM users WHERE username = 'demo'), 'Site Visit - Test Company', '2024-01-18', '14:00:00', '16:30:00', 2.5, 18.2, 50.00, 125.00, 'On-site consultation and system review', 'Company Car', 'Demo User', 'completed'),
((SELECT id FROM users WHERE username = 'demo'), 'Training Session', '2024-01-22', '10:00:00', '15:00:00', 5.0, 35.0, 50.00, 250.00, 'Staff training and system introduction', 'Company Car', 'Demo User', 'completed');

-- Sample invoices for demo user
INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, client_address, client_email, subtotal, tax_rate, tax_amount, total, currency, status) VALUES 
((SELECT id FROM users WHERE username = 'demo'), 'INV-2024-0001', '2024-01-15', '2024-02-14', 'Musterfirma GmbH', 'Musterstraße 123\n12345 Musterstadt', 'info@musterfirma.de', 1000.00, 19.00, 190.00, 1190.00, 'EUR', 'paid'),
((SELECT id FROM users WHERE username = 'demo'), 'INV-2024-0002', '2024-01-18', '2024-02-17', 'Test Company AG', 'Testweg 456\n54321 Teststadt', 'contact@testcompany.de', 800.00, 19.00, 152.00, 952.00, 'EUR', 'sent');

-- Sample invoice items
INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total) VALUES 
((SELECT id FROM invoices WHERE invoice_number = 'INV-2024-0001'), 'Website development and design', 8.0, 75.00, 600.00),
((SELECT id FROM invoices WHERE invoice_number = 'INV-2024-0001'), 'Database optimization', 6.5, 75.00, 487.50),
((SELECT id FROM invoices WHERE invoice_number = 'INV-2024-0001'), 'Consulting and planning', 4.0, 100.00, 400.00),
((SELECT id FROM invoices WHERE invoice_number = 'INV-2024-0002'), 'System maintenance and updates', 7.0, 75.00, 525.00),
((SELECT id FROM invoices WHERE invoice_number = 'INV-2024-0002'), 'Testing and quality assurance', 4.0, 75.00, 300.00); 