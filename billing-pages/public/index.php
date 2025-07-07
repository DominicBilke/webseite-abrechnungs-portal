<?php
/**
 * Billing Pages Portal - Main Entry Point
 * 
 * This file serves as the main entry point for the billing portal application.
 * It handles routing, authentication, and initializes the application.
 */

// Start session
session_start();

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Initialize application
$app = new BillingPages\Core\Application();

// Handle the request
$app->handleRequest(); 