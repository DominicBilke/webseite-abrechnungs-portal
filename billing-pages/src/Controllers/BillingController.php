<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;
use TCPDF;

/**
 * Billing Controller - Handles invoice generation and PDF creation
 */
class BillingController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        // Check authentication
        if (!$this->session->isAuthenticated()) {
            header('Location: /');
            exit;
        }
    }

    /**
     * Helper: Get invoice stats for the current user
     */
    private function getStats(int $userId): array
    {
        $stats = [
            'total_invoices' => 0,
            'total_amount' => 0.0,
            'paid_invoices' => 0,
            'pending_invoices' => 0
        ];
        try {
            $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['total_invoices'] = $result['total'] ?? 0;

            $sql = "SELECT SUM(total) as total_amount FROM invoices WHERE user_id = ?";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['total_amount'] = $result['total_amount'] ?? 0.0;

            $sql = "SELECT COUNT(*) as paid FROM invoices WHERE user_id = ? AND status = 'paid'";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['paid_invoices'] = $result['paid'] ?? 0;

            $sql = "SELECT COUNT(*) as pending FROM invoices WHERE user_id = ? AND status = 'draft'";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['pending_invoices'] = $result['pending'] ?? 0;
        } catch (\Exception $e) {}
        return $stats;
    }

    /**
     * Show billing index (dashboard-style)
     */
    public function index(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT i.*, c.company_name FROM invoices i LEFT JOIN companies c ON i.client_id = c.id WHERE i.user_id = ? ORDER BY i.invoice_date DESC LIMIT ? OFFSET ?";
        $invoices = $this->database->query($sql, [$userId, $limit, $offset]);
        $countSql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);
        $stats = $this->getStats($userId);
        $this->render('billing/index', [
            'title' => $this->localization->get('nav_billing'),
            'invoices' => $invoices,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Show billing overview (list-style)
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT i.*, c.company_name FROM invoices i LEFT JOIN companies c ON i.client_id = c.id WHERE i.user_id = ? ORDER BY i.invoice_date DESC LIMIT ? OFFSET ?";
        $invoices = $this->database->query($sql, [$userId, $limit, $offset]);
        $countSql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);
        $stats = $this->getStats($userId);
        $this->render('billing/overview', [
            'title' => $this->localization->get('billing') . ' - ' . $this->localization->get('overview'),
            'invoices' => $invoices,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Show all invoices (for /billing/all)
     */
    public function all(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT i.*, c.company_name FROM invoices i LEFT JOIN companies c ON i.client_id = c.id WHERE i.user_id = ? ORDER BY i.invoice_date DESC LIMIT ? OFFSET ?";
        $invoices = $this->database->query($sql, [$userId, $limit, $offset]);
        $countSql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);
        $stats = $this->getStats($userId);
        $this->render('billing/all', [
            'title' => $this->localization->get('all') . ' ' . $this->localization->get('invoices'),
            'invoices' => $invoices,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Show create invoice form
     */
    public function showCreate(): void
    {
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);
        $sql = "SELECT w.*, c.company_name FROM work_entries w LEFT JOIN companies c ON w.company_id = c.id WHERE w.user_id = ? AND w.billed = 0 ORDER BY w.work_date DESC";
        $workEntries = $this->database->query($sql, [$userId]);
        $this->render('billing/create', [
            'title' => $this->localization->get('create') . ' ' . $this->localization->get('invoice'),
            'companies' => $companies,
            'workEntries' => $workEntries
        ]);
    }

    /**
     * Show edit invoice form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        $sql = "SELECT * FROM invoices WHERE id = ? AND user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }
        $sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
        $invoice_items = $this->database->query($sql, [$id]);
        $this->render('billing/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('invoice'),
            'invoice' => $invoice,
            'invoice_items' => $invoice_items ?? []
        ]);
    }

    /**
     * Show invoice details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        $sql = "SELECT i.*, c.company_name, c.company_address, c.company_contact FROM invoices i LEFT JOIN companies c ON i.client_id = c.id WHERE i.id = ? AND i.user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }
        $sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
        $invoice_items = $this->database->query($sql, [$id]);
        $this->render('billing/view', [
            'title' => $this->localization->get('invoice') . ' ' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'invoice_items' => $invoice_items ?? []
        ]);
    }

    /**
     * Show generate invoice form (manual entry)
     */
    public function generate(): void
    {
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);
        $default_invoice_number = $this->generateInvoiceNumber($userId);
        $this->render('billing/generate', [
            'title' => $this->localization->get('generate') . ' ' . $this->localization->get('invoice'),
            'companies' => $companies,
            'default_invoice_number' => $default_invoice_number,
            'selected_client' => '',
        ]);
    }

    /**
     * Create new invoice
     */
    public function create(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $clientId = (int)($_POST['client_id'] ?? 0);
        $invoiceDate = trim($_POST['invoice_date'] ?? date('Y-m-d'));
        $dueDate = trim($_POST['due_date'] ?? '');
        $workEntryIds = $_POST['work_entries'] ?? [];
        $notes = trim($_POST['notes'] ?? '');

        if (empty($clientId) || empty($workEntryIds)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /billing/create');
            exit;
        }

        // Calculate invoice total
        $total = 0;
        $workEntries = [];
        
        foreach ($workEntryIds as $workId) {
            $sql = "SELECT * FROM work_entries WHERE id = ? AND user_id = ? AND billed = 0";
            $workEntry = $this->database->queryOne($sql, [$workId, $userId]);
            
            if ($workEntry) {
                $total += $workEntry['work_total'];
                $workEntries[] = $workEntry;
            }
        }

        if (empty($workEntries)) {
            $this->session->setFlash('error', $this->localization->get('error_no_work_entries'));
            header('Location: /billing/create');
            exit;
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Get company info for the invoice
        $companySql = "SELECT company_name, company_address, company_email FROM companies WHERE id = ? AND user_id = ?";
        $company = $this->database->queryOne($companySql, [$clientId, $userId]);
        
        // Create invoice
        $sql = "INSERT INTO invoices (user_id, client_id, invoice_number, invoice_date, due_date, 
                client_name, client_address, client_email, subtotal, total, notes, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')";
        
        try {
            $this->database->execute($sql, [
                $userId, $clientId, $invoiceNumber, $invoiceDate, $dueDate,
                $company['company_name'] ?? '', $company['company_address'] ?? '', $company['company_email'] ?? '',
                $total, $total, $notes
            ]);
            
            $invoiceId = $this->database->lastInsertId();

            // Mark work entries as billed
            foreach ($workEntryIds as $workId) {
                $sql = "UPDATE work_entries SET billed = 1, invoice_id = ? WHERE id = ? AND user_id = ?";
                $this->database->execute($sql, [$invoiceId, $workId, $userId]);
            }

            $this->session->setFlash('success', $this->localization->get('success_invoice_created'));
            header('Location: /billing/view/' . $invoiceId);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /billing/create');
            exit;
        }
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(int $userId): string
    {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as count FROM invoices WHERE user_id = ? AND YEAR(invoice_date) = ?";
        $result = $this->database->queryOne($sql, [$userId, $year]);
        $count = ($result['count'] ?? 0) + 1;
        
        return "INV-{$year}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate PDF invoice
     */
    public function generatePdf(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get invoice with company info
        $sql = "SELECT i.*, c.company_name, c.company_address, c.company_contact 
                FROM invoices i 
                LEFT JOIN companies c ON i.client_id = c.id 
                WHERE i.id = ? AND i.user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }

        // Get work entries for this invoice
        $sql = "SELECT * FROM work_entries WHERE invoice_id = ? AND user_id = ? ORDER BY work_date";
        $workEntries = $this->database->query($sql, [$id, $userId]);

        // Generate PDF
        $pdf = $this->createInvoicePdf($invoice, $workEntries);
        
        // Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="invoice-' . $invoice['invoice_number'] . '.pdf"');
        echo $pdf;
    }

    /**
     * Create invoice PDF
     */
    private function createInvoicePdf(array $invoice, array $workEntries): string
    {
        // This is a simplified PDF generation
        // In a real application, you would use a proper PDF library like TCPDF or FPDF
        
        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .invoice-info { margin-bottom: 20px; }
            .client-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .total { text-align: right; font-weight: bold; }
        </style></head><body>';
        
        $html .= '<div class="header"><h1>INVOICE</h1></div>';
        $html .= '<div class="invoice-info">';
        $html .= '<p><strong>Invoice Number:</strong> ' . htmlspecialchars($invoice['invoice_number']) . '</p>';
        $html .= '<p><strong>Date:</strong> ' . htmlspecialchars($invoice['invoice_date']) . '</p>';
        $html .= '<p><strong>Due Date:</strong> ' . htmlspecialchars($invoice['due_date']) . '</p>';
        $html .= '</div>';
        
        $html .= '<div class="client-info">';
        $html .= '<h3>Bill To:</h3>';
        $html .= '<p>' . htmlspecialchars($invoice['company_name']) . '</p>';
        $html .= '<p>' . htmlspecialchars($invoice['company_address']) . '</p>';
        $html .= '<p>Contact: ' . htmlspecialchars($invoice['company_contact']) . '</p>';
        $html .= '</div>';
        
        $html .= '<table>';
        $html .= '<tr><th>Date</th><th>Description</th><th>Hours</th><th>Rate</th><th>Total</th></tr>';
        
        foreach ($workEntries as $work) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($work['work_date']) . '</td>';
            $html .= '<td>' . htmlspecialchars($work['work_description']) . '</td>';
            $html .= '<td>' . htmlspecialchars($work['work_hours']) . '</td>';
            $html .= '<td>' . htmlspecialchars($work['work_rate']) . '</td>';
            $html .= '<td>' . htmlspecialchars($work['work_total']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        $html .= '<div class="total">';
        $html .= '<p><strong>Total: €' . number_format($invoice['total'], 2) . '</strong></p>';
        $html .= '</div>';
        
        if (!empty($invoice['notes'])) {
            $html .= '<div><h3>Notes:</h3><p>' . htmlspecialchars($invoice['notes']) . '</p></div>';
        }
        
        $html .= '</body></html>';
        
        // For now, return HTML (in a real app, convert to PDF)
        return $html;
    }

    /**
     * Send invoice via email
     */
    public function sendEmail(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get invoice with company info
        $sql = "SELECT i.*, c.company_name, c.company_email 
                FROM invoices i 
                LEFT JOIN companies c ON i.client_id = c.id 
                WHERE i.id = ? AND i.user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }

        if (empty($invoice['company_email'])) {
            $this->session->setFlash('error', $this->localization->get('error_no_email'));
            header('Location: /billing/view/' . $id);
            exit;
        }

        // Update invoice status
        $sql = "UPDATE invoices SET status = 'sent', sent_date = NOW() WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [$id, $userId]);
            
            // In a real application, you would send the email here
            // For now, just show success message
            
            $this->session->setFlash('success', $this->localization->get('success_invoice_sent'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_send'));
        }
        
        header('Location: /billing/view/' . $id);
        exit;
    }

    /**
     * Mark invoice as paid
     */
    public function markPaid(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if invoice exists and belongs to user
        $sql = "SELECT id FROM invoices WHERE id = ? AND user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }

        // Update invoice status
        $sql = "UPDATE invoices SET status = 'paid', paid_date = NOW() WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [$id, $userId]);
            $this->session->setFlash('success', $this->localization->get('success_invoice_paid'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
        }
        
        header('Location: /billing/view/' . $id);
        exit;
    }

    /**
     * Delete invoice
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if invoice exists and belongs to user
        $sql = "SELECT id FROM invoices WHERE id = ? AND user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing/overview');
            exit;
        }

        // Unmark work entries as billed
        $sql = "UPDATE work_entries SET billed = 0, invoice_id = NULL WHERE invoice_id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [$id, $userId]);
            
            // Delete invoice
            $sql = "DELETE FROM invoices WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /billing/overview');
        exit;
    }

    /**
     * Search invoices
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT i.id, i.invoice_number, i.invoice_date, i.total, i.status, c.company_name 
                FROM invoices i 
                LEFT JOIN companies c ON i.client_id = c.id 
                WHERE i.user_id = ? AND (i.invoice_number LIKE ? OR c.company_name LIKE ?)
                ORDER BY i.invoice_date DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }

    /**
     * AJAX: Get selectable items for invoice generation modal
     */
    public function data(string $type): void
    {
        $userId = $this->session->getUserId();
        $data = [];
        $success = true;
        $error = '';
        try {
            switch ($type) {
                case 'work':
                    $sql = "SELECT id, work_description, work_total, work_date FROM work_entries WHERE user_id = ? AND billed = 0 ORDER BY work_date DESC";
                    $data = $this->database->query($sql, [$userId]);
                    break;
                case 'company':
                    $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
                    $data = $this->database->query($sql, [$userId]);
                    break;
                case 'tour':
                    $sql = "SELECT id, tour_name, tour_total, tour_date FROM tours WHERE user_id = ? ORDER BY tour_date DESC";
                    $data = $this->database->query($sql, [$userId]);
                    break;
                case 'task':
                    $sql = "SELECT id, task_name, task_total FROM tasks WHERE user_id = ? ORDER BY task_due_date DESC";
                    $data = $this->database->query($sql, [$userId]);
                    break;
                case 'money':
                    $sql = "SELECT id, description, amount FROM money_entries WHERE user_id = ? ORDER BY payment_date DESC";
                    $data = $this->database->query($sql, [$userId]);
                    break;
                default:
                    $success = false;
                    $error = 'Invalid type';
            }
        } catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
        }
        $this->jsonResponse([
            'success' => $success,
            'data' => $data,
            'error' => $error
        ]);
    }

    /**
     * AJAX: Get invoice statistics for dashboard/cards
     */
    public function stats(): void
    {
        $userId = $this->session->getUserId();
        $stats = [
            'total_invoices' => 0,
            'total_amount' => 0.0,
            'paid_invoices' => 0,
            'pending_invoices' => 0
        ];
        try {
            // Total invoices
            $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['total_invoices'] = $result['total'] ?? 0;

            // Total amount
            $sql = "SELECT SUM(total) as total_amount FROM invoices WHERE user_id = ?";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['total_amount'] = $result['total_amount'] ?? 0.0;

            // Paid invoices
            $sql = "SELECT COUNT(*) as paid FROM invoices WHERE user_id = ? AND status = 'paid'";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['paid_invoices'] = $result['paid'] ?? 0;

            // Pending invoices
            $sql = "SELECT COUNT(*) as pending FROM invoices WHERE user_id = ? AND status = 'draft'";
            $result = $this->database->queryOne($sql, [$userId]);
            $stats['pending_invoices'] = $result['pending'] ?? 0;
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()]);
            return;
        }
        $this->jsonResponse(['success' => true, 'data' => $stats]);
    }
} 