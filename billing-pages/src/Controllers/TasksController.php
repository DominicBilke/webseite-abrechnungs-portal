<?php

namespace BillingPages\Controllers;

/**
 * Tasks Controller - Handles task management and billing
 */
class TasksController extends BaseController
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
     * Show tasks index
     */
    public function index(): void
    {
        $this->overview();
    }

    /**
     * Show add task form
     */
    public function showAdd(): void
    {
        // Get companies for dropdown
        $userId = $this->session->getUserId();
        $sql = "SELECT id, company_name FROM companies WHERE user_id = ? AND status = 'active' ORDER BY company_name";
        $companies = $this->database->query($sql, [$userId]);

        $this->render('tasks/add', [
            'title' => $this->localization->get('add') . ' ' . $this->localization->get('task'),
            'companies' => $companies
        ]);
    }

    /**
     * Add new task
     */
    public function add(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $taskName = trim($_POST['task_name'] ?? '');
        $taskDescription = trim($_POST['task_description'] ?? '');
        $taskPriority = trim($_POST['task_priority'] ?? 'medium');
        $taskAssigned = trim($_POST['task_assigned'] ?? '');
        $taskDueDate = trim($_POST['task_due_date'] ?? '');
        $taskEstimatedHours = (float)($_POST['task_estimated_hours'] ?? 0);
        $taskRate = (float)($_POST['task_rate'] ?? 0);

        if (empty($taskName)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /tasks/add');
            exit;
        }

        // Calculate estimated total
        $taskEstimatedTotal = $taskEstimatedHours * $taskRate;

        // Insert task
        $sql = "INSERT INTO tasks (user_id, task_name, task_description, task_priority, 
                task_assigned, task_due_date, task_estimated_hours, task_rate, task_total) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->database->execute($sql, [
                $userId, $taskName, $taskDescription, $taskPriority, $taskAssigned,
                $taskDueDate, $taskEstimatedHours, $taskRate, $taskEstimatedTotal
            ]);

            $this->session->setFlash('success', $this->localization->get('success_saved'));
            header('Location: /tasks/overview');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_save'));
            header('Location: /tasks/add');
            exit;
        }
    }

    /**
     * Show tasks overview
     */
    public function overview(): void
    {
        $userId = $this->session->getUserId();
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get tasks with pagination
        $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY task_due_date ASC, task_priority DESC LIMIT ? OFFSET ?";
        $tasks = $this->database->query($sql, [$userId, $limit, $offset]);

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM tasks WHERE user_id = ?";
        $totalResult = $this->database->queryOne($countSql, [$userId]);
        $total = $totalResult['total'] ?? 0;
        $totalPages = ceil($total / $limit);

        $this->render('tasks/overview', [
            'title' => $this->localization->get('tasks') . ' - ' . $this->localization->get('overview'),
            'tasks' => $tasks,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'total_records' => $total
            ]
        ]);
    }

    /**
     * Show task reports
     */
    public function reports(): void
    {
        $userId = $this->session->getUserId();
        
        // Get date range filters
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');

        // Get task statistics
        $stats = $this->getTaskStats($userId, $fromDate, $toDate);
        
        // Get tasks for reporting
        $tasks = $this->getTasksForReport($userId, $fromDate, $toDate);

        $this->render('tasks/reports', [
            'title' => $this->localization->get('tasks') . ' - ' . $this->localization->get('reports'),
            'stats' => $stats,
            'tasks' => $tasks,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    }

    /**
     * Get task statistics
     */
    private function getTaskStats(int $userId, string $fromDate, string $toDate): array
    {
        $stats = [];

        // Total tasks
        $sql = "SELECT COUNT(*) as total FROM tasks 
                WHERE user_id = ? AND task_due_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_tasks'] = $result['total'] ?? 0;

        // Completed tasks
        $sql = "SELECT COUNT(*) as total FROM tasks 
                WHERE user_id = ? AND task_status = 'completed' AND task_due_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['completed_tasks'] = $result['total'] ?? 0;

        // Pending tasks
        $sql = "SELECT COUNT(*) as total FROM tasks 
                WHERE user_id = ? AND task_status = 'pending' AND task_due_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['pending_tasks'] = $result['total'] ?? 0;

        // Total estimated hours
        $sql = "SELECT SUM(task_estimated_hours) as total FROM tasks 
                WHERE user_id = ? AND task_due_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_estimated_hours'] = $result['total'] ?? 0;

        // Total actual hours
        $sql = "SELECT SUM(task_actual_hours) as total FROM tasks 
                WHERE user_id = ? AND task_status = 'completed' AND task_due_date BETWEEN ? AND ?";
        $result = $this->database->queryOne($sql, [$userId, $fromDate, $toDate]);
        $stats['total_actual_hours'] = $result['total'] ?? 0;

        return $stats;
    }

    /**
     * Get tasks for reporting
     */
    private function getTasksForReport(int $userId, string $fromDate, string $toDate): array
    {
        $sql = "SELECT * FROM tasks 
                WHERE user_id = ? AND task_due_date BETWEEN ? AND ?
                ORDER BY task_due_date ASC, task_priority DESC";
        
        return $this->database->query($sql, [$userId, $fromDate, $toDate]);
    }

    /**
     * View task details
     */
    public function view(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get task details
        $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
        $task = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$task) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tasks/overview');
            exit;
        }

        $this->render('tasks/view', [
            'title' => $this->localization->get('task') . ' - ' . $task['task_name'],
            'task' => $task
        ]);
    }

    /**
     * Show edit task form
     */
    public function edit(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Get task details
        $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
        $task = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$task) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tasks/overview');
            exit;
        }

        $this->render('tasks/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('task'),
            'task' => $task
        ]);
    }

    /**
     * Update task
     */
    public function update(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $taskName = trim($_POST['task_name'] ?? '');
        $taskDescription = trim($_POST['task_description'] ?? '');
        $taskPriority = trim($_POST['task_priority'] ?? 'medium');
        $taskAssigned = trim($_POST['task_assigned'] ?? '');
        $taskDueDate = trim($_POST['task_due_date'] ?? '');
        $taskEstimatedHours = (float)($_POST['task_estimated_hours'] ?? 0);
        $taskActualHours = (float)($_POST['task_actual_hours'] ?? 0);
        $taskRate = (float)($_POST['task_rate'] ?? 0);
        $taskStatus = trim($_POST['task_status'] ?? 'pending');

        if (empty($taskName)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /tasks/edit/' . $id);
            exit;
        }

        // Calculate totals
        $taskEstimatedTotal = $taskEstimatedHours * $taskRate;
        $taskActualTotal = $taskActualHours * $taskRate;

        // Update task
        $sql = "UPDATE tasks SET task_name = ?, task_description = ?, task_priority = ?, 
                task_assigned = ?, task_due_date = ?, task_estimated_hours = ?, 
                task_actual_hours = ?, task_rate = ?, task_total = ?, task_status = ?,
                task_completed_date = ? WHERE id = ? AND user_id = ?";
        
        $taskCompletedDate = ($taskStatus === 'completed' && empty($_POST['task_completed_date'])) 
            ? date('Y-m-d H:i:s') : $_POST['task_completed_date'] ?? null;
        
        try {
            $this->database->execute($sql, [
                $taskName, $taskDescription, $taskPriority, $taskAssigned,
                $taskDueDate, $taskEstimatedHours, $taskActualHours, $taskRate,
                $taskActualTotal, $taskStatus, $taskCompletedDate, $id, $userId
            ]);

            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /tasks/view/' . $id);
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /tasks/edit/' . $id);
            exit;
        }
    }

    /**
     * Delete task
     */
    public function delete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if task exists and belongs to user
        $sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
        $task = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$task) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tasks/overview');
            exit;
        }

        // Delete task
        try {
            $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
            $this->database->execute($sql, [$id, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('success_deleted'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_delete'));
        }
        
        header('Location: /tasks/overview');
        exit;
    }

    /**
     * Mark task as completed
     */
    public function complete(int $id): void
    {
        $userId = $this->session->getUserId();
        
        // Check if task exists and belongs to user
        $sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
        $task = $this->database->queryOne($sql, [$id, $userId]);
        
        if (!$task) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /tasks/overview');
            exit;
        }

        // Update task status
        $sql = "UPDATE tasks SET task_status = 'completed', task_completed_date = NOW() WHERE id = ? AND user_id = ?";
        
        try {
            $this->database->execute($sql, [$id, $userId]);
            $this->session->setFlash('success', $this->localization->get('success_task_completed'));
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
        }
        
        header('Location: /tasks/view/' . $id);
        exit;
    }

    /**
     * Search tasks
     */
    public function search(): void
    {
        $userId = $this->session->getUserId();
        $query = trim($_POST['query'] ?? '');
        
        if (empty($query)) {
            $this->jsonResponse(['success' => false, 'message' => 'Query is required']);
        }

        $sql = "SELECT id, task_name, task_status, task_priority, task_due_date, task_assigned 
                FROM tasks 
                WHERE user_id = ? AND task_name LIKE ?
                ORDER BY task_due_date ASC, task_priority DESC 
                LIMIT 10";
        
        $searchTerm = "%{$query}%";
        $results = $this->database->query($sql, [$userId, $searchTerm]);
        
        $this->jsonResponse(['success' => true, 'data' => $results]);
    }
} 