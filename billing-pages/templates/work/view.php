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
                <i class="bi bi-clock me-2"></i>
                <?= htmlspecialchars($work['work_description']) ?>
            </h1>
            <p class="text-muted"><?= $localization->get('work') ?> <?= $localization->get('entry') ?> <?= $localization->get('details') ?></p>
        </div>
        <div>
            <a href="/work/edit/<?= $work['id'] ?>" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>
                <?= $localization->get('edit') ?>
            </a>
            <a href="/work/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Work Details -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        <?= $localization->get('work') ?> <?= $localization->get('information') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_description') ?></label>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($work['work_description'])) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_date') ?></label>
                                <p class="mb-0">
                                    <?= date('d.m.Y', strtotime($work['work_date'])) ?> 
                                    <small class="text-muted">(<?= date('l', strtotime($work['work_date'])) ?>)</small>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_project') ?></label>
                                <p class="mb-0">
                                    <?php if (!empty($work['work_project'])): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($work['work_project']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_client') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($work['work_client']) ?: '-' ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_hours') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-primary fs-6"><?= number_format($work['work_hours'], 2) ?> <?= $localization->get('hours') ?></span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_rate') ?></label>
                                <p class="mb-0"><?= number_format($work['work_rate'], 2) ?> €/<?= $localization->get('hour') ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_total') ?></label>
                                <p class="mb-0">
                                    <strong class="text-success fs-5"><?= number_format($work['work_total'], 2) ?> €</strong>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('work_type') ?></label>
                                <p class="mb-0">
                                    <?php if (!empty($work['work_type'])): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($work['work_type']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $work['status'] === 'completed' ? 'success' : ($work['status'] === 'billed' ? 'info' : 'warning') ?>">
                                        <?= $localization->get($work['status']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                                <p class="mb-0"><?= date('d.m.Y H:i', strtotime($work['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Statistics -->
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
                                <h4 class="text-primary mb-1"><?= number_format($work['work_hours'], 2) ?></h4>
                                <small class="text-muted"><?= $localization->get('hours') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1"><?= number_format($work['work_total'], 2) ?> €</h4>
                                <small class="text-muted"><?= $localization->get('total') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1"><?= number_format($work['work_rate'], 2) ?> €</h4>
                                <small class="text-muted"><?= $localization->get('rate') ?>/<?= $localization->get('hour') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1"><?= date('d.m.Y', strtotime($work['work_date'])) ?></h4>
                                <small class="text-muted"><?= $localization->get('date') ?></small>
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
                        <a href="/billing/generate/work/<?= $work['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-receipt me-2"></i>
                            <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                        </a>
                        <a href="/work/edit/<?= $work['id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-2"></i>
                            <?= $localization->get('edit') ?> <?= $localization->get('work') ?>
                        </a>
                        <button onclick="duplicateWork()" class="btn btn-outline-info">
                            <i class="bi bi-files me-2"></i>
                            <?= $localization->get('duplicate') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Work Entries -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>
                <?= $localization->get('related') ?> <?= $localization->get('work') ?> <?= $localization->get('entries') ?>
            </h6>
            <a href="/work/overview?project=<?= urlencode($work['work_project']) ?>" class="btn btn-sm btn-outline-primary">
                <?= $localization->get('view_all') ?>
            </a>
        </div>
        <div class="card-body">
            <div class="text-center py-4">
                <i class="bi bi-clock fa-3x text-muted mb-3"></i>
                <h5 class="text-muted"><?= $localization->get('related_work_entries') ?></h5>
                <p class="text-muted"><?= $localization->get('related_work_entries_description') ?></p>
                <a href="/work/add?project=<?= urlencode($work['work_project']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('add') ?> <?= $localization->get('related') ?> <?= $localization->get('work') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Work view functionality
function duplicateWork() {
    if (confirm('<?= $localization->get('confirm_duplicate_work') ?>')) {
        // Create a new work entry with the same data
        const formData = new FormData();
        formData.append('work_date', '<?= $work['work_date'] ?>');
        formData.append('work_hours', '<?= $work['work_hours'] ?>');
        formData.append('work_description', '<?= addslashes($work['work_description']) ?> (Kopie)');
        formData.append('work_type', '<?= $work['work_type'] ?>');
        formData.append('work_rate', '<?= $work['work_rate'] ?>');
        formData.append('work_project', '<?= $work['work_project'] ?>');
        formData.append('work_client', '<?= $work['work_client'] ?>');
        formData.append('status', 'pending');

        fetch('/work/add', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/work/view/' + data.work_id;
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
        window.location.href = '/work/edit/<?= $work['id'] ?>';
    }
    
    // Ctrl/Cmd + B to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
        e.preventDefault();
        window.location.href = '/work/overview';
    }
    
    // Ctrl/Cmd + D to duplicate
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        duplicateWork();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 