<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Company Controller - Handles company billing operations
 */
class CompanyController extends BaseController
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
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('company')
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
     * View company details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get company details
        $sql = "SELECT * FROM companies WHERE id = ? AND user_id = ?";
        $company = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$company) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /company/overview');
            exit;
        }

        // Get related invoices
        $sql = "SELECT * FROM invoices WHERE client_id = ? ORDER BY invoice_date DESC LIMIT 10";
        $invoices = $this->database->query($sql, [$id]);

        $this->render('company/view', [
            'title' => $company['company_name'],
            'company' => $company,
            'invoices' => $invoices
        ]);
    }

    /**
     * Show edit company form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get company details
        $sql = "SELECT * FROM companies WHERE id = ? AND user_id = ?";
        $company = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$company) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /company/overview');
            exit;
        }

        $this->render('company/edit', [
            'title' => $this->localization->get('edit') . ' ' . $company['company_name'],
            'company' => $company
        ]);
    }

    /**
     * Update company
     */
    public function update(int $id): void
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
        $status = trim($_POST['status'] ?? 'active');

        if (empty($companyName)) {
            $this->session->setFlash('error', $this->localization->get('validation_required', ['field' => $this->localization->get('company_name')]));
            header('Location: /company/edit/' . $id);
            exit;
        }

        // Update company
        $sql = "UPDATE companies SET company_name = ?, company_address = ?, company_phone = ?, 
                company_email = ?, company_contact = ?, company_vat = ?, company_registration = ?, 
                company_bank = ?, status = ? WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [
                $companyName, $companyAddress, $companyPhone, $companyEmail,
                $companyContact, $companyVat, $companyRegistration, $companyBank,
                $status, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /company/view/' . $id);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /company/edit/' . $id);
            exit;
        }
    }

    /**
     * Delete company
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if company exists and belongs to user
        $sql = "SELECT id FROM companies WHERE id = ? AND user_id = ?";
        $company = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$company) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /company/overview');
            exit;
        }

        // Check if company has related invoices
        $sql = "SELECT COUNT(*) as count FROM invoices WHERE client_id = ?";
        $result = $this->database->queryOne($sql, [$id]);
        
        if ($result['count'] > 0) {
            $this->session->setFlash('error', $this->localization->get('error_delete') . ' - ' . $this->localization->get('company_has_invoices'));
            header('Location: /company/overview');
            exit;
        }

        // Delete company
        try {
            $sql = "DELETE FROM companies WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /company/overview');
        exit;
    }

    /**
     * Search companies
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT id, company_name, company_contact, company_email 
                FROM companies 
                WHERE user_id = ? AND (company_name LIKE ? OR company_contact LIKE ? OR company_email LIKE ?)
                ORDER BY company_name 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm, $searchTerm, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }
} 