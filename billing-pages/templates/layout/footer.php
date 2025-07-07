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
                        <li><a href="/contact" class="text-decoration-none"><?= $localization->get('contact') ?></a></li>
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
    
    <!-- Custom JavaScript -->
    <script src="/assets/js/app.js"></script>
    
    <!-- Additional Scripts -->
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 