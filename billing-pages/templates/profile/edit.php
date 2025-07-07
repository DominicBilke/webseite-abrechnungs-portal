<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    <?= $localization->get('edit') ?> <?= $localization->get('profile') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/profile/edit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label"><?= $localization->get('username') ?></label>
                                <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                <div class="form-text"><?= $localization->get('username_cannot_be_changed') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= $localization->get('email') ?> *</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label"><?= $localization->get('first_name') ?> *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label"><?= $localization->get('last_name') ?> *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label"><?= $localization->get('role') ?></label>
                                <input type="text" class="form-control" id="role" value="<?= ucfirst($user['role']) ?>" readonly>
                                <div class="form-text"><?= $localization->get('role_cannot_be_changed') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label"><?= $localization->get('status') ?></label>
                                <input type="text" class="form-control" id="status" value="<?= ucfirst($user['status']) ?>" readonly>
                                <div class="form-text"><?= $localization->get('status_cannot_be_changed') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/profile" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('cancel') ?>
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-1"></i>
                            <?= $localization->get('save_changes') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?> 