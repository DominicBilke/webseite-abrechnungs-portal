<?php

namespace BillingPages\Controllers;

use BillingPages\Core\Database;
use BillingPages\Core\Session;
use BillingPages\Core\Localization;

/**
 * Base Controller Class
 * Provides common functionality for all controllers
 */
abstract class BaseController
{
    protected Database $database;
    protected Session $session;
    protected Localization $localization;

    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->session = new Session();
        $this->localization = new Localization();
    }

    /**
     * Render a template with common variables
     */
    protected function render(string $template, array $data = []): void
    {
        // Add common variables that all templates need
        $commonData = [
            'localization' => $this->localization,
            'session' => $this->session,
            'locale' => $this->localization->getLocale(),
            'title' => $data['title'] ?? 'Billing Portal'
        ];

        // Merge with provided data
        $data = array_merge($commonData, $data);

        // Extract variables for template
        extract($data);
        
        // Include the template
        include __DIR__ . "/../../templates/{$template}.php";
    }

    /**
     * Require authentication for protected routes
     */
    protected function requireAuth(): void
    {
        if (!$this->session->isAuthenticated()) {
            $this->session->setFlash('error', $this->localization->get('not_authenticated'));
            header('Location: /');
            exit;
        }
    }

    /**
     * Require specific role for protected routes
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        
        if (!$this->session->hasRole($role)) {
            $this->session->setFlash('error', $this->localization->get('access_denied'));
            header('Location: /dashboard');
            exit;
        }
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back to previous page
     */
    protected function redirectBack(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
        $this->redirect($referer);
    }

    /**
     * Get JSON response
     */
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Validate required fields
     */
    protected function validateRequired(array $data, array $fields): array
    {
        $errors = [];
        
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = $this->localization->get('validation_required', ['field' => $field]);
            }
        }
        
        return $errors;
    }

    /**
     * Sanitize input data
     */
    protected function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
} 