<?php
/**
 * Billing Pages Portal - Installation Script
 * 
 * This script helps set up the billing portal application.
 * Run this script once to initialize the database and create the first user.
 */

// Start session
session_start();

// Check if already installed
if (file_exists('config/installed.lock')) {
    die('Billing Pages Portal is already installed. Remove config/installed.lock to reinstall.');
}

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Configuration
$config = [
    'db_host' => $_ENV['DB_HOST'] ?? 'localhost:3306',
    'db_name' => $_ENV['DB_NAME'] ?? 'billing_system',
    'db_user' => $_ENV['DB_USER'] ?? 'root',
    'db_pass' => $_ENV['DB_PASS'] ?? '',
    'app_name' => $_ENV['APP_NAME'] ?? 'Billing Pages Portal',
    'app_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'default_locale' => $_ENV['DEFAULT_LOCALE'] ?? 'de'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate database connection
    try {
        $pdo = new PDO(
            "mysql:host={$config['db_host']};charset=utf8mb4",
            $config['db_user'],
            $config['db_pass']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['db_name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$config['db_name']}`");
        
        // Import database schema
        $schema = file_get_contents('database/schema.sql');
        $pdo->exec($schema);
        
        // Create admin user
        $adminUsername = $_POST['admin_username'] ?? 'admin';
        $adminEmail = $_POST['admin_email'] ?? 'admin@example.com';
        $adminPassword = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, 'admin', 'active')");
        $stmt->execute([
            $adminUsername,
            $adminEmail,
            $adminPassword,
            $_POST['admin_first_name'] ?? 'Admin',
            $_POST['admin_last_name'] ?? 'User'
        ]);
        
        // Create config file
        $configContent = "<?php\n\n";
        $configContent .= "// Database configuration\n";
        $configContent .= "define('DB_HOST', '{$config['db_host']}');\n";
        $configContent .= "define('DB_NAME', '{$config['db_name']}');\n";
        $configContent .= "define('DB_USER', '{$config['db_user']}');\n";
        $configContent .= "define('DB_PASS', '{$config['db_pass']}');\n\n";
        $configContent .= "// Application configuration\n";
        $configContent .= "define('APP_NAME', '{$config['app_name']}');\n";
        $configContent .= "define('APP_URL', '{$config['app_url']}');\n";
        $configContent .= "define('APP_ENV', 'production');\n";
        $configContent .= "define('APP_DEBUG', false);\n\n";
        $configContent .= "// Session configuration\n";
        $configContent .= "define('SESSION_SECRET', '" . bin2hex(random_bytes(32)) . "');\n";
        $configContent .= "define('SESSION_LIFETIME', 3600);\n\n";
        $configContent .= "// File upload configuration\n";
        $configContent .= "define('UPLOAD_MAX_SIZE', 10485760);\n";
        $configContent .= "define('UPLOAD_ALLOWED_TYPES', 'jpg,jpeg,png,pdf,doc,docx');\n\n";
        $configContent .= "// Default language\n";
        $configContent .= "define('DEFAULT_LOCALE', '{$config['default_locale']}');\n\n";
        $configContent .= "// Set timezone\n";
        $configContent .= "date_default_timezone_set('Europe/Berlin');\n\n";
        $configContent .= "// Set session configuration\n";
        $configContent .= "@ini_set('session.cookie_httponly', 1);\n";
        $configContent .= "@ini_set('session.cookie_secure', isset(\$_SERVER['HTTPS']));\n";
        $configContent .= "@ini_set('session.cookie_samesite', 'Strict');\n";
        $configContent .= "@ini_set('session.gc_maxlifetime', SESSION_LIFETIME);\n";
        
        file_put_contents('config/config.php', $configContent);
        
        // Create installed lock file
        file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
        
        $success = true;
        
    } catch (Exception $e) {
        $errors[] = 'Database Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Pages Portal - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-calculator me-2"></i>
                            Billing Pages Portal
                        </h3>
                        <p class="mb-0">Installation Wizard</p>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success">
                                <h4 class="alert-heading">Installation Complete!</h4>
                                <p>The Billing Pages Portal has been successfully installed.</p>
                                <hr>
                                <p class="mb-0">
                                    <strong>Admin Credentials:</strong><br>
                                    Username: <?= htmlspecialchars($adminUsername) ?><br>
                                    Email: <?= htmlspecialchars($adminEmail) ?>
                                </p>
                                <hr>
                                <a href="/" class="btn btn-primary">Go to Login</a>
                            </div>
                        <?php else: ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <h5>Installation Errors:</h5>
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <h5 class="mb-3">Database Configuration</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_host" class="form-label">Database Host</label>
                                            <input type="text" class="form-control" id="db_host" name="db_host" 
                                                   value="<?= htmlspecialchars($config['db_host']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_name" class="form-label">Database Name</label>
                                            <input type="text" class="form-control" id="db_name" name="db_name" 
                                                   value="<?= htmlspecialchars($config['db_name']) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_user" class="form-label">Database Username</label>
                                            <input type="text" class="form-control" id="db_user" name="db_user" 
                                                   value="<?= htmlspecialchars($config['db_user']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_pass" class="form-label">Database Password</label>
                                            <input type="password" class="form-control" id="db_pass" name="db_pass" 
                                                   value="<?= htmlspecialchars($config['db_pass']) ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                <h5 class="mb-3">Admin User</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="admin_username" name="admin_username" 
                                                   value="admin" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                                   value="admin@example.com" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="admin_first_name" name="admin_first_name" 
                                                   value="Admin" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="admin_last_name" name="admin_last_name" 
                                                   value="User" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" 
                                           required minlength="8">
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-download me-2"></i>
                                        Install Billing Pages Portal
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Billing Pages Portal v1.0.0<br>
                        A comprehensive billing and time tracking solution
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 