<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Billing Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo and Title -->
                        <div class="text-center mb-4">
                            <i class="bi bi-calculator text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-3 mb-1">Billing Portal</h2>
                            <p class="text-muted"><?= $localization->get('login') ?></p>
                        </div>

                        <!-- Language Switcher -->
                        <div class="text-center mb-4">
                            <div class="btn-group" role="group">
                                <a href="<?= $getLanguageUrl('de') ?>" class="btn btn-outline-secondary btn-sm <?= $locale === 'de' ? 'active' : '' ?>">
                                    <?= $localization->get('language_de') ?>
                                </a>
                                <a href="<?= $getLanguageUrl('en') ?>" class="btn btn-outline-secondary btn-sm <?= $locale === 'en' ? 'active' : '' ?>">
                                    <?= $localization->get('language_en') ?>
                                </a>
                            </div>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="/login">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    <?= $localization->get('username') ?>
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('username') ?>" 
                                       required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    <?= $localization->get('password') ?>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('password') ?>" 
                                       required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    <?= $localization->get('remember_me') ?>
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    <?= $localization->get('login') ?>
                                </button>
                            </div>
                        </form>

                        <!-- Additional Links -->
                        <div class="text-center mt-4">
                            <a href="/forgot-password" class="text-decoration-none">
                                <?= $localization->get('forgot_password') ?>
                            </a>
                        </div>

                        <!-- Demo Credentials -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Demo Credentials:</strong><br>
                                Username: <code>demo</code><br>
                                Password: <code>demo123</code><br><br>
                                <strong>Admin Credentials:</strong><br>
                                Username: <code>admin</code><br>
                                Password: <code>password</code>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <a href="/privacy" class="text-decoration-none me-3"><?= $localization->get('privacy') ?></a>
                        <a href="/imprint" class="text-decoration-none"><?= $localization->get('imprint') ?></a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 