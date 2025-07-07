<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Dashboard Controller
 */
class DashboardController extends BaseController
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
     * Show dashboard
     */
    public function index(): void
    {
        $userId = $this->session->getUserId();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($userId);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($userId);
        
        // Get chart data
        $chartData = $this->getChartData($userId);

        $this->render('dashboard/index', [
            'title' => $this->localization->get('dashboard'),
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'chartData' => $chartData
        ]);
    }

    /**
     * Redirect to dashboard (for placeholder routes)
     */
    public function redirectToDashboard(): void
    {
        header('Location: /dashboard');
        exit;
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(int $userId): array
    {
        $stats = [];

        // Total companies
        $sql = "SELECT COUNT(*) as total FROM companies WHERE user_id = ?";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['total_companies'] = $result['total'] ?? 0;

        // Total work hours this month
        $sql = "SELECT SUM(work_hours) as total FROM work_entries 
                WHERE user_id = ? AND DATE_FORMAT(work_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['work_hours_month'] = $result['total'] ?? 0;

        // Total earnings this month
        $sql = "SELECT SUM(work_total) as total FROM work_entries 
                WHERE user_id = ? AND DATE_FORMAT(work_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['earnings_month'] = $result['total'] ?? 0;

        // Pending invoices
        $sql = "SELECT COUNT(*) as total FROM invoices WHERE user_id = ? AND status IN ('draft', 'sent')";
        $result = $this->database->queryOne($sql, [$userId]);
        $stats['pending_invoices'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities(int $userId): array
    {
        $activities = [];

        // Recent work entries
        $sql = "SELECT 'work' as type, work_date as date, work_description as description, work_total as amount 
                FROM work_entries WHERE user_id = ? ORDER BY work_date DESC LIMIT 5";
        $workEntries = $this->database->query($sql, [$userId]);
        $activities = array_merge($activities, $workEntries);

        // Recent money entries
        $sql = "SELECT 'money' as type, payment_date as date, description, amount 
                FROM money_entries WHERE user_id = ? ORDER BY payment_date DESC LIMIT 5";
        $moneyEntries = $this->database->query($sql, [$userId]);
        $activities = array_merge($activities, $moneyEntries);

        // Sort by date and limit to 10
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Get chart data
     */
    private function getChartData(int $userId): array
    {
        $chartData = [];

        // Monthly earnings for the last 12 months
        $sql = "SELECT DATE_FORMAT(work_date, '%Y-%m') as month, SUM(work_total) as total 
                FROM work_entries 
                WHERE user_id = ? AND work_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(work_date, '%Y-%m')
                ORDER BY month";
        $monthlyEarnings = $this->database->query($sql, [$userId]);
        
        $chartData['monthly_earnings'] = $monthlyEarnings;

        // Work hours by type
        $sql = "SELECT work_type, SUM(work_hours) as total 
                FROM work_entries 
                WHERE user_id = ? AND work_type IS NOT NULL
                GROUP BY work_type
                ORDER BY total DESC";
        $workByType = $this->database->query($sql, [$userId]);
        
        $chartData['work_by_type'] = $workByType;

        return $chartData;
    }

    /**
     * Get stats via API
     */
    public function getStats(): void
    {
        header('Content-Type: application/json');
        
        $userId = $this->session->getUserId();
        $stats = $this->getDashboardStats($userId);
        
        echo json_encode(['success' => true, 'data' => $stats]);
    }

    /**
     * Get chart data via API
     */
    public function getChartDataApi(): void
    {
        header('Content-Type: application/json');
        
        $userId = $this->session->getUserId();
        $chartData = $this->getChartData($userId);
        
        echo json_encode(['success' => true, 'data' => $chartData]);
    }
} 