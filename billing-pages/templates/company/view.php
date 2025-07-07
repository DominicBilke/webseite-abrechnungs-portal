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
                <i class="bi bi-building me-2"></i>
                <?= htmlspecialchars($company['company_name']) ?>
            </h1>
            <p class="text-muted"><?= $localization->get('company') ?> <?= $localization->get('details') ?> <?= $localization->get('and') ?> <?= $localization->get('related') ?> <?= $localization->get('invoices') ?></p>
        </div>
        <div>
            <a href="/company/edit/<?= $company['id'] ?>" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>
                <?= $localization->get('edit') ?>
            </a>
            <a href="/company/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Company Details -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        <?= $localization->get('company') ?> <?= $localization->get('information') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_name') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($company['company_name']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_contact') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($company['company_contact']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_email') ?></label>
                                <p class="mb-0">
                                    <a href="mailto:<?= htmlspecialchars($company['company_email']) ?>">
                                        <?= htmlspecialchars($company['company_email']) ?>
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_phone') ?></label>
                                <p class="mb-0">
                                    <a href="tel:<?= htmlspecialchars($company['company_phone']) ?>">
                                        <?= htmlspecialchars($company['company_phone']) ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_address') ?></label>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($company['company_address'])) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_vat') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($company['company_vat']) ?: '-' ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_registration') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($company['company_registration']) ?: '-' ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('company_bank') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($company['company_bank']) ?: '-' ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $company['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= $localization->get($company['status']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                                <p class="mb-0"><?= date('d.m.Y H:i', strtotime($company['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Statistics -->
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
                                <h4 class="text-primary mb-1"><?= count($invoices) ?></h4>
                                <small class="text-muted"><?= $localization->get('total') ?> <?= $localization->get('invoices') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">
                                    <?= number_format(array_reduce($invoices, function($carry, $invoice) {
                                        return $carry + $invoice['total'];
                                    }, 0), 2) ?> €
                                </h4>
                                <small class="text-muted"><?= $localization->get('total') ?> <?= $localization->get('billing') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">
                                    <?= count(array_filter($invoices, function($invoice) {
                                        return $invoice['status'] === 'paid';
                                    })) ?>
                                </h4>
                                <small class="text-muted"><?= $localization->get('paid') ?> <?= $localization->get('invoices') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">
                                    <?= count(array_filter($invoices, function($invoice) {
                                        return $invoice['status'] === 'pending';
                                    })) ?>
                                </h4>
                                <small class="text-muted"><?= $localization->get('pending') ?> <?= $localization->get('invoices') ?></small>
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
                        <a href="/billing/generate/company/<?= $company['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-receipt me-2"></i>
                            <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                        </a>
                        <a href="/work/add?client=<?= $company['id'] ?>" class="btn btn-outline-success">
                            <i class="bi bi-clock me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('work') ?>
                        </a>
                        <a href="/money/add?client=<?= $company['id'] ?>" class="btn btn-outline-info">
                            <i class="bi bi-cash-coin me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('money') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Invoices -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-receipt me-2"></i>
                <?= $localization->get('related') ?> <?= $localization->get('invoices') ?>
            </h6>
            <a href="/billing?client=<?= $company['id'] ?>" class="btn btn-sm btn-outline-primary">
                <?= $localization->get('view_all') ?>
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($invoices)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('invoices') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('no_invoices_for_this_company') ?></p>
                    <a href="/billing/generate/company/<?= $company['id'] ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= $localization->get('generate') ?> <?= $localization->get('first') ?> <?= $localization->get('invoice') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><?= $localization->get('invoice_number') ?></th>
                                <th><?= $localization->get('invoice_date') ?></th>
                                <th><?= $localization->get('due_date') ?></th>
                                <th><?= $localization->get('total') ?></th>
                                <th><?= $localization->get('status') ?></th>
                                <th class="text-end"><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                                    </td>
                                    <td><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></td>
                                    <td><?= date('d.m.Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <strong class="text-success"><?= number_format($invoice['total'], 2) ?> €</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                            <?= $localization->get($invoice['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="/billing/view/<?= $invoice['id'] ?>" class="btn btn-outline-primary btn-sm" title="<?= $localization->get('view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="/billing/edit/<?= $invoice['id'] ?>" class="btn btn-outline-secondary btn-sm" title="<?= $localization->get('edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/billing/download/<?= $invoice['id'] ?>" class="btn btn-outline-success btn-sm" title="<?= $localization->get('download') ?>">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Company view functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality here
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to edit
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '/company/edit/<?= $company['id'] ?>';
    }
    
    // Ctrl/Cmd + B to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
        e.preventDefault();
        window.location.href = '/company/overview';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 