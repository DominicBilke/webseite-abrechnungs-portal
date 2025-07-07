<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Billing Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h1 class="h3 mb-3 text-danger">
                            <?= $localization->get('error_500_title') ?>
                        </h1>
                        
                        <p class="text-muted mb-4">
                            <?= $localization->get('error_500_message') ?>
                        </p>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="/dashboard" class="btn btn-primary">
                                <i class="bi bi-house me-1"></i>
                                <?= $localization->get('go_to_dashboard') ?>
                            </a>
                            <button onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                <?= $localization->get('go_back') ?>
                            </button>
                        </div>
                        
                        <?php if (defined('APP_DEBUG') && APP_DEBUG): ?>
                            <div class="mt-4">
                                <details>
                                    <summary class="text-muted cursor-pointer">
                                        <?= $localization->get('show_error_details') ?>
                                    </summary>
                                    <div class="mt-3 p-3 bg-light rounded text-start">
                                        <small class="text-muted">
                                            <strong><?= $localization->get('error_message') ?>:</strong><br>
                                            <?= htmlspecialchars($e->getMessage()) ?>
                                        </small>
                                    </div>
                                </details>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 