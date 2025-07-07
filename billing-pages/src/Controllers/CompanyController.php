<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Company Controller - Handles company billing operations
 */
class CompanyController
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
     * Show company billing index
     */
    public function index(): void
    {
        $this->overview();
    }

    /**
     * Show add company form
     */
    public function showAdd(): void
    {
        $this->render('company/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('company'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization
        ]);
    }

    /**
     * Add new company
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $companyName = trim($_POST['company_name'] ?? '');
        $companyAddress = trim($_POST['company_address'] ?? '');
        $companyPhone = trim($_POST['company_phone'] ?? '');
        $companyEmail = trim($_POST['company_email'] ?? '');
        $companyContact = trim($_POST['company_contact'] ?? '');
        $companyVat = trim($_POST['company_vat'] ?? '');
        $companyRegistration = trim($_POST['company_registration'] ?? '');
        $companyBank = trim($_POST['company_bank'] ?? '');

        if (empty($companyName)) {
            $this->session->setFlash('error', $this->localization->get('validation_required', ['field' => $this->localization->get('company_name')]));
            header('Location: /company/add');
            exit;
        }

        // Insert company
        $sql = "INSERT INTO companies (user_id, company_name, company_address, company_phone, company_email, 
                company_contact, company_vat, company_registration, company_bank, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
        
        try {
            $this->database->execute($sql, [
                $userId, $companyName, $companyAddress, $companyPhone, $companyEmail,
                $companyContact, $companyVat, $companyRegistration, $companyBank
            ]);

            $this->session->setFlash('success', $this->localization->get('success_saved'));
            header('Location: /company/overview');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /company/add');
            exit;
        }
    }

    /**
     * Show company overview
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get companies with pagination
        $sql = "SELECT * FROM companies WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $companies = $this->database->query($sql, [$userId, $limit, $offset]);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM companies WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        $this->render('company/overview', [
            'title' => $this->localization->get('companies') . ' - ' . $this->localization->get('overview'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'companies' => $companies,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ]
        ]);
    }

    /**
     * Show company reports
     */
    public function reports(): void
    {
        $userId = $this->session->getUserId();
        
        // Get date range filters
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');

        // Get company statistics
        $stats = $this->getCompanyStats($userId, $fromDate, $toDate);
        
        // Get companies with their billing data
        $companies = $this->getCompaniesWithBilling($userId, $fromDate, $toDate);

        $this->render('company/reports', [
            'title' => $this->localization->get('companies') . ' - ' . $this->localization->get('reports'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'stats' => $stats,
            'companies' => $companies,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    }

    /**
     * Get company statistics
     */
    private function getCompanyStats(int $userId, string $fromDate, string $toDate): array
    {
        $stats = [];

        // Total companies
        $sql = "SELECT COUNT(*) as total FROM companies WHERE user_id = ? AND created_at BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_companies'] = $result['total'] ?? 0;

        // Active companies
        $sql = "SELECT COUNT(*) as total FROM companies WHERE user_id = ? AND status = 'active' AND created_at BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['active_companies'] = $result['total'] ?? 0;

        // Companies with billing
        $sql = "SELECT COUNT(DISTINCT c.id) as total FROM companies c 
                INNER JOIN invoices i ON c.id = i.client_id 
                WHERE c.user_id = ? AND i.invoice_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['companies_with_billing'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get companies with their billing data
     */
    private function getCompaniesWithBilling(int $userId, string $fromDate, string $toDate): array
    {
        $sql = "SELECT c.*, 
                COUNT(i.id) as invoice_count,
                SUM(i.total) as total_billing,
                AVG(i.total) as avg_billing
                FROM companies c
                LEFT JOIN invoices i ON c.id = i.client_id AND i.invoice_date BETWEEN ? AND ?
                WHERE c.user_id = ?
                GROUP BY c.id
                ORDER BY total_billing DESC";
        
        return $this->database->query($sql, [$fromDate, $toDate, $userId]);
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