<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-receipt me-2"></i>
                <?= $localization->get('billing') ?> - <?= $localization->get('overview') ?>
            </h1>
            <a href="/billing/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('create') ?> <?= $localization->get('invoice') ?>
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('total_invoices') ?></h6>
                        <h3 class="mb-0"><?= $pagination['total_records'] ?? 0 ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('paid_invoices') ?></h6>
                        <h3 class="mb-0"><?= $stats['paid_count'] ?? 0 ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('pending_invoices') ?></h6>
                        <h3 class="mb-0"><?= $stats['pending_count'] ?? 0 ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('total_amount') ?></h6>
                        <h3 class="mb-0">€<?= number_format($stats['total_amount'] ?? 0, 2) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-euro fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0"><?= $localization->get('invoices') ?></h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="<?= $localization->get('search_invoices') ?>">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($invoices)): ?>
            <div class="text-center py-5">
                <i class="bi bi-receipt fs-1 text-muted mb-3"></i>
                <h5 class="text-muted"><?= $localization->get('no_invoices') ?></h5>
                <p class="text-muted"><?= $localization->get('no_invoices_description') ?></p>
                <a href="/billing/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('create_first_invoice') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= $localization->get('invoice_number') ?></th>
                            <th><?= $localization->get('client') ?></th>
                            <th><?= $localization->get('date') ?></th>
                            <th><?= $localization->get('due_date') ?></th>
                            <th><?= $localization->get('amount') ?></th>
                            <th><?= $localization->get('status') ?></th>
                            <th><?= $localization->get('actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $invoice): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($invoice['company_name'] ?? $localization->get('no_client')) ?>
                                </td>
                                <td><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></td>
                                <td>
                                    <?php 
                                    $dueDate = strtotime($invoice['due_date']);
                                    $isOverdue = $dueDate < time() && $invoice['status'] !== 'paid';
                                    ?>
                                    <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                        <?= date('d.m.Y', $dueDate) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong>€<?= number_format($invoice['total'], 2) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($invoice['status']) {
                                        'draft' => 'bg-secondary',
                                        'sent' => 'bg-primary',
                                        'paid' => 'bg-success',
                                        'overdue' => 'bg-danger',
                                        'cancelled' => 'bg-dark',
                                        default => 'bg-secondary'
                                    };
                                    $statusText = $localization->get('status_' . $invoice['status']);
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/billing/view/<?= $invoice['id'] ?>" 
                                           class="btn btn-outline-primary" 
                                           title="<?= $localization->get('view') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/billing/edit/<?= $invoice['id'] ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="<?= $localization->get('edit') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/billing/pdf/<?= $invoice['id'] ?>" 
                                           class="btn btn-outline-info" 
                                           title="<?= $localization->get('download_pdf') ?>"
                                           target="_blank">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        <?php if ($invoice['status'] === 'draft'): ?>
                                            <a href="/billing/send/<?= $invoice['id'] ?>" 
                                               class="btn btn-outline-success" 
                                               title="<?= $localization->get('send') ?>"
                                               onclick="return confirm('<?= $localization->get('confirm_send_invoice') ?>')">
                                                <i class="bi bi-envelope"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($invoice['status'] === 'sent'): ?>
                                            <a href="/billing/mark-paid/<?= $invoice['id'] ?>" 
                                               class="btn btn-outline-success" 
                                               title="<?= $localization->get('mark_paid') ?>"
                                               onclick="return confirm('<?= $localization->get('confirm_mark_paid') ?>')">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="<?= $localization->get('delete') ?>"
                                                onclick="deleteInvoice(<?= $invoice['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total'] > 1): ?>
                <nav aria-label="<?= $localization->get('pagination') ?>">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">
                                    <?= $localization->get('previous') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">
                                    <?= $localization->get('next') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteInvoice(id) {
    if (confirm('<?= $localization->get('confirm_delete_invoice') ?>')) {
        fetch(`/billing/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= $localization->get('error_delete') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_delete') ?>');
        });
    }
}

// Search functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value;
    if (query.trim()) {
        window.location.href = `/billing/search?q=${encodeURIComponent(query)}`;
    }
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 