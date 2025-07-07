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
                <i class="bi bi-receipt me-2"></i>
                <?= htmlspecialchars($invoice['invoice_number']) ?>
            </h1>
            <p class="text-muted"><?= $localization->get('invoice') ?> <?= $localization->get('details') ?> <?= $localization->get('and') ?> <?= $localization->get('information') ?></p>
        </div>
        <div>
            <a href="/billing/edit/<?= $invoice['id'] ?>" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>
                <?= $localization->get('edit') ?>
            </a>
            <a href="/billing/download/<?= $invoice['id'] ?>" class="btn btn-success me-2">
                <i class="bi bi-download me-1"></i>
                <?= $localization->get('download') ?>
            </a>
            <a href="/billing" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Invoice Details -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        <?= $localization->get('invoice') ?> <?= $localization->get('information') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('invoice_number') ?></label>
                                <p class="mb-0">
                                    <strong class="text-primary"><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('invoice_date') ?></label>
                                <p class="mb-0"><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('due_date') ?></label>
                                <p class="mb-0">
                                    <?= date('d.m.Y', strtotime($invoice['due_date'])) ?>
                                    <?php
                                    $daysUntilDue = (strtotime($invoice['due_date']) - time()) / (60 * 60 * 24);
                                    if ($daysUntilDue < 0): ?>
                                        <span class="badge bg-danger ms-2"><?= $localization->get('overdue') ?></span>
                                    <?php elseif ($daysUntilDue <= 7): ?>
                                        <span class="badge bg-warning ms-2"><?= $localization->get('due_soon') ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'pending' ? 'warning' : ($invoice['status'] === 'overdue' ? 'danger' : 'secondary')) ?>">
                                        <?= $localization->get($invoice['status']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('subtotal') ?></label>
                                <p class="mb-0"><?= number_format($invoice['subtotal'], 2) ?> €</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('tax') ?></label>
                                <p class="mb-0"><?= number_format($invoice['tax'], 2) ?> €</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('total') ?></label>
                                <p class="mb-0">
                                    <strong class="text-success fs-5"><?= number_format($invoice['total'], 2) ?> €</strong>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                                <p class="mb-0"><?= date('d.m.Y H:i', strtotime($invoice['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person me-2"></i>
                        <?= $localization->get('client') ?> <?= $localization->get('information') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('client_name') ?></label>
                                <p class="mb-0"><?= htmlspecialchars($invoice['client_name']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('client_email') ?></label>
                                <p class="mb-0">
                                    <a href="mailto:<?= htmlspecialchars($invoice['client_email']) ?>">
                                        <?= htmlspecialchars($invoice['client_email']) ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('client_address') ?></label>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($invoice['client_address'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Statistics -->
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
                                <h4 class="text-success mb-1"><?= number_format($invoice['total'], 2) ?> €</h4>
                                <small class="text-muted"><?= $localization->get('total') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1"><?= count($invoice_items) ?></h4>
                                <small class="text-muted"><?= $localization->get('items') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1"><?= date('d.m.Y', strtotime($invoice['due_date'])) ?></h4>
                                <small class="text-muted"><?= $localization->get('due_date') ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary mb-1"><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></h4>
                                <small class="text-muted"><?= $localization->get('invoice_date') ?></small>
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
                        <a href="/billing/edit/<?= $invoice['id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-2"></i>
                            <?= $localization->get('edit') ?> <?= $localization->get('invoice') ?>
                        </a>
                        <a href="/billing/download/<?= $invoice['id'] ?>" class="btn btn-success">
                            <i class="bi bi-download me-2"></i>
                            <?= $localization->get('download') ?> PDF
                        </a>
                        <button onclick="sendInvoice()" class="btn btn-primary">
                            <i class="bi bi-envelope me-2"></i>
                            <?= $localization->get('send') ?> <?= $localization->get('invoice') ?>
                        </button>
                        <?php if ($invoice['status'] !== 'paid'): ?>
                            <button onclick="markAsPaid()" class="btn btn-outline-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <?= $localization->get('mark_as_paid') ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-list-ul me-2"></i>
                <?= $localization->get('invoice') ?> <?= $localization->get('items') ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($invoice_items)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-list-ul fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('items') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('no_items_for_this_invoice') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><?= $localization->get('description') ?></th>
                                <th><?= $localization->get('quantity') ?></th>
                                <th><?= $localization->get('unit_price') ?></th>
                                <th><?= $localization->get('total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoice_items as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($item['description']) ?></strong>
                                        <?php if (!empty($item['notes'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($item['notes']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= number_format($item['unit_price'], 2) ?> €</td>
                                    <td>
                                        <strong class="text-success"><?= number_format($item['total'], 2) ?> €</strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong><?= $localization->get('subtotal') ?>:</strong></td>
                                <td><strong><?= number_format($invoice['subtotal'], 2) ?> €</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong><?= $localization->get('tax') ?>:</strong></td>
                                <td><strong><?= number_format($invoice['tax'], 2) ?> €</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong><?= $localization->get('total') ?>:</strong></td>
                                <td><strong class="text-success fs-5"><?= number_format($invoice['total'], 2) ?> €</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Invoice actions
function sendInvoice() {
    if (confirm('<?= $localization->get('confirm_send_invoice') ?>')) {
        fetch(`/billing/send/<?= $invoice['id'] ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('<?= $localization->get('invoice_sent_successfully') ?>');
                location.reload();
            } else {
                alert(data.error || '<?= $localization->get('error_send_invoice') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_send_invoice') ?>');
        });
    }
}

function markAsPaid() {
    if (confirm('<?= $localization->get('confirm_mark_as_paid') ?>')) {
        fetch(`/billing/mark-paid/<?= $invoice['id'] ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('<?= $localization->get('invoice_marked_as_paid') ?>');
                location.reload();
            } else {
                alert(data.error || '<?= $localization->get('error_mark_as_paid') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_mark_as_paid') ?>');
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to edit
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        window.location.href = '/billing/edit/<?= $invoice['id'] ?>';
    }
    
    // Ctrl/Cmd + D to download
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        window.location.href = '/billing/download/<?= $invoice['id'] ?>';
    }
    
    // Ctrl/Cmd + S to send
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        sendInvoice();
    }
    
    // Ctrl/Cmd + B to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
        e.preventDefault();
        window.location.href = '/billing';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 