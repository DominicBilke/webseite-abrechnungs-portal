<?php

namespace BillingPages\Core;

/**
 * Session Management Class
 */
class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Clear all session data
     */
    public function clear(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Set user authentication data
     */
    public function setUser(array $userData): void
    {
        $this->set('user_id', $userData['id']);
        $this->set('username', $userData['username']);
        $this->set('email', $userData['email']);
        $this->set('role', $userData['role']);
        $this->set('domain', $userData['domain'] ?? null);
        $this->set('authenticated', true);
        $this->set('login_time', time());
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated(): bool
    {
        return $this->get('authenticated', false);
    }

    /**
     * Get current user ID
     */
    public function getUserId(): ?int
    {
        return $this->get('user_id');
    }

    /**
     * Get current username
     */
    public function getUsername(): ?string
    {
        return $this->get('username');
    }

    /**
     * Get current user role
     */
    public function getUserRole(): ?string
    {
        return $this->get('role');
    }

    /**
     * Get current domain
     */
    public function getDomain(): ?string
    {
        return $this->get('domain');
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->getUserRole() === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $userRole = $this->getUserRole();
        return in_array($userRole, $roles);
    }

    /**
     * Set flash message
     */
    public function setFlash(string $key, string $message): void
    {
        $this->set('flash_' . $key, $message);
    }

    /**
     * Get flash message
     */
    public function getFlash(string $key): ?string
    {
        $message = $this->get('flash_' . $key);
        $this->remove('flash_' . $key);
        return $message;
    }

    /**
     * Check if flash message exists
     */
    public function hasFlash(string $key): bool
    {
        return $this->has('flash_' . $key);
    }

    /**
     * Regenerate session ID for security
     */
    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
} 