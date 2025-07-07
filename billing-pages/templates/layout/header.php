<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Billing Portal' ?> - <?= $localization->get('app_name') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="bi bi-calculator me-2"></i>
                <?= $localization->get('app_name') ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if ($session->isAuthenticated()): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="bi bi-speedometer2 me-1"></i>
                                <?= $localization->get('nav_dashboard') ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-building me-1"></i>
                                <?= $localization->get('nav_company') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/company"><?= $localization->get('overview') ?></a></li>
                                <li><a class="dropdown-item" href="/company/add"><?= $localization->get('add') ?></a></li>
                                <li><a class="dropdown-item" href="/company/reports"><?= $localization->get('reports') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-map me-1"></i>
                                <?= $localization->get('nav_tours') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/tours"><?= $localization->get('overview') ?></a></li>
                                <li><a class="dropdown-item" href="/tours/add"><?= $localization->get('add') ?></a></li>
                                <li><a class="dropdown-item" href="/tours/reports"><?= $localization->get('reports') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-briefcase me-1"></i>
                                <?= $localization->get('nav_work') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/work"><?= $localization->get('overview') ?></a></li>
                                <li><a class="dropdown-item" href="/work/add"><?= $localization->get('add') ?></a></li>
                                <li><a class="dropdown-item" href="/work/reports"><?= $localization->get('reports') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-list-task me-1"></i>
                                <?= $localization->get('nav_tasks') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/tasks"><?= $localization->get('overview') ?></a></li>
                                <li><a class="dropdown-item" href="/tasks/add"><?= $localization->get('add') ?></a></li>
                                <li><a class="dropdown-item" href="/tasks/reports"><?= $localization->get('reports') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-cash-coin me-1"></i>
                                <?= $localization->get('nav_money') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/money"><?= $localization->get('overview') ?></a></li>
                                <li><a class="dropdown-item" href="/money/add"><?= $localization->get('add') ?></a></li>
                                <li><a class="dropdown-item" href="/money/reports"><?= $localization->get('reports') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/billing">
                                <i class="bi bi-receipt me-1"></i>
                                <?= $localization->get('nav_billing') ?>
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-globe me-1"></i>
                                <?= $localization->getLocaleName($locale) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?lang=de"><?= $localization->get('language_de') ?></a></li>
                                <li><a class="dropdown-item" href="?lang=en"><?= $localization->get('language_en') ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= $session->getUsername() ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile"><?= $localization->get('profile') ?></a></li>
                                <li><a class="dropdown-item" href="/settings"><?= $localization->get('settings') ?></a></li>
                                <?php if ($session->hasRole('admin')): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/users"><?= $localization->get('users') ?></a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout"><?= $localization->get('logout') ?></a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-4">
        <!-- Flash Messages -->
        <?php if ($session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $session->getFlash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $session->getFlash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($session->hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= $session->getFlash('warning') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($session->hasFlash('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $session->getFlash('info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 