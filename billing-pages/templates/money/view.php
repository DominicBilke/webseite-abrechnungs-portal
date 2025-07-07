<?php
$localization = new BillingPages\Core\Localization();
$session = new BillingPages\Core\Session();
?>

<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-cash-coin me-2"></i>
                <?= htmlspecialchars($money['description']) ?>
            </h1>
            <p class="text-muted"><?= $localization->get('money') ?> <?= $localization->get('entry') ?> <?= $localization->get('details') ?></p>
        </div>
        <div>
            <a href="/money/edit/<?= $money['id'] ?>" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>
                <?= $localization->get('edit') ?>
            </a>
            <a href="/money/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Money Details -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        <?= $localization->get('money') ?> <?= $localization->get('information') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('description') ?></label>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($money['description'])) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('payment_date') ?></label>
                                <p class="mb-0">
                                    <?= date('d.m.Y', strtotime($money['payment_date'])) ?> 
                                    <small class="text-muted">(<?= date('l', strtotime($money['payment_date'])) ?>)</small>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('category') ?></label>
                                <p class="mb-0">
                                    <?php if (!empty($money['category'])): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($money['category']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('payment_method') ?></label>
                                <p class="mb-0">
                                    <?php if (!empty($money['payment_method'])): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($money['payment_method']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('amount') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $money['amount'] >= 0 ? 'success' : 'danger' ?> fs-6">
                                        <?= ($money['amount'] >= 0 ? '+' : '') . number_format($money['amount'], 2) ?> <?= $money['currency'] ?>
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('currency') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($money['currency']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('payment_status') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $money['payment_status'] === 'completed' ? 'success' : ($money['payment_status'] === 'cancelled' ? 'danger' : 'warning') ?>">
                                        <?= $localization->get($money['payment_status']) ?>
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('reference') ?></label>
                                <p class="mb-0">
                                    <?php if (!empty($money['reference'])): ?>
                                        <code><?= htmlspecialchars($money['reference']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($money['notes'])): ?>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('notes') ?></label>
                            <div class="alert alert-light">
                                <?= nl2br(htmlspecialchars($money['notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('type') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $money['amount'] >= 0 ? 'success' : 'danger' ?>">
                                        <?= $money['amount'] >= 0 ? $localization->get('income') : $localization->get('expense') ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                                <p class="mb-0"><?= date('d.m.Y H:i', strtotime($money['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Money Statistics -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>
                        <?= $localization->get('statistics') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-<?= $money['amount'] >= 0 ? 'success' : 'danger' ?> mb-1">
                                    <?= number_format($money['amount'], 2) ?> <?= $money['currency'] ?>
                                </h4>
                                <small class="text-muted"><?= $localization->get('amount') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1"><?= date('d.m.Y', strtotime($money['payment_date'])) ?></h4>
                                <small class="text-muted"><?= $localization->get('date') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">
                                    <?= $money['amount'] >= 0 ? $localization->get('income') : $localization->get('expense') ?>
                                </h4>
                                <small class="text-muted"><?= $localization->get('type') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary mb-1"><?= htmlspecialchars($money['currency']) ?></h4>
                                <small class="text-muted"><?= $localization->get('currency') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>
                        <?= $localization->get('quick_actions') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/money/edit/<?= $money['id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-2"></i>
                            <?= $localization->get('edit') ?> <?= $localization->get('money') ?>
                        </a>
                        <button onclick="duplicateMoney()" class="btn btn-outline-info">
                            <i class="bi bi-files me-2"></i>
                            <?= $localization->get('duplicate') ?>
                        </button>
                        <?php if ($money['amount'] < 0): ?>
                            <a href="/billing/generate/expense/<?= $money['id'] ?>" class="btn btn-primary">
                                <i class="bi bi-receipt me-2"></i>
                                <?= $localization->get('generate') ?> <?= $localization->get('expense_report') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Money Entries -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-cash-coin me-2"></i>
                <?= $localization->get('related') ?> <?= $localization->get('money') ?> <?= $localization->get('entries') ?>
            </h6>
            <a href="/money/overview?category=<?= urlencode($money['category']) ?>" class="btn btn-sm btn-outline-primary">
                <?= $localization->get('view_all') ?>
            </a>
        </div>
        <div class="card-body">
            <div class="text-center py-4">
                <i class="bi bi-cash-coin fa-3x text-muted mb-3"></i>
                <h5 class="text-muted"><?= $localization->get('related_money_entries') ?></h5>
                <p class="text-muted"><?= $localization->get('related_money_entries_description') ?></p>
                <a href="/money/add?category=<?= urlencode($money['category']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('add') ?> <?= $localization->get('related') ?> <?= $localization->get('money') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Money view functionality
function duplicateMoney() {
    if (confirm('<?= $localization->get('confirm_duplicate_money') ?>')) {
        // Create a new money entry with the same data
        const formData = new FormData();
        formData.append('amount', '<?= $money['amount'] ?>');
        formData.append('currency', '<?= $money['currency'] ?>');
        formData.append('description', '<?= addslashes($money['description']) ?> (Kopie)');
        formData.append('payment_method', '<?= $money['payment_method'] ?>');
        formData.append('payment_date', '<?= $money['payment_date'] ?>');
        formData.append('payment_status', 'pending');
        formData.append('category', '<?= $money['category'] ?>');
        formData.append('reference', '<?= $money['reference'] ?>');
        formData.append('notes', '<?= addslashes($money['notes']) ?>');

        fetch('/money/add', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/money/view/' + data.money_id;
            } else {
                alert(data.error || '<?= $localization->get('error_duplicate') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_duplicate') ?>');
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to edit
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '/money/edit/<?= $money['id'] ?>';
    }
    
    // Ctrl/Cmd + B to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
        e.preventDefault();
        window.location.href = '/money/overview';
    }
    
    // Ctrl/Cmd + D to duplicate
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        duplicateMoney();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 