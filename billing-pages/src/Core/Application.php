<?php

namespace BillingPages\Core;

use BillingPages\Controllers\AuthController;
use BillingPages\Controllers\DashboardController;
use BillingPages\Controllers\CompanyController;
use BillingPages\Controllers\WorkController;
use BillingPages\Controllers\MoneyController;
use BillingPages\Controllers\BillingController;
use BillingPages\Controllers\ToursController;
use BillingPages\Controllers\TasksController;
use BillingPages\Controllers\ProfileController;
use BillingPages\Core\Router;
use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Main Application Class
 */
class Application
{
    private Router $router;
    private Database $database;
    private Session $session;
    private Localization $localization;

    public function __construct()
    {
        // Initialize core components
        $this->database = new Database();
        $this->session = new Session();
        $this->localization = new Localization();
        $this->router = new Router();

        // Set up routes
        $this->registerRoutes();
    }

    /**
     * Register all application routes
     */
    private function registerRoutes(): void
    {
        // Authentication routes
        $this->router->get('/', [AuthController::class, 'showLogin']);
        $this->router->post('/login', [AuthController::class, 'login']);
        $this->router->get('/logout', [AuthController::class, 'logout']);

        // Dashboard routes
        $this->router->get('/dashboard', [DashboardController::class, 'index']);

        // Company routes
        $this->router->get('/company', [CompanyController::class, 'index']);
        $this->router->get('/company/add', [CompanyController::class, 'showAdd']);
        $this->router->post('/company/add', [CompanyController::class, 'add']);
        $this->router->get('/company/overview', [CompanyController::class, 'overview']);
        $this->router->get('/company/reports', [CompanyController::class, 'reports']);
        $this->router->get('/company/view/{id}', [CompanyController::class, 'view']);
        $this->router->get('/company/edit/{id}', [CompanyController::class, 'edit']);
        $this->router->post('/company/edit/{id}', [CompanyController::class, 'update']);
        $this->router->post('/company/delete/{id}', [CompanyController::class, 'delete']);

        // Work routes
        $this->router->get('/work', [WorkController::class, 'index']);
        $this->router->get('/work/add', [WorkController::class, 'showAdd']);
        $this->router->post('/work/add', [WorkController::class, 'add']);
        $this->router->get('/work/overview', [WorkController::class, 'overview']);
        $this->router->get('/work/reports', [WorkController::class, 'reports']);
        $this->router->get('/work/view/{id}', [WorkController::class, 'view']);
        $this->router->get('/work/edit/{id}', [WorkController::class, 'edit']);
        $this->router->post('/work/edit/{id}', [WorkController::class, 'update']);
        $this->router->post('/work/delete/{id}', [WorkController::class, 'delete']);

        // Money routes
        $this->router->get('/money', [MoneyController::class, 'index']);
        $this->router->get('/money/add', [MoneyController::class, 'showAdd']);
        $this->router->post('/money/add', [MoneyController::class, 'add']);
        $this->router->get('/money/overview', [MoneyController::class, 'overview']);
        $this->router->get('/money/reports', [MoneyController::class, 'reports']);
        $this->router->get('/money/view/{id}', [MoneyController::class, 'view']);
        $this->router->get('/money/edit/{id}', [MoneyController::class, 'edit']);
        $this->router->post('/money/edit/{id}', [MoneyController::class, 'update']);
        $this->router->post('/money/delete/{id}', [MoneyController::class, 'delete']);

        // Billing routes
        $this->router->get('/billing', [BillingController::class, 'index']);
        $this->router->get('/billing/overview', [BillingController::class, 'overview']);
        $this->router->get('/billing/create', [BillingController::class, 'showCreate']);
        $this->router->post('/billing/create', [BillingController::class, 'create']);
        $this->router->get('/billing/view/{id}', [BillingController::class, 'view']);
        $this->router->get('/billing/pdf/{id}', [BillingController::class, 'generatePdf']);
        $this->router->get('/billing/send/{id}', [BillingController::class, 'sendEmail']);
        $this->router->get('/billing/mark-paid/{id}', [BillingController::class, 'markPaid']);
        $this->router->post('/billing/delete/{id}', [BillingController::class, 'delete']);

        // Tours routes
        $this->router->get('/tours', [ToursController::class, 'index']);
        $this->router->get('/tours/add', [ToursController::class, 'showAdd']);
        $this->router->post('/tours/add', [ToursController::class, 'add']);
        $this->router->get('/tours/overview', [ToursController::class, 'overview']);
        $this->router->get('/tours/reports', [ToursController::class, 'reports']);
        $this->router->get('/tours/view/{id}', [ToursController::class, 'view']);
        $this->router->get('/tours/edit/{id}', [ToursController::class, 'edit']);
        $this->router->post('/tours/edit/{id}', [ToursController::class, 'update']);
        $this->router->post('/tours/delete/{id}', [ToursController::class, 'delete']);

        // Tasks routes
        $this->router->get('/tasks', [TasksController::class, 'index']);
        $this->router->get('/tasks/add', [TasksController::class, 'showAdd']);
        $this->router->post('/tasks/add', [TasksController::class, 'add']);
        $this->router->get('/tasks/overview', [TasksController::class, 'overview']);
        $this->router->get('/tasks/reports', [TasksController::class, 'reports']);
        $this->router->get('/tasks/view/{id}', [TasksController::class, 'view']);
        $this->router->get('/tasks/edit/{id}', [TasksController::class, 'edit']);
        $this->router->post('/tasks/edit/{id}', [TasksController::class, 'update']);
        $this->router->post('/tasks/delete/{id}', [TasksController::class, 'delete']);
        $this->router->get('/tasks/complete/{id}', [TasksController::class, 'complete']);

        // Profile routes
        $this->router->get('/profile', [ProfileController::class, 'index']);
        $this->router->get('/profile/edit', [ProfileController::class, 'edit']);
        $this->router->post('/profile/edit', [ProfileController::class, 'update']);
        $this->router->get('/profile/change-password', [ProfileController::class, 'changePassword']);
        $this->router->post('/profile/change-password', [ProfileController::class, 'updatePassword']);

        // User management routes (placeholder for future implementation)
        $this->router->get('/users', [DashboardController::class, 'redirectToDashboard']);

        // Settings routes (placeholder for future implementation)
        $this->router->get('/settings', [DashboardController::class, 'redirectToDashboard']);

        // API routes for AJAX requests
        $this->router->get('/api/stats', [DashboardController::class, 'getStats']);
        $this->router->get('/api/chart-data', [DashboardController::class, 'getChartDataApi']);
        $this->router->post('/api/company/search', [CompanyController::class, 'search']);
        $this->router->post('/api/work/search', [WorkController::class, 'search']);
        $this->router->post('/api/money/search', [MoneyController::class, 'search']);
        $this->router->post('/api/tours/search', [ToursController::class, 'search']);
        $this->router->post('/api/tasks/search', [TasksController::class, 'search']);
        $this->router->post('/api/billing/search', [BillingController::class, 'search']);
    }

    /**
     * Handle incoming request
     */
    public function handleRequest(): void
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        $this->handleRequest();
    }

    /**
     * Handle application errors
     */
    private function handleError(\Exception $e): void
    {
        // Log error
        error_log("Application Error: " . $e->getMessage());
        
        // Show error page
        http_response_code(500);
        
        if ($this->session->isAuthenticated()) {
            // Show error page for authenticated users
            $this->renderErrorPage($e);
        } else {
            // Redirect to login for unauthenticated users
            header('Location: /');
            exit;
        }
    }

    /**
     * Render error page
     */
    private function renderErrorPage(\Exception $e): void
    {
        $title = $this->localization->get('error_title');
        $locale = $this->localization->getLocale();
        $localization = $this->localization;
        
        include __DIR__ . '/../../templates/error/500.php';
    }
} 