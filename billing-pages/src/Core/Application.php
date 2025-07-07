<?php

namespace BillingPages\Core;

use BillingPages\Controllers\AuthController;
use BillingPages\Controllers\DashboardController;
use BillingPages\Controllers\CompanyController;
use BillingPages\Controllers\WorkController;
use BillingPages\Controllers\MoneyController;
use BillingPages\Controllers\BillingController;
use BillingPages\Controllers\UserController;
use BillingPages\Controllers\TourController;
use BillingPages\Controllers\TaskController;
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
        $this->router->get('/billing/all', [BillingController::class, 'all']);
        $this->router->get('/billing/generate/{type}/{id}', [BillingController::class, 'generate']);
        $this->router->get('/billing/download/{id}', [BillingController::class, 'download']);
        $this->router->get('/billing/view/{id}', [BillingController::class, 'view']);
        $this->router->get('/billing/edit/{id}', [BillingController::class, 'edit']);
        $this->router->post('/billing/edit/{id}', [BillingController::class, 'update']);
        $this->router->post('/billing/delete/{id}', [BillingController::class, 'delete']);
        $this->router->get('/billing/data/{type}', [BillingController::class, 'getData']);
        $this->router->post('/billing/data/{type}', [BillingController::class, 'saveData']);

        // Tour routes (placeholder for future implementation)
        $this->router->get('/tours', [DashboardController::class, 'redirectToDashboard']);

        // Task routes (placeholder for future implementation)
        $this->router->get('/tasks', [DashboardController::class, 'redirectToDashboard']);

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
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleError($e);
        }
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