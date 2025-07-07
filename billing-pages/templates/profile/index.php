<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    <?= $localization->get('profile') ?>
                </h4>
                <a href="/profile/edit" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>
                    <?= $localization->get('edit') ?>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('personal_information') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('username') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['username']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('email') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('first_name') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['first_name']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('last_name') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['last_name']) ?></p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('account_information') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('role') ?></label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'manager' ? 'warning' : 'info') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('member_since') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('last_login') ?></label>
                            <p class="form-control-plaintext">
                                <?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : $localization->get('never') ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex flex-wrap gap-2 justify-content-between">
                    <a href="/profile/change-password" class="btn btn-outline-primary">
                        <i class="bi bi-key me-1"></i>
                        <?= $localization->get('change_password') ?>
                    </a>
                    <a href="/settings" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i>
                        <?= $localization->get('settings') ?>
                    </a>
                    <?php if ($session->hasRole('admin')): ?>
                        <a href="/users" class="btn btn-outline-info">
                            <i class="bi bi-people me-1"></i>
                            <?= $localization->get('users') ?>
                        </a>
                    <?php endif; ?>
                    <a href="/dashboard" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?= $localization->get('back_to_dashboard') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?> 