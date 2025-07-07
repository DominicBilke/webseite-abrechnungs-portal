<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Dashboard Controller
 */
class DashboardController
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
     * Show dashboard
     */
    public function index(): void
    {
        $userId = $this->session->getUserId();
        $userRole = $this->session->getUserRole();

        // Get dashboard statistics
        $stats = $this->getDashboardStats($userId, $userRole);
        
        // Get recent entries
        $recentEntries = $this->getRecentEntries($userId, $userRole);

        $this->render('dashboard/index', [
            'title' => $this->localization->get('dashboard'),
            'locale' => $this->localization->getLocale(),
            'stats' => $stats,
            'recentEntries' => $recentEntries,
            'user' => [
                'username' => $this->session->getUsername(),
                'role' => $userRole,
                'domain' => $this->session->getDomain()
            ]
        ]);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(int $userId, string $userRole): array
    {
        $stats = [];

        // Get total revenue
        $sql = "SELECT SUM(amount) as total FROM money_entries WHERE user_id = ?";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['total_revenue'] = $result['total'] ?? 0;

        // Get total invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ?";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['total_invoices'] = $result['total'] ?? 0;

        // Get pending amount
        $sql = "SELECT SUM(amount) as total FROM money_entries WHERE user_id = ? AND status = 'pending'";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['pending_amount'] = $result['total'] ?? 0;

        // Get overdue invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ? AND due_date < CURDATE() AND status != 'paid'";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['overdue_invoices'] = $result['total'] ?? 0;

        // Get pending tasks
        $sql = "SELECT COUNT(*) as total FROM tasks WHERE user_id = ? AND status = 'pending'";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['pending_tasks'] = $result['total'] ?? 0;

        // Get recent companies
        $sql = "SELECT COUNT(*) as total FROM companies WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['recent_companies'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get recent entries for dashboard
     */
    private function getRecentEntries(int $userId, string $userRole): array
    {
        $entries = [];

        // Get recent money entries
        $sql = "SELECT id, amount, description, created_at, 'money' as type FROM money_entries 
                WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
        $moneyEntries = $this->database->query($sql, [$userId]);
        $entries = array_merge($entries, $moneyEntries);

        // Get recent work entries
        $sql = "SELECT id, work_hours as amount, work_description as description, work_date as created_at, 'work' as type 
                FROM work_entries WHERE user_id = ? ORDER BY work_date DESC LIMIT 5";
        $workEntries = $this->database->query($sql, [$userId]);
        $entries = array_merge($entries, $workEntries);

        // Get recent task entries
        $sql = "SELECT id, 0 as amount, task_name as description, created_at, 'task' as type 
                FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
        $taskEntries = $this->database->query($sql, [$userId]);
        $entries = array_merge($entries, $taskEntries);

        // Sort by creation date and limit to 10
        usort($entries, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($entries, 0, 10);
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