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
                <?= $localization->get('money') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('manage') ?> <?= $localization->get('money') ?> <?= $localization->get('entries') ?> <?= $localization->get('and') ?> <?= $localization->get('track') ?> <?= $localization->get('income') ?> <?= $localization->get('and') ?> <?= $localization->get('expenses') ?></p>
        </div>
        <div>
            <a href="/money/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('add') ?> <?= $localization->get('money') ?>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('income') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($moneyEntries, function($carry, $money) {
                                    return $carry + ($money['amount'] > 0 ? $money['amount'] : 0);
                                }, 0), 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('expenses') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(abs(array_reduce($moneyEntries, function($carry, $money) {
                                    return $carry + ($money['amount'] < 0 ? $money['amount'] : 0);
                                }, 0)), 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-down-circle fa-2x text-gray-300"></i>
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
                                <?= $localization->get('net') ?> <?= $localization->get('balance') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($moneyEntries, function($carry, $money) {
                                    return $carry + $money['amount'];
                                }, 0), 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fa-2x text-gray-300"></i>
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
                                <?= $localization->get('total_entries') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($pagination['total_records']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-ul fa-2x text-gray-300"></i>
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
                <div class="col-md-2">
                    <label for="type" class="form-label"><?= $localization->get('type') ?></label>
                    <select class="form-select" id="type" name="type">
                        <option value="all"><?= $localization->get('all_types') ?></option>
                        <option value="income" <?= $filters['type'] === 'income' ? 'selected' : '' ?>><?= $localization->get('income') ?></option>
                        <option value="expense" <?= $filters['type'] === 'expense' ? 'selected' : '' ?>><?= $localization->get('expense') ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="category" class="form-label"><?= $localization->get('category') ?></label>
                    <select class="form-select" id="category" name="category">
                        <option value="all"><?= $localization->get('all_categories') ?></option>
                        <?php foreach ($filters['categories'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filters['category'] === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label"><?= $localization->get('status') ?></label>
                    <select class="form-select" id="status" name="status">
                        <option value="all"><?= $localization->get('all_statuses') ?></option>
                        <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>><?= $localization->get('pending') ?></option>
                        <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>><?= $localization->get('completed') ?></option>
                        <option value="cancelled" <?= $filters['status'] === 'cancelled' ? 'selected' : '' ?>><?= $localization->get('cancelled') ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="from_date" class="form-label"><?= $localization->get('from_date') ?></label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="<?= $filters['from_date'] ?>">
                </div>
                <div class="col-md-2">
                    <label for="to_date" class="form-label"><?= $localization->get('to_date') ?></label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="<?= $filters['to_date'] ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        <?= $localization->get('filter') ?>
                    </button>
                    <a href="/money/overview" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        <?= $localization->get('reset') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Money Entries Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>
                    <?= $localization->get('money') ?> <?= $localization->get('entries') ?>
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
            <?php if (empty($moneyEntries)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-cash-coin fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('money') ?> <?= $localization->get('entries') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('start') ?> <?= $localization->get('by') ?> <?= $localization->get('adding') ?> <?= $localization->get('your') ?> <?= $localization->get('first') ?> <?= $localization->get('money') ?> <?= $localization->get('entry') ?></p>
                    <a href="/money/add" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= $localization->get('add') ?> <?= $localization->get('money') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="moneyTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="sortable" data-sort="payment_date">
                                    <?= $localization->get('date') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="description">
                                    <?= $localization->get('description') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="amount">
                                    <?= $localization->get('amount') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="category">
                                    <?= $localization->get('category') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="payment_method">
                                    <?= $localization->get('payment_method') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="payment_status">
                                    <?= $localization->get('status') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="reference">
                                    <?= $localization->get('reference') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="text-end"><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($moneyEntries as $money): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d.m.Y', strtotime($money['payment_date'])) ?></span>
                                            <small class="text-muted"><?= date('D', strtotime($money['payment_date'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($money['description']) ?></strong>
                                            <?php if (!empty($money['notes'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($money['notes'], 0, 50)) ?><?= strlen($money['notes']) > 50 ? '...' : '' ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $money['amount'] >= 0 ? 'success' : 'danger' ?>">
                                            <?= ($money['amount'] >= 0 ? '+' : '') . number_format($money['amount'], 2) ?> €
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($money['category'])): ?>
                                            <span class="badge bg-info"><?= htmlspecialchars($money['category']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($money['payment_method'])): ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($money['payment_method']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $money['payment_status'] === 'completed' ? 'success' : ($money['payment_status'] === 'cancelled' ? 'danger' : 'warning') ?>">
                                            <?= $localization->get($money['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($money['reference'])): ?>
                                            <code><?= htmlspecialchars($money['reference']) ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewMoney(<?= $money['id'] ?>)" 
                                                    title="<?= $localization->get('view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="editMoney(<?= $money['id'] ?>)" 
                                                    title="<?= $localization->get('edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteMoney(<?= $money['id'] ?>)" 
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
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>&type=<?= $filters['type'] ?>&category=<?= $filters['category'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>" <?= $pagination['current'] <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1&type=<?= $filters['type'] ?>&category=<?= $filters['category'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&type=<?= $filters['type'] ?>&category=<?= $filters['category'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $pagination['total']): ?>
                                <?php if ($end < $pagination['total'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $pagination['total'] ?>&type=<?= $filters['type'] ?>&category=<?= $filters['category'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>"><?= $pagination['total'] ?></a>
                                </li>
                            <?php endif; ?>

                            <!-- Next -->
                            <li class="page-item <?= $pagination['current'] >= $pagination['total'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>&type=<?= $filters['type'] ?>&category=<?= $filters['category'] ?>&status=<?= $filters['status'] ?>&from_date=<?= $filters['from_date'] ?>&to_date=<?= $filters['to_date'] ?>" <?= $pagination['current'] >= $pagination['total'] ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
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
// Money actions
function viewMoney(id) {
    window.location.href = `/money/view/${id}`;
}

function editMoney(id) {
    window.location.href = `/money/edit/${id}`;
}

function deleteMoney(id) {
    if (confirm('<?= $localization->get('confirm_delete') ?>')) {
        fetch(`/money/delete/${id}`, {
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
    const table = document.getElementById('moneyTable');
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
    a.download = 'money_entries_<?= date('Y-m-d') ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print functionality
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('moneyTable').cloneNode(true);
    
    // Remove action buttons for print
    const actionCells = table.querySelectorAll('td:last-child');
    actionCells.forEach(cell => cell.remove());
    
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('money') ?> <?= $localization->get('entries') ?> - <?= date('Y-m-d') ?></title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h1 { color: #333; }
                </style>
            </head>
            <body>
                <h1><?= $localization->get('money') ?> <?= $localization->get('entries') ?> - <?= date('Y-m-d') ?></h1>
                ${table.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 