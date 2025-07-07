<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    <?= $localization->get('change_password') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/profile/change-password">
                    <div class="mb-3">
                        <label for="current_password" class="form-label"><?= $localization->get('current_password') ?> *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label"><?= $localization->get('new_password') ?> *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text"><?= $localization->get('password_min_length') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><?= $localization->get('confirm_password') ?> *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/profile" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('cancel') ?>
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-1"></i>
                            <?= $localization->get('change_password') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?> 