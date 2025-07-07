<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;
use TCPDF;

/**
 * Billing Controller - Handles invoice generation and PDF creation
 */
class BillingController
{
    private Database $database;
    private Session $session;
    private Localization $localization;

    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->session = new Session();
        $this->localization = new Localization();

        // Check authentication
        if (!$this->session->isAuthenticated()) {
            header('Location: /');
            exit;
        }
    }

    /**
     * Show billing index
     */
    public function index(): void
    {
        $userId = $this->session->getUserId();
        
        // Get recent invoices
        $sql = "SELECT * FROM invoices WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
        $invoices = $this->database->query($sql, [$userId]);

        // Get billing statistics
        $stats = $this->getBillingStats($userId);

        $this->render('billing/index', [
            'title' => $this->localization->get('nav_billing'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'invoices' => $invoices,
            'stats' => $stats
        ]);
    }

    /**
     * Generate invoice
     */
    public function generate(string $type, int $id): void
    {
        $userId = $this->session->getUserId();
        
        try {
            switch ($type) {
                case 'company':
                    $invoice = $this->generateCompanyInvoice($userId, $id);
                    break;
                case 'work':
                    $invoice = $this->generateWorkInvoice($userId, $id);
                    break;
                case 'tour':
                    $invoice = $this->generateTourInvoice($userId, $id);
                    break;
                case 'task':
                    $invoice = $this->generateTaskInvoice($userId, $id);
                    break;
                case 'money':
                    $invoice = $this->generateMoneyInvoice($userId, $id);
                    break;
                default:
                    throw new \Exception('Invalid invoice type');
            }

            // Generate PDF
            $pdf = $this->createPDF($invoice);
            
            // Output PDF
            $filename = 'invoice_' . $invoice['invoice_number'] . '.pdf';
            $pdf->Output($filename, 'D');
            
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_generate_invoice'));
            header('Location: /billing');
            exit;
        }
    }

    /**
     * Download invoice
     */
    public function download(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get invoice
        $sql = "SELECT * FROM invoices WHERE id = ? AND user_id = ?";
        $invoice = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$invoice) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /billing');
            exit;
        }

        // Get invoice items
        $sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
        $items = $this->database->query($sql, [$id]);
        $invoice['items'] = $items;

        // Generate PDF
        $pdf = $this->createPDF($invoice);
        
        // Output PDF
        $filename = 'invoice_' . $invoice['invoice_number'] . '.pdf';
        $pdf->Output($filename, 'D');
    }

    /**
     * Get billing data via API
     */
    public function getData(string $type): void
    {
        $userId = $this->session->getUserId();
        
        header('Content-Type: application/json');
        
        try {
            switch ($type) {
                case 'companies':
                    $data = $this->getCompanies($userId);
                    break;
                case 'work':
                    $data = $this->getWorkEntries($userId);
                    break;
                case 'tours':
                    $data = $this->getTours($userId);
                    break;
                case 'tasks':
                    $data = $this->getTasks($userId);
                    break;
                case 'money':
                    $data = $this->getMoneyEntries($userId);
                    break;
                default:
                    throw new \Exception('Invalid data type');
            }
            
            echo json_encode(['success' => true, 'data' => $data]);
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Save billing data via API
     */
    public function saveData(string $type): void
    {
        $userId = $this->session->getUserId();
        
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            switch ($type) {
                case 'invoice':
                    $result = $this->saveInvoice($userId, $input);
                    break;
                default:
                    throw new \Exception('Invalid data type');
            }
            
            echo json_encode(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Generate company invoice
     */
    private function generateCompanyInvoice(int $userId, int $companyId): array
    {
        // Get company data
        $sql = "SELECT * FROM companies WHERE id = ? AND user_id = ?";
        $company = $this->database->queryOne($sql, [$companyId, $userId]);
        
        if (!$company) {
            throw new \Exception('Company not found');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                client_address, client_email, subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, ?, ?, 'EUR', 'draft')";
        
        $subtotal = 0; // Calculate based on company billing
        $taxRate = 19.00;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $this->database->execute($sql, [
            $userId, $invoiceNumber, $company['company_name'], $company['company_address'],
            $company['company_email'], $subtotal, $taxRate, $taxAmount, $total
        ]);

        $invoiceId = $this->database->lastInsertId();

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'client_name' => $company['company_name'],
            'client_address' => $company['company_address'],
            'client_email' => $company['company_email'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => 'EUR',
            'items' => []
        ];
    }

    /**
     * Generate work invoice
     */
    private function generateWorkInvoice(int $userId, int $workId): array
    {
        // Get work entry data
        $sql = "SELECT * FROM work_entries WHERE id = ? AND user_id = ?";
        $work = $this->database->queryOne($sql, [$workId, $userId]);
        
        if (!$work) {
            throw new \Exception('Work entry not found');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, 'EUR', 'draft')";
        
        $subtotal = $work['work_total'];
        $taxRate = 19.00;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $this->database->execute($sql, [
            $userId, $invoiceNumber, $work['work_client'] ?? 'Client', 
            $subtotal, $taxRate, $taxAmount, $total
        ]);

        $invoiceId = $this->database->lastInsertId();

        // Add invoice item
        $sql = "INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total) 
                VALUES (?, ?, ?, ?, ?)";
        $this->database->execute($sql, [
            $invoiceId, $work['work_description'], $work['work_hours'], 
            $work['work_rate'], $work['work_total']
        ]);

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'client_name' => $work['work_client'] ?? 'Client',
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => 'EUR',
            'items' => [[
                'description' => $work['work_description'],
                'quantity' => $work['work_hours'],
                'unit_price' => $work['work_rate'],
                'total' => $work['work_total']
            ]]
        ];
    }

    /**
     * Generate tour invoice
     */
    private function generateTourInvoice(int $userId, int $tourId): array
    {
        // Get tour data
        $sql = "SELECT * FROM tours WHERE id = ? AND user_id = ?";
        $tour = $this->database->queryOne($sql, [$tourId, $userId]);
        
        if (!$tour) {
            throw new \Exception('Tour not found');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, 'EUR', 'draft')";
        
        $subtotal = $tour['tour_total'];
        $taxRate = 19.00;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $this->database->execute($sql, [
            $userId, $invoiceNumber, $tour['tour_name'], 
            $subtotal, $taxRate, $taxAmount, $total
        ]);

        $invoiceId = $this->database->lastInsertId();

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'client_name' => $tour['tour_name'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => 'EUR',
            'items' => []
        ];
    }

    /**
     * Generate task invoice
     */
    private function generateTaskInvoice(int $userId, int $taskId): array
    {
        // Get task data
        $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
        $task = $this->database->queryOne($sql, [$taskId, $userId]);
        
        if (!$task) {
            throw new \Exception('Task not found');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, 'EUR', 'draft')";
        
        $subtotal = $task['task_total'];
        $taxRate = 19.00;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $this->database->execute($sql, [
            $userId, $invoiceNumber, $task['task_name'], 
            $subtotal, $taxRate, $taxAmount, $total
        ]);

        $invoiceId = $this->database->lastInsertId();

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'client_name' => $task['task_name'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => 'EUR',
            'items' => []
        ];
    }

    /**
     * Generate money invoice
     */
    private function generateMoneyInvoice(int $userId, int $moneyId): array
    {
        // Get money entry data
        $sql = "SELECT * FROM money_entries WHERE id = ? AND user_id = ?";
        $money = $this->database->queryOne($sql, [$moneyId, $userId]);
        
        if (!$money) {
            throw new \Exception('Money entry not found');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, ?, 'draft')";
        
        $subtotal = abs($money['amount']);
        $taxRate = 19.00;
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $this->database->execute($sql, [
            $userId, $invoiceNumber, $money['description'], 
            $subtotal, $taxRate, $taxAmount, $total, $money['currency']
        ]);

        $invoiceId = $this->database->lastInsertId();

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'client_name' => $money['description'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => $money['currency'],
            'items' => []
        ];
    }

    /**
     * Generate invoice number
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
     * Create PDF invoice
     */
    private function createPDF(array $invoice): TCPDF
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Billing Portal');
        $pdf->SetAuthor('Billing Portal');
        $pdf->SetTitle('Invoice ' . $invoice['invoice_number']);

        // Set default header data
        $pdf->SetHeaderData('', 0, 'INVOICE', $invoice['invoice_number']);

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Invoice content
        $html = $this->generateInvoiceHTML($invoice);
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf;
    }

    /**
     * Generate invoice HTML
     */
    private function generateInvoiceHTML(array $invoice): string
    {
        $html = '
        <table cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #ddd;">
            <tr>
                <td style="width: 50%;">
                    <h2>INVOICE</h2>
                    <strong>Invoice Number:</strong> ' . $invoice['invoice_number'] . '<br>
                    <strong>Date:</strong> ' . $invoice['invoice_date'] . '<br>
                    <strong>Due Date:</strong> ' . $invoice['due_date'] . '
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Bill To:</strong><br>
                    ' . htmlspecialchars($invoice['client_name']) . '<br>
                    ' . htmlspecialchars($invoice['client_address'] ?? '') . '<br>
                    ' . htmlspecialchars($invoice['client_email'] ?? '') . '
                </td>
            </tr>
        </table>
        
        <br><br>
        
        <table cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #ddd;">
            <tr style="background-color: #f5f5f5;">
                <th style="border: 1px solid #ddd; text-align: left;">Description</th>
                <th style="border: 1px solid #ddd; text-align: right;">Quantity</th>
                <th style="border: 1px solid #ddd; text-align: right;">Unit Price</th>
                <th style="border: 1px solid #ddd; text-align: right;">Total</th>
            </tr>';

        if (!empty($invoice['items'])) {
            foreach ($invoice['items'] as $item) {
                $html .= '
                <tr>
                    <td style="border: 1px solid #ddd;">' . htmlspecialchars($item['description']) . '</td>
                    <td style="border: 1px solid #ddd; text-align: right;">' . $item['quantity'] . '</td>
                    <td style="border: 1px solid #ddd; text-align: right;">' . number_format($item['unit_price'], 2) . '</td>
                    <td style="border: 1px solid #ddd; text-align: right;">' . number_format($item['total'], 2) . '</td>
                </tr>';
            }
        } else {
            $html .= '
            <tr>
                <td style="border: 1px solid #ddd;" colspan="4">' . htmlspecialchars($invoice['client_name']) . '</td>
            </tr>';
        }

        $html .= '
            <tr>
                <td colspan="3" style="border: 1px solid #ddd; text-align: right;"><strong>Subtotal:</strong></td>
                <td style="border: 1px solid #ddd; text-align: right;">' . number_format($invoice['subtotal'], 2) . '</td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid #ddd; text-align: right;"><strong>Tax (' . $invoice['tax_rate'] . '%):</strong></td>
                <td style="border: 1px solid #ddd; text-align: right;">' . number_format($invoice['tax_amount'], 2) . '</td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid #ddd; text-align: right;"><strong>Total:</strong></td>
                <td style="border: 1px solid #ddd; text-align: right;"><strong>' . number_format($invoice['total'], 2) . ' ' . $invoice['currency'] . '</strong></td>
            </tr>
        </table>';

        return $html;
    }

    /**
     * Get billing statistics
     */
    private function getBillingStats(int $userId): array
    {
        $stats = [];

        // Total invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['total_invoices'] = $result['total'] ?? 0;

        // Total amount
        $sql = "SELECT SUM(total) as total FROM invoices WHERE user_id = ?";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['total_amount'] = $result['total'] ?? 0;

        // Paid invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ? AND status = 'paid'";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['paid_invoices'] = $result['total'] ?? 0;

        // Pending amount
        $sql = "SELECT SUM(total) as total FROM invoices WHERE user_id = ? AND status IN ('draft', 'sent')";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['pending_amount'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get companies for API
     */
    private function getCompanies(int $userId): array
    {
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        return $this->database->query($sql, [$userId]);
    }

    /**
     * Get work entries for API
     */
    private function getWorkEntries(int $userId): array
    {
        $sql = "SELECT id, work_description, work_total, work_date FROM work_entries WHERE user_id = ? ORDER BY work_date DESC";
        return $this->database->query($sql, [$userId]);
    }

    /**
     * Get tours for API
     */
    private function getTours(int $userId): array
    {
        $sql = "SELECT id, tour_name, tour_total, tour_date FROM tours WHERE user_id = ? ORDER BY tour_date DESC";
        return $this->database->query($sql, [$userId]);
    }

    /**
     * Get tasks for API
     */
    private function getTasks(int $userId): array
    {
        $sql = "SELECT id, task_name, task_total FROM tasks WHERE user_id = ? ORDER BY created_at DESC";
        return $this->database->query($sql, [$userId]);
    }

    /**
     * Get money entries for API
     */
    private function getMoneyEntries(int $userId): array
    {
        $sql = "SELECT id, description, amount FROM money_entries WHERE user_id = ? ORDER BY payment_date DESC";
        return $this->database->query($sql, [$userId]);
    }

    /**
     * Save invoice
     */
    private function saveInvoice(int $userId, array $data): array
    {
        // Validate required fields
        if (empty($data['client_name']) || empty($data['total'])) {
            throw new \Exception('Missing required fields');
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber($userId);

        // Insert invoice
        $sql = "INSERT INTO invoices (user_id, invoice_number, invoice_date, due_date, client_name, 
                client_address, client_email, subtotal, tax_rate, tax_amount, total, currency, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')";
        
        $this->database->execute($sql, [
            $userId, $invoiceNumber, $data['invoice_date'] ?? date('Y-m-d'),
            $data['due_date'] ?? date('Y-m-d', strtotime('+30 days')),
            $data['client_name'], $data['client_address'] ?? '', $data['client_email'] ?? '',
            $data['subtotal'] ?? $data['total'], $data['tax_rate'] ?? 19.00,
            $data['tax_amount'] ?? 0, $data['total'], $data['currency'] ?? 'EUR'
        ]);

        $invoiceId = $this->database->lastInsertId();

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber
        ];
    }

    /**
     * Render a template
     */
    private function render(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../../templates/{$template}.php";
    }
} 