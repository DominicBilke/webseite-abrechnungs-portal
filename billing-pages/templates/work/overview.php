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
                <?= $localization->get('work') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('manage') ?> <?= $localization->get('work') ?> <?= $localization->get('entries') ?> <?= $localization->get('and') ?> <?= $localization->get('track') ?> <?= $localization->get('time') ?></p>
        </div>
        <div>
            <a href="/work/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('add') ?> <?= $localization->get('work') ?>
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
                                <?= $localization->get('total') ?> <?= $localization->get('hours') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($workEntries, function($carry, $work) {
                                    return $carry + $work['work_hours'];
                                }, 0), 1) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
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
                                <?= $localization->get('total') ?> <?= $localization->get('earnings') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($workEntries, function($carry, $work) {
                                    return $carry + $work['work_total'];
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
                                <?= $localization->get('avg_hourly_rate') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $totalHours = array_reduce($workEntries, function($carry, $work) {
                                    return $carry + $work['work_hours'];
                                }, 0);
                                $totalEarnings = array_reduce($workEntries, function($carry, $work) {
                                    return $carry + $work['work_total'];
                                }, 0);
                                $avgRate = $totalHours > 0 ? $totalEarnings / $totalHours : 0;
                                echo number_format($avgRate, 2) . ' €';
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
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
                <div class="col-md-3">
                    <label for="month" class="form-label"><?= $localization->get('month') ?></label>
                    <select class="form-select" id="month" name="month">
                        <option value="all"><?= $localization->get('all_months') ?></option>
                        <?php foreach ($filters['months'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filters['month'] === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="project" class="form-label"><?= $localization->get('project') ?></label>
                    <select class="form-select" id="project" name="project">
                        <option value="all"><?= $localization->get('all_projects') ?></option>
                        <?php foreach ($filters['projects'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filters['project'] === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label"><?= $localization->get('status') ?></label>
                    <select class="form-select" id="status" name="status">
                        <option value="all"><?= $localization->get('all_statuses') ?></option>
                        <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>><?= $localization->get('pending') ?></option>
                        <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>><?= $localization->get('completed') ?></option>
                        <option value="billed" <?= $filters['status'] === 'billed' ? 'selected' : '' ?>><?= $localization->get('billed') ?></option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        <?= $localization->get('filter') ?>
                    </button>
                    <a href="/work/overview" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        <?= $localization->get('reset') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Work Entries Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>
                    <?= $localization->get('work') ?> <?= $localization->get('entries') ?>
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
            <?php if (empty($workEntries)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-clock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('work') ?> <?= $localization->get('entries') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('start') ?> <?= $localization->get('by') ?> <?= $localization->get('adding') ?> <?= $localization->get('your') ?> <?= $localization->get('first') ?> <?= $localization->get('work') ?> <?= $localization->get('entry') ?></p>
                    <a href="/work/add" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= $localization->get('add') ?> <?= $localization->get('work') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="workTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="sortable" data-sort="work_date">
                                    <?= $localization->get('date') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="work_description">
                                    <?= $localization->get('description') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="work_hours">
                                    <?= $localization->get('hours') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="work_rate">
                                    <?= $localization->get('rate') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="work_total">
                                    <?= $localization->get('total') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="work_project">
                                    <?= $localization->get('project') ?>
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
                            <?php foreach ($workEntries as $work): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d.m.Y', strtotime($work['work_date'])) ?></span>
                                            <small class="text-muted"><?= date('D', strtotime($work['work_date'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($work['work_description']) ?></strong>
                                            <?php if (!empty($work['work_client'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($work['work_client']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= number_format($work['work_hours'], 2) ?> <?= $localization->get('hours') ?></span>
                                    </td>
                                    <td><?= number_format($work['work_rate'], 2) ?> €/<?= $localization->get('hour') ?></td>
                                    <td>
                                        <strong class="text-success"><?= number_format($work['work_total'], 2) ?> €</strong>
                                    </td>
                                    <td>
                                        <?php if (!empty($work['work_project'])): ?>
                                            <span class="badge bg-info"><?= htmlspecialchars($work['work_project']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $work['status'] === 'completed' ? 'success' : ($work['status'] === 'billed' ? 'info' : 'warning') ?>">
                                            <?= $localization->get($work['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewWork(<?= $work['id'] ?>)" 
                                                    title="<?= $localization->get('view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="editWork(<?= $work['id'] ?>)" 
                                                    title="<?= $localization->get('edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    onclick="generateInvoice(<?= $work['id'] ?>)" 
                                                    title="<?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteWork(<?= $work['id'] ?>)" 
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
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>&month=<?= $filters['month'] ?>&project=<?= $filters['project'] ?>&status=<?= $filters['status'] ?>" <?= $pagination['current'] <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1&month=<?= $filters['month'] ?>&project=<?= $filters['project'] ?>&status=<?= $filters['status'] ?>">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&month=<?= $filters['month'] ?>&project=<?= $filters['project'] ?>&status=<?= $filters['status'] ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $pagination['total']): ?>
                                <?php if ($end < $pagination['total'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $pagination['total'] ?>&month=<?= $filters['month'] ?>&project=<?= $filters['project'] ?>&status=<?= $filters['status'] ?>"><?= $pagination['total'] ?></a>
                                </li>
                            <?php endif; ?>

                            <!-- Next -->
                            <li class="page-item <?= $pagination['current'] >= $pagination['total'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>&month=<?= $filters['month'] ?>&project=<?= $filters['project'] ?>&status=<?= $filters['status'] ?>" <?= $pagination['current'] >= $pagination['total'] ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
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
// Work actions
function viewWork(id) {
    window.location.href = `/work/view/${id}`;
}

function editWork(id) {
    window.location.href = `/work/edit/${id}`;
}

function generateInvoice(id) {
    if (confirm('<?= $localization->get('confirm_generate_invoice') ?>')) {
        window.location.href = `/billing/generate/work/${id}`;
    }
}

function deleteWork(id) {
    if (confirm('<?= $localization->get('confirm_delete') ?>')) {
        fetch(`/work/delete/${id}`, {
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
    const table = document.getElementById('workTable');
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
    a.download = 'work_entries_<?= date('Y-m-d') ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print functionality
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('workTable').cloneNode(true);
    
    // Remove action buttons for print
    const actionCells = table.querySelectorAll('td:last-child');
    actionCells.forEach(cell => cell.remove());
    
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('work') ?> <?= $localization->get('entries') ?> - <?= date('Y-m-d') ?></title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h1 { color: #333; }
                </style>
            </head>
            <body>
                <h1><?= $localization->get('work') ?> <?= $localization->get('entries') ?> - <?= date('Y-m-d') ?></h1>
                ${table.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 