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
                <?= $localization->get('all') ?> <?= $localization->get('invoices') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('manage') ?> <?= $localization->get('and') ?> <?= $localization->get('track') ?> <?= $localization->get('all') ?> <?= $localization->get('invoices') ?></p>
        </div>
        <div>
            <a href="/billing/generate" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('invoices') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($pagination['total_records']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('amount') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($invoices, function($carry, $invoice) {
                                    return $carry + $invoice['total'];
                                }, 0), 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <?= $localization->get('paid') ?> <?= $localization->get('invoices') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($invoices, function($invoice) {
                                    return $invoice['status'] === 'paid';
                                })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <?= $localization->get('pending') ?> <?= $localization->get('invoices') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($invoices, function($invoice) {
                                    return $invoice['status'] === 'pending';
                                })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label"><?= $localization->get('status') ?></label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" <?= $filters['status'] === 'all' ? 'selected' : '' ?>><?= $localization->get('all_statuses') ?></option>
                        <option value="draft" <?= $filters['status'] === 'draft' ? 'selected' : '' ?>><?= $localization->get('draft') ?></option>
                        <option value="sent" <?= $filters['status'] === 'sent' ? 'selected' : '' ?>><?= $localization->get('sent') ?></option>
                        <option value="paid" <?= $filters['status'] === 'paid' ? 'selected' : '' ?>><?= $localization->get('paid') ?></option>
                        <option value="overdue" <?= $filters['status'] === 'overdue' ? 'selected' : '' ?>><?= $localization->get('overdue') ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="from_date" class="form-label"><?= $localization->get('from_date') ?></label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="<?= $filters['from_date'] ?>">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label"><?= $localization->get('to_date') ?></label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="<?= $filters['to_date'] ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        <?= $localization->get('filter') ?>
                    </button>
                    <a href="/billing" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        <?= $localization->get('reset') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>
                    <?= $localization->get('invoices') ?>
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV()">
                        <i class="bi bi-download me-1"></i>
                        <?= $localization->get('export') ?>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printTable()">
                        <i class="bi bi-printer me-1"></i>
                        <?= $localization->get('print') ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($invoices)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('invoices') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('start') ?> <?= $localization->get('by') ?> <?= $localization->get('generating') ?> <?= $localization->get('your') ?> <?= $localization->get('first') ?> <?= $localization->get('invoice') ?></p>
                    <a href="/billing/generate" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="invoicesTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="sortable" data-sort="invoice_number">
                                    <?= $localization->get('invoice_number') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="client_name">
                                    <?= $localization->get('client') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="invoice_date">
                                    <?= $localization->get('invoice_date') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="due_date">
                                    <?= $localization->get('due_date') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="total">
                                    <?= $localization->get('total') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="status">
                                    <?= $localization->get('status') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="text-end"><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($invoice['client_name']) ?></strong>
                                            <?php if (!empty($invoice['client_email'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($invoice['client_email']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></span>
                                            <small class="text-muted"><?= date('D', strtotime($invoice['invoice_date'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d.m.Y', strtotime($invoice['due_date'])) ?></span>
                                            <small class="text-muted"><?= date('D', strtotime($invoice['due_date'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success"><?= number_format($invoice['total'], 2) ?> €</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'pending' ? 'warning' : ($invoice['status'] === 'overdue' ? 'danger' : 'secondary')) ?>">
                                            <?= $localization->get($invoice['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewInvoice(<?= $invoice['id'] ?>)" 
                                                    title="<?= $localization->get('view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="editInvoice(<?= $invoice['id'] ?>)" 
                                                    title="<?= $localization->get('edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    onclick="downloadInvoice(<?= $invoice['id'] ?>)" 
                                                    title="<?= $localization->get('download') ?>">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteInvoice(<?= $invoice['id'] ?>)" 
                                                    title="<?= $localization->get('delete') ?>">
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
                    <nav aria-label="<?= $localization->get('pagination') ?>" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Previous -->
                            <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>" <?= $pagination['current'] <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $pagination['total']): ?>
                                <?php if ($end < $pagination['total'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $pagination['total'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>"><?= $pagination['total'] ?></a>
                                </li>
                            <?php endif; ?>

                            <!-- Next -->
                            <li class="page-item <?= $pagination['current'] >= $pagination['total'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>" <?= $pagination['current'] >= $pagination['total'] ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Invoice actions
function viewInvoice(id) {
    window.location.href = `/billing/view/${id}`;
}

function editInvoice(id) {
    window.location.href = `/billing/edit/${id}`;
}

function downloadInvoice(id) {
    window.location.href = `/billing/download/${id}`;
}

function deleteInvoice(id) {
    if (confirm('<?= $localization->get('confirm_delete') ?>')) {
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
                alert(data.error || '<?= $localization->get('error_delete') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_delete') ?>');
        });
    }
}

// Export functionality
function exportToCSV() {
    const table = document.getElementById('invoicesTable');
    const rows = table.querySelectorAll('tbody tr');
    let csv = [];
    
    // Add headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Add data rows
    rows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach((td, index) => {
            if (index < headers.length - 1) { // Skip actions column
                rowData.push(`"${td.textContent.trim()}"`);
            }
        });
        csv.push(rowData.join(','));
    });
    
    // Download CSV
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'invoices_<?= date('Y-m-d') ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print functionality
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('invoicesTable').cloneNode(true);
    
    // Remove action buttons for print
    const actionCells = table.querySelectorAll('td:last-child');
    actionCells.forEach(cell => cell.remove());
    
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('invoices') ?> - <?= date('Y-m-d') ?></title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h1 { color: #333; }
                </style>
            </head>
            <body>
                <h1><?= $localization->get('invoices') ?> - <?= date('Y-m-d') ?></h1>
                ${table.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 