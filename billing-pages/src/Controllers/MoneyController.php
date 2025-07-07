<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Money Controller - Handles money billing operations
 */
class MoneyController
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
     * Show money billing index
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
        $this->render('money/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('money'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization
        ]);
    }

    /**
     * Add new money entry
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $amount = (float)($_POST['amount'] ?? 0);
        $currency = trim($_POST['currency'] ?? 'EUR');
        $description = trim($_POST['description'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? '');
        $paymentDate = trim($_POST['payment_date'] ?? date('Y-m-d'));
        $paymentStatus = trim($_POST['payment_status'] ?? 'pending');
        $category = trim($_POST['category'] ?? '');
        $reference = trim($_POST['reference'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($amount == 0 || empty($description)) {
            $this->session->setFlash('error', $this->localization->get('validation_required', ['field' => $this->localization->get('amount') . '/' . $this->localization->get('description')]));
            header('Location: /money/add');
            exit;
        }

        // Insert money entry
        $sql = "INSERT INTO money_entries (user_id, amount, currency, description, payment_method, 
                payment_date, payment_status, category, reference, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->database->execute($sql, [
                $userId, $amount, $currency, $description, $paymentMethod,
                $paymentDate, $paymentStatus, $category, $reference, $notes
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

        // Get filters
        $month = $_GET['month'] ?? '';
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';

        // Build WHERE clause
        $whereConditions = ['user_id = ?'];
        $params = [$userId];

        if (!empty($month) && $month !== 'all') {
            $whereConditions[] = 'DATE_FORMAT(payment_date, "%Y-%m") = ?';
            $params[] = $month;
        }

        if (!empty($category) && $category !== 'all') {
            $whereConditions[] = 'category = ?';
            $params[] = $category;
        }

        if (!empty($status) && $status !== 'all') {
            $whereConditions[] = 'payment_status = ?';
            $params[] = $status;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Get money entries with pagination
        $sql = "SELECT * FROM money_entries WHERE {$whereClause} ORDER BY payment_date DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $moneyEntries = $this->database->query($sql, $params);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM money_entries WHERE {$whereClause}";
        $totalResult = $this->database->queryOne($countSql, array_slice($params, 0, -2));
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        // Get available months and categories for filters
        $months = $this->getAvailableMonths($userId);
        $categories = $this->getAvailableCategories($userId);

        $this->render('money/overview', [
            'title' => $this->localization->get('money') . ' - ' . $this->localization->get('overview'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'moneyEntries' => $moneyEntries,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ],
            'filters' => [
                'month' => $month,
                'category' => $category,
                'status' => $status,
                'months' => $months,
                'categories' => $categories
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
        $category = $_GET['category'] ?? '';

        // Get money statistics
        $stats = $this->getMoneyStats($userId, $fromDate, $toDate, $category);
        
        // Get money entries for reporting
        $moneyEntries = $this->getMoneyEntriesForReport($userId, $fromDate, $toDate, $category);

        // Get categories for filter
        $categories = $this->getAvailableCategories($userId);

        $this->render('money/reports', [
            'title' => $this->localization->get('money') . ' - ' . $this->localization->get('reports'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'stats' => $stats,
            'moneyEntries' => $moneyEntries,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'category' => $category,
                'categories' => $categories
            ]
        ]);
    }

    /**
     * Get money statistics
     */
    private function getMoneyStats(int $userId, string $fromDate, string $toDate, string $category = ''): array
    {
        $stats = [];

        // Build WHERE clause
        $whereConditions = ['user_id = ?', 'payment_date BETWEEN ? AND ?'];
        $params = [$userId, $fromDate, $toDate];

        if (!empty($category)) {
            $whereConditions[] = 'category = ?';
            $params[] = $category;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Total income
        $sql = "SELECT SUM(amount) as total FROM money_entries WHERE {$whereClause} AND amount > 0";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_income'] = $result['total'] ?? 0;

        // Total expenses
        $sql = "SELECT SUM(ABS(amount)) as total FROM money_entries WHERE {$whereClause} AND amount < 0";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_expenses'] = $result['total'] ?? 0;

        // Net amount
        $sql = "SELECT SUM(amount) as total FROM money_entries WHERE {$whereClause}";
        $result = $this->database->queryOne($sql, $params);
        $stats['net_amount'] = $result['total'] ?? 0;

        // Pending amount
        $sql = "SELECT SUM(amount) as total FROM money_entries WHERE {$whereClause} AND payment_status = 'pending'";
        $result = $this->database->queryOne($sql, $params);
        $stats['pending_amount'] = $result['total'] ?? 0;

        // Total entries
        $sql = "SELECT COUNT(*) as total FROM money_entries WHERE {$whereClause}";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_entries'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get money entries for reporting
     */
    private function getMoneyEntriesForReport(int $userId, string $fromDate, string $toDate, string $category = ''): array
    {
        $whereConditions = ['user_id = ?', 'payment_date BETWEEN ? AND ?'];
        $params = [$userId, $fromDate, $toDate];

        if (!empty($category)) {
            $whereConditions[] = 'category = ?';
            $params[] = $category;
        }

        $whereClause = implode(' AND ', $whereConditions);

        $sql = "SELECT * FROM money_entries WHERE {$whereClause} ORDER BY payment_date DESC";
        return $this->database->query($sql, $params);
    }

    /**
     * Get available months for filtering
     */
    private function getAvailableMonths(int $userId): array
    {
        $sql = "SELECT DISTINCT DATE_FORMAT(payment_date, '%Y-%m') as month 
                FROM money_entries WHERE user_id = ? ORDER BY month DESC";
        $months = $this->database->query($sql, [$userId]);
        
        $result = [];
        foreach ($months as $month) {
            $date = \DateTime::createFromFormat('Y-m', $month['month']);
            $result[$month['month']] = $date->format('F Y');
        }
        
        return $result;
    }

    /**
     * Get available categories for filtering
     */
    private function getAvailableCategories(int $userId): array
    {
        $sql = "SELECT DISTINCT category FROM money_entries 
                WHERE user_id = ? AND category IS NOT NULL AND category != '' 
                ORDER BY category";
        $categories = $this->database->query($sql, [$userId]);
        
        $result = [];
        foreach ($categories as $category) {
            $result[$category['category']] = $category['category'];
        }
        
        return $result;
    }

    /**
     * Render a template
     */
    private function render(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../../templates/{$template}.php";
    }

    /**
     * Show money entry details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get money entry details
        $sql = "SELECT * FROM money_entries WHERE id = ? AND user_id = ?";
        $money = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$money) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        $this->render('money/view', [
            'title' => $money['description'],
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'money' => $money
        ]);
    }

    /**
     * Show edit money form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get money entry details
        $sql = "SELECT * FROM money_entries WHERE id = ? AND user_id = ?";
        $money = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$money) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        $this->render('money/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('money'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'money' => $money
        ]);
    }

    /**
     * Update money entry
     */
    public function update(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $amount = (float)($_POST['amount'] ?? 0);
        $currency = trim($_POST['currency'] ?? 'EUR');
        $description = trim($_POST['description'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? '');
        $paymentDate = trim($_POST['payment_date'] ?? date('Y-m-d'));
        $paymentStatus = trim($_POST['payment_status'] ?? 'pending');
        $category = trim($_POST['category'] ?? '');
        $reference = trim($_POST['reference'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($amount == 0 || empty($description)) {
            $this->session->setFlash('error', $this->localization->get('validation_required', ['field' => $this->localization->get('amount') . '/' . $this->localization->get('description')]));
            header('Location: /money/edit/' . $id);
            exit;
        }

        // Update money entry
        $sql = "UPDATE money_entries SET amount = ?, currency = ?, description = ?, payment_method = ?, 
                payment_date = ?, payment_status = ?, category = ?, reference = ?, notes = ?, 
                updated_at = NOW() WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [
                $amount, $currency, $description, $paymentMethod, $paymentDate,
                $paymentStatus, $category, $reference, $notes, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /money/overview');
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
        $money = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$money) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /money/overview');
            exit;
        }

        // Delete money entry
        $sql = "DELETE FROM money_entries WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [$id, $userId]);
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /money/overview');
        exit;
    }

    /**
     * Search money entries via API
     */
    public function search(): void
    {
        header('Content-Type: application/json');
        
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            echo json_encode(['success' => true, 'data' => []]);
            return;
        }

        $sql = "SELECT id, description, amount, payment_date, category, payment_status 
                FROM money_entries 
                WHERE user_id = ? AND (description LIKE ? OR category LIKE ? OR reference LIKE ?)
                ORDER BY payment_date DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $moneyEntries = $this->database->query($sql, [$userId, $searchTerm, $searchTerm, $searchTerm]);
        
        echo json_encode(['success' => true, 'data' => $moneyEntries]);
    }
} 