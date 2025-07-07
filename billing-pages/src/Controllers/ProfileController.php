<?php

namespace BillingPages\Controllers;

/**
 * Profile Controller - Handles user profile management
 */
class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Show profile page
     */
    public function index(): void
    {
        $userId = $this->session->getUserId();
        
        // Get user profile data
        $sql = "SELECT id, username, email, first_name, last_name, role, status, created_at, last_login 
                FROM users WHERE id = ?";
        $user = $this->database->queryOne($sql, [$userId]);
        
        if (!$user) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /dashboard');
            exit;
        }

        $this->render('profile/index', [
            'title' => $this->localization->get('profile'),
            'user' => $user
        ]);
    }

    /**
     * Show edit profile form
     */
    public function edit(): void
    {
        $userId = $this->session->getUserId();
        
        // Get user profile data
        $sql = "SELECT id, username, email, first_name, last_name, role, status 
                FROM users WHERE id = ?";
        $user = $this->database->queryOne($sql, [$userId]);
        
        if (!$user) {
            $this->session->setFlash('error', $this->localization->get('error_not_found'));
            header('Location: /profile');
            exit;
        }

        $this->render('profile/edit', [
            'title' => $this->localization->get('edit') . ' ' . $this->localization->get('profile'),
            'user' => $user
        ]);
    }

    /**
     * Update profile
     */
    public function update(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /profile/edit');
            exit;
        }

        // Check if email is already taken by another user
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $existingUser = $this->database->queryOne($sql, [$email, $userId]);
        
        if ($existingUser) {
            $this->session->setFlash('error', $this->localization->get('email_already_exists'));
            header('Location: /profile/edit');
            exit;
        }

        // Update user profile
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        
        try {
            $this->database->execute($sql, [$firstName, $lastName, $email, $userId]);
            
            // Update session data
            $this->session->setUsername($firstName . ' ' . $lastName);
            
            $this->session->setFlash('success', $this->localization->get('success_updated'));
            header('Location: /profile');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /profile/edit');
            exit;
        }
    }

    /**
     * Show change password form
     */
    public function changePassword(): void
    {
        $this->render('profile/change_password', [
            'title' => $this->localization->get('change_password')
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(): void
    {
        $userId = $this->session->getUserId();
        
        // Validate input
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->session->setFlash('error', $this->localization->get('validation_required'));
            header('Location: /profile/change-password');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $this->session->setFlash('error', $this->localization->get('passwords_dont_match'));
            header('Location: /profile/change-password');
            exit;
        }

        if (strlen($newPassword) < 6) {
            $this->session->setFlash('error', $this->localization->get('password_too_short'));
            header('Location: /profile/change-password');
            exit;
        }

        // Verify current password
        $sql = "SELECT password FROM users WHERE id = ?";
        $user = $this->database->queryOne($sql, [$userId]);
        
        if (!password_verify($currentPassword, $user['password'])) {
            $this->session->setFlash('error', $this->localization->get('current_password_incorrect'));
            header('Location: /profile/change-password');
            exit;
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        try {
            $this->database->execute($sql, [$hashedPassword, $userId]);
            
            $this->session->setFlash('success', $this->localization->get('password_updated'));
            header('Location: /profile');
            exit;
        } catch (\Exception $e) {
            $this->session->setFlash('error', $this->localization->get('error_update'));
            header('Location: /profile/change-password');
            exit;
        }
    }
} 