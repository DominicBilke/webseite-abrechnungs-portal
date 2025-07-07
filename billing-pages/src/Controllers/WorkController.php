<?php

namespace BillingPages\Controllers;

/**
 * Work Controller - Handles work time tracking and billing
 */
class WorkController extends BaseController
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
     * Show work index
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
        // Get companies for dropdown
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('work/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('work'),
            'companies' => $companies
        ]);
    }

    /**
     * Add new work entry
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $companyId = (int)($_POST['company_id'] ?? 0);
        $workDate = trim($_POST['work_date'] ?? '');
        $workHours = (float)($_POST['work_hours'] ?? 0);
        $workRate = (float)($_POST['work_rate'] ?? 0);
        $workDescription = trim($_POST['work_description'] ?? '');
        $workType = trim($_POST['work_type'] ?? '');

        if (empty($companyId) || empty($workDate) || $workHours <= 0) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /work/add');
            exit;
        }

        // Calculate total
        $workTotal = $workHours * $workRate;

        // Insert work entry
        $sql = "INSERT INTO work_entries (user_id, company_id, work_date, work_hours, work_rate, 
                work_total, work_description, work_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->database->execute($sql, [
                $userId, $companyId, $workDate, $workHours, $workRate, 
                $workTotal, $workDescription, $workType
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

        // Get work entries with company info and pagination
        $sql = "SELECT w.*, c.company_name FROM work_entries w 
                LEFT JOIN companies c ON w.company_id = c.id 
                WHERE w.user_id = ? ORDER BY w.work_date DESC LIMIT ? OFFSET ?";
        $workEntries = $this->database->query($sql, [$userId, $limit, $offset]);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM work_entries WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        $this->render('work/overview', [
            'title' => $this->localization->get('work') . ' - ' . $this->localization->get('overview'),
            'workEntries' => $workEntries,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
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

        // Get work statistics
        $stats = $this->getWorkStats($userId, $fromDate, $toDate);
        
        // Get work entries with company info
        $workEntries = $this->getWorkEntriesWithCompany($userId, $fromDate, $toDate);

        $this->render('work/reports', [
            'title' => $this->localization->get('work') . ' - ' . $this->localization->get('reports'),
            'stats' => $stats,
            'workEntries' => $workEntries,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    }

    /**
     * Get work statistics
     */
    private function getWorkStats(int $userId, string $fromDate, string $toDate): array
    {
        $stats = [];

        // Total work hours
        $sql = "SELECT SUM(work_hours) as total FROM work_entries 
                WHERE user_id = ? AND work_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_hours'] = $result['total'] ?? 0;

        // Total earnings
        $sql = "SELECT SUM(work_total) as total FROM work_entries 
                WHERE user_id = ? AND work_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_earnings'] = $result['total'] ?? 0;

        // Average hourly rate
        $sql = "SELECT AVG(work_rate) as avg_rate FROM work_entries 
                WHERE user_id = ? AND work_date BETWEEN ? AND ? AND work_rate > 0";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['avg_hourly_rate'] = $result['avg_rate'] ?? 0;

        // Work entries count
        $sql = "SELECT COUNT(*) as total FROM work_entries 
                WHERE user_id = ? AND work_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_entries'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get work entries with company info
     */
    private function getWorkEntriesWithCompany(int $userId, string $fromDate, string $toDate): array
    {
        $sql = "SELECT w.*, c.company_name FROM work_entries w 
                LEFT JOIN companies c ON w.company_id = c.id 
                WHERE w.user_id = ? AND w.work_date BETWEEN ? AND ?
                ORDER BY w.work_date DESC";
        
        return $this->database->query($sql, [$userId, $fromDate, $toDate]);
    }

    /**
     * View work entry details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get work entry with company info
        $sql = "SELECT w.*, c.company_name FROM work_entries w 
                LEFT JOIN companies c ON w.company_id = c.id 
                WHERE w.id = ? AND w.user_id = ?";
        $workEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$workEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /work/overview');
            exit;
        }

        $this->render('work/view', [
            'title' => $this->localization->get('work') . ' - ' . $workEntry['work_date'],
            'workEntry' => $workEntry
        ]);
    }

    /**
     * Show edit work form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get work entry
        $sql = "SELECT * FROM work_entries WHERE id = ? AND user_id = ?";
        $workEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$workEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /work/overview');
            exit;
        }

        // Get companies for dropdown
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('work/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('work'),
            'workEntry' => $workEntry,
            'companies' => $companies
        ]);
    }

    /**
     * Update work entry
     */
    public function update(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $companyId = (int)($_POST['company_id'] ?? 0);
        $workDate = trim($_POST['work_date'] ?? '');
        $workHours = (float)($_POST['work_hours'] ?? 0);
        $workRate = (float)($_POST['work_rate'] ?? 0);
        $workDescription = trim($_POST['work_description'] ?? '');
        $workType = trim($_POST['work_type'] ?? '');

        if (empty($companyId) || empty($workDate) || $workHours <= 0) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /work/edit/' . $id);
            exit;
        }

        // Calculate total
        $workTotal = $workHours * $workRate;

        // Update work entry
        $sql = "UPDATE work_entries SET company_id = ?, work_date = ?, work_hours = ?, 
                work_rate = ?, work_total = ?, work_description = ?, work_type = ? 
                WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [
                $companyId, $workDate, $workHours, $workRate, $workTotal, 
                $workDescription, $workType, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /work/view/' . $id);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /work/edit/' . $id);
            exit;
        }
    }

    /**
     * Delete work entry
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if work entry exists and belongs to user
        $sql = "SELECT id FROM work_entries WHERE id = ? AND user_id = ?";
        $workEntry = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$workEntry) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /work/overview');
            exit;
        }

        // Delete work entry
        try {
            $sql = "DELETE FROM work_entries WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /work/overview');
        exit;
    }

    /**
     * Search work entries
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT w.id, w.work_date, w.work_hours, w.work_total, w.work_description, c.company_name 
                FROM work_entries w 
                LEFT JOIN companies c ON w.company_id = c.id 
                WHERE w.user_id = ? AND (w.work_description LIKE ? OR c.company_name LIKE ?)
                ORDER BY w.work_date DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }
} 