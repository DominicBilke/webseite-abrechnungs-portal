<?php
/**
 * Help Page Template
 */
?>
<!DOCTYPE html>
<html lang="<?= $localization->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $localization->get('help') ?> - <?= $localization->get('app_name') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-calculator"></i>
                <?= $localization->get('app_name') ?>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <?= $localization->get('login') ?>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4">
                        <i class="bi bi-question-circle text-primary"></i>
                        <?= $localization->get('help') ?>
                    </h1>
                    <p class="lead text-muted">
                        <?= $localization->get('help_subtitle') ?>
                    </p>
                </div>

                <!-- Quick Navigation -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-list-ul"></i>
                            <?= $localization->get('quick_navigation') ?>
                        </h5>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="#getting-started" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-play-circle"></i>
                                    <?= $localization->get('getting_started') ?>
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#features" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-star"></i>
                                    <?= $localization->get('features') ?>
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#faq" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-question-diamond"></i>
                                    <?= $localization->get('faq') ?>
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#support" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-headset"></i>
                                    <?= $localization->get('support') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Getting Started -->
                <div id="getting-started" class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-play-circle text-success"></i>
                            <?= $localization->get('getting_started') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">1</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('create_account') ?></h5>
                                        <p class="text-muted"><?= $localization->get('create_account_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">2</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('add_companies') ?></h5>
                                        <p class="text-muted"><?= $localization->get('add_companies_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">3</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('track_work') ?></h5>
                                        <p class="text-muted"><?= $localization->get('track_work_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">4</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('generate_invoices') ?></h5>
                                        <p class="text-muted"><?= $localization->get('generate_invoices_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div id="features" class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-star text-warning"></i>
                            <?= $localization->get('features') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-building text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('company_management') ?></h5>
                                        <p class="text-muted"><?= $localization->get('company_management_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-clock text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('time_tracking') ?></h5>
                                        <p class="text-muted"><?= $localization->get('time_tracking_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-receipt text-info fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('invoice_generation') ?></h5>
                                        <p class="text-muted"><?= $localization->get('invoice_generation_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-graph-up text-warning fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('reports_analytics') ?></h5>
                                        <p class="text-muted"><?= $localization->get('reports_analytics_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-calendar-check text-danger fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('task_management') ?></h5>
                                        <p class="text-muted"><?= $localization->get('task_management_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-currency-exchange text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5><?= $localization->get('expense_tracking') ?></h5>
                                        <p class="text-muted"><?= $localization->get('expense_tracking_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div id="faq" class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-question-diamond text-info"></i>
                            <?= $localization->get('faq') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        <?= $localization->get('faq_question_1') ?>
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?= $localization->get('faq_answer_1') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        <?= $localization->get('faq_question_2') ?>
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?= $localization->get('faq_answer_2') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        <?= $localization->get('faq_question_3') ?>
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?= $localization->get('faq_answer_3') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        <?= $localization->get('faq_question_4') ?>
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?= $localization->get('faq_answer_4') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div id="support" class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-headset text-primary"></i>
                            <?= $localization->get('support') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-envelope text-primary fs-1 mb-3"></i>
                                    <h5><?= $localization->get('contact_support') ?></h5>
                                    <p class="text-muted"><?= $localization->get('contact_support_desc') ?></p>
                                    <a href="https://www.dominic-bilke.de/en/contact" class="btn btn-primary" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        <?= $localization->get('contact_us') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-book text-success fs-1 mb-3"></i>
                                    <h5><?= $localization->get('documentation') ?></h5>
                                    <p class="text-muted"><?= $localization->get('documentation_desc') ?></p>
                                    <a href="/README.md" class="btn btn-success" target="_blank">
                                        <i class="bi bi-file-text"></i>
                                        <?= $localization->get('view_documentation') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demo Access -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-play-circle"></i>
                            <?= $localization->get('try_demo') ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <p><?= $localization->get('demo_description') ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong><?= $localization->get('demo_credentials') ?>:</strong><br>
                                    <?= $localization->get('username') ?>: <code>demo</code><br>
                                    <?= $localization->get('password') ?>: <code>password</code>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <a href="/login" class="btn btn-info btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    <?= $localization->get('access_demo') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light border-top mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= $localization->get('app_name') ?></h5>
                    <p class="text-muted">
                        <?= $localization->get('app_name') ?> - <?= $localization->get('dashboard_welcome') ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <h6><?= $localization->get('help') ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="/help" class="text-decoration-none"><?= $localization->get('help') ?></a></li>
                        <li><a href="https://www.dominic-bilke.de/en/contact" class="text-decoration-none" target="_blank"><?= $localization->get('contact') ?></a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6><?= $localization->get('legal') ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="/privacy" class="text-decoration-none"><?= $localization->get('privacy') ?></a></li>
                        <li><a href="/imprint" class="text-decoration-none"><?= $localization->get('imprint') ?></a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; 2025 Bilke Web- und Softwareentwicklung. <?= $localization->get('all_rights_reserved') ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">
                        <?= $localization->get('version') ?> 2.0.0
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 