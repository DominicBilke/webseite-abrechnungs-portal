<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Money Controller - Handles money tracking and expenses
 */
class MoneyController extends BaseController
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
     * Show money index
     */
    public function index(): void
    {
        $this->overview();
    }

    /**
     * Show add money form
     */
    public function showAdd(): void
    {
        // Get companies for dropdown
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('money/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('money'),
            'companies' => $companies
        ]);
    }

    /**
     * Add new money entry
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $companyId = (int)($_POST['company_id'] ?? 0);
        $paymentDate = trim($_POST['payment_date'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $type = trim($_POST['type'] ?? 'income');
        $category = trim($_POST['category'] ?? '');

        if (empty($paymentDate) || $amount == 0) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /money/add');
            exit;
        }

        // Insert money entry
        $sql = "INSERT INTO money_entries (user_id, company_id, payment_date, amount, description, type, category) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->database->execute($sql, [
                $userId, $companyId, $paymentDate, $amount, $description, $type, $category
            ]);

            $this->session->setFlash('success', $this->localization->get('success_saved'));
            header('Location: /money/overview');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /money/add');
            exit;
        }
    }

    /**
     * Show money overview
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get money entries with company info and pagination
        $sql = "SELECT m.*, c.company_name FROM money_entries m 
                LEFT JOIN companies c ON m.company_id = c.id 
                WHERE m.user_id = ? ORDER BY m.payment_date DESC LIMIT ? OFFSET ?";
        $moneyEntries = $this->database->query($sql, [$userId, $limit, $offset]);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM money_entries WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        $this->render('money/overview', [
            'title' => $this->localization->get('money') . ' - ' . $this->localization->get('overview'),
            'moneyEntries' => $moneyEntries,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ]
        ]);
    }

    /**
     * Show money reports
     */
    public function reports(): void
    {
        $userId = $this->session->getUserId();
        
        // Get date range filters
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');

        // Get money statistics
        $stats = $this->getMoneyStats($userId, $fromDate, $toDate);
        
        // Get money entries with company info
        $moneyEntries = $this->getMoneyEntriesWithCompany($userId, $fromDate, $toDate);

        $this->render('money/reports', [
            'title' => $this->localization->get('money') . ' - ' . $this->localization->get('reports'),
            'stats' => $stats,
            'moneyEntries' => $moneyEntries,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    }

    /**
     * Get money statistics
     */
    private function getMoneyStats(int $userId, string $fromDate, string $toDate): array
    {
        $stats = [];

        // Total income
        $sql = "SELECT SUM(amount) as total FROM money_entries 
                WHERE user_id = ? AND type = 'income' AND payment_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_income'] = $result['total'] ?? 0;

        // Total expenses
        $sql = "SELECT SUM(amount) as total FROM money_entries 
                WHERE user_id = ? AND type = 'expense' AND payment_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_expenses'] = $result['total'] ?? 0;

        // Net amount
        $stats['net_amount'] = $stats['total_income'] - $stats['total_expenses'];

        // Total entries
        $sql = "SELECT COUNT(*) as total FROM money_entries 
                WHERE user_id = ? AND payment_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_entries'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get money entries with company info
     */
    private function getMoneyEntriesWithCompany(int $userId, string $fromDate, string $toDate): array
    {
        $sql = "SELECT m.*, c.company_name FROM money_entries m 
                LEFT JOIN companies c ON m.company_id = c.id 
                WHERE m.user_id = ? AND m.payment_date BETWEEN ? AND ?
                ORDER BY m.payment_date DESC";
        
        return $this->database->query($sql, [$userId, $fromDate, $toDate]);
    }

    /**
     * View money entry details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get money entry with company info
        $sql = "SELECT m.*, c.company_name FROM money_entries m 
                LEFT JOIN companies c ON m.company_id = c.id 
                WHERE m.id = ? AND m.user_id = ?";
        $moneyEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$moneyEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        $this->render('money/view', [
            'title' => $this->localization->get('money') . ' - ' . $moneyEntry['payment_date'],
            'moneyEntry' => $moneyEntry
        ]);
    }

    /**
     * Show edit money form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get money entry
        $sql = "SELECT * FROM money_entries WHERE id = ? AND user_id = ?";
        $moneyEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$moneyEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        // Get companies for dropdown
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('money/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('money'),
            'moneyEntry' => $moneyEntry,
            'companies' => $companies
        ]);
    }

    /**
     * Update money entry
     */
    public function update(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $companyId = (int)($_POST['company_id'] ?? 0);
        $paymentDate = trim($_POST['payment_date'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $type = trim($_POST['type'] ?? 'income');
        $category = trim($_POST['category'] ?? '');

        if (empty($paymentDate) || $amount == 0) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /money/edit/' . $id);
            exit;
        }

        // Update money entry
        $sql = "UPDATE money_entries SET company_id = ?, payment_date = ?, amount = ?, 
                description = ?, type = ?, category = ? WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [
                $companyId, $paymentDate, $amount, $description, $type, $category, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /money/view/' . $id);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /money/edit/' . $id);
            exit;
        }
    }

    /**
     * Delete money entry
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if money entry exists and belongs to user
        $sql = "SELECT id FROM money_entries WHERE id = ? AND user_id = ?";
        $moneyEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$moneyEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        // Delete money entry
        try {
            $sql = "DELETE FROM money_entries WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /money/overview');
        exit;
    }

    /**
     * Search money entries
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT m.id, m.payment_date, m.amount, m.description, m.type, c.company_name 
                FROM money_entries m 
                LEFT JOIN companies c ON m.company_id = c.id 
                WHERE m.user_id = ? AND (m.description LIKE ? OR c.company_name LIKE ?)
                ORDER BY m.payment_date DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }
} 