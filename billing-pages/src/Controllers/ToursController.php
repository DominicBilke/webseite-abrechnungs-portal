<?php

namespace BillingPages\Controllers;

/**
 * Tours Controller - Handles tour management and billing
 */
class ToursController extends BaseController
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
     * Show tours index
     */
    public function index(): void
    {
        $this->overview();
    }

    /**
     * Show add tour form
     */
    public function showAdd(): void
    {
        // Get companies for dropdown
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('tours/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('tour'),
            'companies' => $companies
        ]);
    }

    /**
     * Add new tour
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $tourName = trim($_POST['tour_name'] ?? '');
        $tourDate = trim($_POST['tour_date'] ?? '');
        $tourStart = trim($_POST['tour_start'] ?? '');
        $tourEnd = trim($_POST['tour_end'] ?? '');
        $tourDistance = (float)($_POST['tour_distance'] ?? 0);
        $tourRate = (float)($_POST['tour_rate'] ?? 0);
        $tourDescription = trim($_POST['tour_description'] ?? '');
        $tourVehicle = trim($_POST['tour_vehicle'] ?? '');
        $tourDriver = trim($_POST['tour_driver'] ?? '');

        if (empty($tourName) || empty($tourDate)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /tours/add');
            exit;
        }

        // Calculate duration and total
        $duration = 0;
        if (!empty($tourStart) && !empty($tourEnd)) {
            $start = strtotime($tourStart);
            $end = strtotime($tourEnd);
            $duration = ($end - $start) / 3600; // Convert to hours
        }
        
        $tourTotal = $duration * $tourRate;

        // Insert tour
        $sql = "INSERT INTO tours (user_id, tour_name, tour_date, tour_start, tour_end, 
                tour_duration, tour_distance, tour_rate, tour_total, tour_description, 
                tour_vehicle, tour_driver, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        try {
            $this->database->execute($sql, [
                $userId, $tourName, $tourDate, $tourStart, $tourEnd, $duration,
                $tourDistance, $tourRate, $tourTotal, $tourDescription, $tourVehicle, $tourDriver
            ]);

            $this->session->setFlash('success', $this->localization->get('success_saved'));
            header('Location: /tours/overview');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /tours/add');
            exit;
        }
    }

    /**
     * Show tours overview
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get tours with pagination
        $sql = "SELECT * FROM tours WHERE user_id = ? ORDER BY tour_date DESC LIMIT ? OFFSET ?";
        $tours = $this->database->query($sql, [$userId, $limit, $offset]);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM tours WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        $this->render('tours/overview', [
            'title' => $this->localization->get('tours') . ' - ' . $this->localization->get('overview'),
            'tours' => $tours,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ]
        ]);
    }

    /**
     * Show tour reports
     */
    public function reports(): void
    {
        $userId = $this->session->getUserId();
        
        // Get date range filters
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');

        // Get tour statistics
        $stats = $this->getTourStats($userId, $fromDate, $toDate);
        
        // Get tours for reporting
        $tours = $this->getToursForReport($userId, $fromDate, $toDate);

        $this->render('tours/reports', [
            'title' => $this->localization->get('tours') . ' - ' . $this->localization->get('reports'),
            'stats' => $stats,
            'tours' => $tours,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    }

    /**
     * Get tour statistics
     */
    private function getTourStats(int $userId, string $fromDate, string $toDate): array
    {
        $stats = [];

        // Total tours
        $sql = "SELECT COUNT(*) as total FROM tours 
                WHERE user_id = ? AND tour_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_tours'] = $result['total'] ?? 0;

        // Total distance
        $sql = "SELECT SUM(tour_distance) as total FROM tours 
                WHERE user_id = ? AND tour_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_distance'] = $result['total'] ?? 0;

        // Total duration
        $sql = "SELECT SUM(tour_duration) as total FROM tours 
                WHERE user_id = ? AND tour_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_duration'] = $result['total'] ?? 0;

        // Total earnings
        $sql = "SELECT SUM(tour_total) as total FROM tours 
                WHERE user_id = ? AND tour_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_earnings'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get tours for reporting
     */
    private function getToursForReport(int $userId, string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM tours 
                WHERE user_id = ? AND tour_date BETWEEN ? AND ?
                ORDER BY tour_date DESC";
        
        return $this->database->query($sql, [$userId, $fromDate, $toDate]);
    }

    /**
     * View tour details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get tour details
        $sql = "SELECT * FROM tours WHERE id = ? AND user_id = ?";
        $tour = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$tour) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tours/overview');
            exit;
        }

        $this->render('tours/view', [
            'title' => $this->localization->get('tour') . ' - ' . $tour['tour_name'],
            'tour' => $tour
        ]);
    }

    /**
     * Show edit tour form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get tour details
        $sql = "SELECT * FROM tours WHERE id = ? AND user_id = ?";
        $tour = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$tour) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tours/overview');
            exit;
        }

        $this->render('tours/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('tour'),
            'tour' => $tour
        ]);
    }

    /**
     * Update tour
     */
    public function update(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $tourName = trim($_POST['tour_name'] ?? '');
        $tourDate = trim($_POST['tour_date'] ?? '');
        $tourStart = trim($_POST['tour_start'] ?? '');
        $tourEnd = trim($_POST['tour_end'] ?? '');
        $tourDistance = (float)($_POST['tour_distance'] ?? 0);
        $tourRate = (float)($_POST['tour_rate'] ?? 0);
        $tourDescription = trim($_POST['tour_description'] ?? '');
        $tourVehicle = trim($_POST['tour_vehicle'] ?? '');
        $tourDriver = trim($_POST['tour_driver'] ?? '');
        $status = trim($_POST['status'] ?? 'pending');

        if (empty($tourName) || empty($tourDate)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /tours/edit/' . $id);
            exit;
        }

        // Calculate duration and total
        $duration = 0;
        if (!empty($tourStart) && !empty($tourEnd)) {
            $start = strtotime($tourStart);
            $end = strtotime($tourEnd);
            $duration = ($end - $start) / 3600; // Convert to hours
        }
        
        $tourTotal = $duration * $tourRate;

        // Update tour
        $sql = "UPDATE tours SET tour_name = ?, tour_date = ?, tour_start = ?, tour_end = ?, 
                tour_duration = ?, tour_distance = ?, tour_rate = ?, tour_total = ?, 
                tour_description = ?, tour_vehicle = ?, tour_driver = ?, status = ? 
                WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [
                $tourName, $tourDate, $tourStart, $tourEnd, $duration,
                $tourDistance, $tourRate, $tourTotal, $tourDescription, 
                $tourVehicle, $tourDriver, $status, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /tours/view/' . $id);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /tours/edit/' . $id);
            exit;
        }
    }

    /**
     * Delete tour
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if tour exists and belongs to user
        $sql = "SELECT id FROM tours WHERE id = ? AND user_id = ?";
        $tour = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$tour) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tours/overview');
            exit;
        }

        // Delete tour
        try {
            $sql = "DELETE FROM tours WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /tours/overview');
        exit;
    }

    /**
     * Search tours
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT id, tour_name, tour_date, tour_duration, tour_distance, tour_total 
                FROM tours 
                WHERE user_id = ? AND tour_name LIKE ?
                ORDER BY tour_date DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }
} 