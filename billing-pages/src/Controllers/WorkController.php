<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Work Controller - Handles work billing operations
 */
class WorkController
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
     * Show work billing index
     */
    public function index(): void
    {
        $this->overview();
    }

    /**
     * Show add work form
     */
    public function showAdd(): void
    {
        $this->render('work/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('work'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization
        ]);
    }

    /**
     * Add new work entry
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $workDate = trim($_POST['work_date'] ?? '');
        $workHours = (float)($_POST['work_hours'] ?? 0);
        $workDescription = trim($_POST['work_description'] ?? '');
        $workType = trim($_POST['work_type'] ?? '');
        $workRate = (float)($_POST['work_rate'] ?? 0);
        $workProject = trim($_POST['work_project'] ?? '');
        $workClient = trim($_POST['work_client'] ?? '');

        if (empty($workDate) || $workHours <= 0) {
            $this->session->setFlash('error', $this->localization->get('validation_required', ['field' => $this->localization->get('work_date') . '/' . $this->localization->get('work_hours')]));
            header('Location: /work/add');
            exit;
        }

        // Calculate total
        $workTotal = $workHours * $workRate;

        // Insert work entry
        $sql = "INSERT INTO work_entries (user_id, work_date, work_hours, work_description, work_type, 
                work_rate, work_total, work_project, work_client, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        try {
            $this->database->execute($sql, [
                $userId, $workDate, $workHours, $workDescription, $workType,
                $workRate, $workTotal, $workProject, $workClient
            ]);

            $this->session->setFlash('success', $this->localization->get('success_saved'));
            header('Location: /work/overview');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /work/add');
            exit;
        }
    }

    /**
     * Show work overview
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get filters
        $month = $_GET['month'] ?? '';
        $project = $_GET['project'] ?? '';
        $status = $_GET['status'] ?? '';

        // Build WHERE clause
        $whereConditions = ['user_id = ?'];
        $params = [$userId];

        if (!empty($month) && $month !== 'all') {
            $whereConditions[] = 'DATE_FORMAT(work_date, "%Y-%m") = ?';
            $params[] = $month;
        }

        if (!empty($project) && $project !== 'all') {
            $whereConditions[] = 'work_project = ?';
            $params[] = $project;
        }

        if (!empty($status) && $status !== 'all') {
            $whereConditions[] = 'status = ?';
            $params[] = $status;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Get work entries with pagination
        $sql = "SELECT * FROM work_entries WHERE {$whereClause} ORDER BY work_date DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $workEntries = $this->database->query($sql, $params);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM work_entries WHERE {$whereClause}";
        $totalResult = $this->database->queryOne($countSql, array_slice($params, 0, -2));
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        // Get available months and projects for filters
        $months = $this->getAvailableMonths($userId);
        $projects = $this->getAvailableProjects($userId);

        $this->render('work/overview', [
            'title' => $this->localization->get('work') . ' - ' . $this->localization->get('overview'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'workEntries' => $workEntries,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ],
            'filters' => [
                'month' => $month,
                'project' => $project,
                'status' => $status,
                'months' => $months,
                'projects' => $projects
            ]
        ]);
    }

    /**
     * Show work reports
     */
    public function reports(): void
    {
        $userId = $this->session->getUserId();
        
        // Get date range filters
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');
        $project = $_GET['project'] ?? '';

        // Get work statistics
        $stats = $this->getWorkStats($userId, $fromDate, $toDate, $project);
        
        // Get work entries for reporting
        $workEntries = $this->getWorkEntriesForReport($userId, $fromDate, $toDate, $project);

        // Get projects for filter
        $projects = $this->getAvailableProjects($userId);

        $this->render('work/reports', [
            'title' => $this->localization->get('work') . ' - ' . $this->localization->get('reports'),
            'locale' => $this->localization->getLocale(),
            'localization' => $this->localization,
            'stats' => $stats,
            'workEntries' => $workEntries,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'project' => $project,
                'projects' => $projects
            ]
        ]);
    }

    /**
     * Get work statistics
     */
    private function getWorkStats(int $userId, string $fromDate, string $toDate, string $project = ''): array
    {
        $stats = [];

        // Build WHERE clause
        $whereConditions = ['user_id = ?', 'work_date BETWEEN ? AND ?'];
        $params = [$userId, $fromDate, $toDate];

        if (!empty($project)) {
            $whereConditions[] = 'work_project = ?';
            $params[] = $project;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Total hours
        $sql = "SELECT SUM(work_hours) as total FROM work_entries WHERE {$whereClause}";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_hours'] = $result['total'] ?? 0;

        // Total earnings
        $sql = "SELECT SUM(work_total) as total FROM work_entries WHERE {$whereClause}";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_earnings'] = $result['total'] ?? 0;

        // Average hourly rate
        $sql = "SELECT AVG(work_rate) as avg_rate FROM work_entries WHERE {$whereClause} AND work_rate > 0";
        $result = $this->database->queryOne($sql, $params);
        $stats['avg_hourly_rate'] = $result['avg_rate'] ?? 0;

        // Total entries
        $sql = "SELECT COUNT(*) as total FROM work_entries WHERE {$whereClause}";
        $result = $this->database->queryOne($sql, $params);
        $stats['total_entries'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get work entries for reporting
     */
    private function getWorkEntriesForReport(int $userId, string $fromDate, string $toDate, string $project = ''): array
    {
        $whereConditions = ['user_id = ?', 'work_date BETWEEN ? AND ?'];
        $params = [$userId, $fromDate, $toDate];

        if (!empty($project)) {
            $whereConditions[] = 'work_project = ?';
            $params[] = $project;
        }

        $whereClause = implode(' AND ', $whereConditions);

        $sql = "SELECT * FROM work_entries WHERE {$whereClause} ORDER BY work_date DESC";
        return $this->database->query($sql, $params);
    }

    /**
     * Get available months for filtering
     */
    private function getAvailableMonths(int $userId): array
    {
        $sql = "SELECT DISTINCT DATE_FORMAT(work_date, '%Y-%m') as month 
                FROM work_entries WHERE user_id = ? ORDER BY month DESC";
        $months = $this->database->query($sql, [$userId]);
        
        $result = [];
        foreach ($months as $month) {
            $date = \DateTime::createFromFormat('Y-m', $month['month']);
            $result[$month['month']] = $date->format('F Y');
        }
        
        return $result;
    }

    /**
     * Get available projects for filtering
     */
    private function getAvailableProjects(int $userId): array
    {
        $sql = "SELECT DISTINCT work_project FROM work_entries 
                WHERE user_id = ? AND work_project IS NOT NULL AND work_project != '' 
                ORDER BY work_project";
        $projects = $this->database->query($sql, [$userId]);
        
        $result = [];
        foreach ($projects as $project) {
            $result[$project['work_project']] = $project['work_project'];
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
} 