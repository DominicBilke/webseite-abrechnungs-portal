<?php

namespace BillingPages\Controllers;

/**
 * Authentication Controller
 */
class AuthController extends BaseController
{
    /**
     * Show login form
     */
    public function showLogin(): void
    {
        // If already authenticated, redirect to dashboard
        if ($this->session->isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }

        $this->render('auth/login', [
            'title' => $this->localization->get('login'),
            'localization' => $this->localization,
            'session' => $this->session,
            'locale' => $this->localization->getLocale()
        ]);
    }

    /**
     * Handle login form submission
     */
    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /');
            exit;
        }

        // Validate user credentials
        $user = $this->validateUser($username, $password);

        if ($user) {
            // Set user session
            $this->session->setUser($user);
            $this->session->regenerate();

            $this->session->setFlash('success', $this->localization->get('login_success'));
            header('Location: /dashboard');
            exit;
        } else {
            $this->session->setFlash('error', $this->localization->get('login_error'));
            header('Location: /');
            exit;
        }
    }

    /**
     * Handle logout
     */
    public function logout(): void
    {
        $this->session->clear();
        $this->session->setFlash('success', $this->localization->get('logout_success'));
        header('Location: /');
        exit;
    }

    /**
     * Validate user credentials
     */
    private function validateUser(string $username, string $password): ?array
    {
        $sql = "SELECT id, username, email, password, role, domain, status FROM users WHERE username = ? AND status = 'active'";
        $user = $this->database->queryOne($sql, [$username]);

        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'domain' => $user['domain']
            ];
        }

        return null;
    }
} 