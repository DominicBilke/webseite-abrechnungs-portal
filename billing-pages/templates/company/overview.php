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
                <?= $localization->get('companies') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('manage') ?> <?= $localization->get('companies') ?> <?= $localization->get('and') ?> <?= $localization->get('view') ?> <?= $localization->get('billing') ?> <?= $localization->get('data') ?></p>
        </div>
        <div>
            <a href="/company/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('add') ?> <?= $localization->get('company') ?>
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
                                <?= $localization->get('total') ?> <?= $localization->get('companies') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($pagination['total_records']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-gray-300"></i>
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
                                <?= $localization->get('active') ?> <?= $localization->get('companies') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($companies, function($carry, $company) {
                                    return $carry + ($company['status'] === 'active' ? 1 : 0);
                                }, 0)) ?>
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <?= $localization->get('this') ?> <?= $localization->get('month') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($companies, function($carry, $company) {
                                    return $carry + (date('Y-m') === date('Y-m', strtotime($company['created_at'])) ? 1 : 0);
                                }, 0)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar fa-2x text-gray-300"></i>
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
                                <?= $localization->get('pending') ?> <?= $localization->get('billing') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format(array_reduce($companies, function($carry, $company) {
                                    return $carry + ($company['status'] === 'pending' ? 1 : 0);
                                }, 0)) ?>
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

    <!-- Companies Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>
                    <?= $localization->get('companies') ?> <?= $localization->get('list') ?>
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
            <?php if (empty($companies)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('companies') ?> <?= $localization->get('found') ?></h5>
                    <p class="text-muted"><?= $localization->get('start') ?> <?= $localization->get('by') ?> <?= $localization->get('adding') ?> <?= $localization->get('your') ?> <?= $localization->get('first') ?> <?= $localization->get('company') ?></p>
                    <a href="/company/add" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= $localization->get('add') ?> <?= $localization->get('company') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="companiesTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="sortable" data-sort="company_name">
                                    <?= $localization->get('company_name') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="company_contact">
                                    <?= $localization->get('company_contact') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="company_email">
                                    <?= $localization->get('company_email') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="company_phone">
                                    <?= $localization->get('company_phone') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="status">
                                    <?= $localization->get('status') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="sortable" data-sort="created_at">
                                    <?= $localization->get('created') ?>
                                    <i class="bi bi-arrow-down-up ms-1"></i>
                                </th>
                                <th scope="col" class="text-end"><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <?= strtoupper(substr($company['company_name'], 0, 1)) ?>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($company['company_name']) ?></h6>
                                                <?php if (!empty($company['company_vat'])): ?>
                                                    <small class="text-muted"><?= $localization->get('company_vat') ?>: <?= htmlspecialchars($company['company_vat']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($company['company_contact'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($company['company_email'])): ?>
                                            <a href="mailto:<?= htmlspecialchars($company['company_email']) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($company['company_email']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($company['company_phone'])): ?>
                                            <a href="tel:<?= htmlspecialchars($company['company_phone']) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($company['company_phone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $company['status'] === 'active' ? 'success' : ($company['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                            <?= $localization->get($company['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d.m.Y', strtotime($company['created_at'])) ?></span>
                                            <small class="text-muted"><?= date('H:i', strtotime($company['created_at'])) ?></small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewCompany(<?= $company['id'] ?>)" 
                                                    title="<?= $localization->get('view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="editCompany(<?= $company['id'] ?>)" 
                                                    title="<?= $localization->get('edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    onclick="generateInvoice(<?= $company['id'] ?>)" 
                                                    title="<?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteCompany(<?= $company['id'] ?>)" 
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
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>" <?= $pagination['current'] <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end < $pagination['total']): ?>
                                <?php if ($end < $pagination['total'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $pagination['total'] ?>"><?= $pagination['total'] ?></a>
                                </li>
                            <?php endif; ?>

                            <!-- Next -->
                            <li class="page-item <?= $pagination['current'] >= $pagination['total'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>" <?= $pagination['current'] >= $pagination['total'] ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
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
// Table sorting functionality
document.addEventListener('DOMContentLoaded', function() {
    const sortableHeaders = document.querySelectorAll('.sortable');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            const currentOrder = this.dataset.order || 'asc';
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            
            // Update URL with sort parameters
            const url = new URL(window.location);
            url.searchParams.set('sort', column);
            url.searchParams.set('order', newOrder);
            window.location.href = url.toString();
        });
    });
});

// Company actions
function viewCompany(id) {
    window.location.href = `/company/view/${id}`;
}

function editCompany(id) {
    window.location.href = `/company/edit/${id}`;
}

function generateInvoice(id) {
    if (confirm('<?= $localization->get('confirm_generate_invoice') ?>')) {
        window.location.href = `/billing/generate/company/${id}`;
    }
}

function deleteCompany(id) {
    if (confirm('<?= $localization->get('confirm_delete') ?>')) {
        fetch(`/company/delete/${id}`, {
            method: 'DELETE',
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
    const table = document.getElementById('companiesTable');
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
    a.download = 'companies_<?= date('Y-m-d') ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print functionality
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('companiesTable').cloneNode(true);
    
    // Remove action buttons for print
    const actionCells = table.querySelectorAll('td:last-child');
    actionCells.forEach(cell => cell.remove());
    
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('companies') ?> - <?= date('Y-m-d') ?></title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h1 { color: #333; }
                </style>
            </head>
            <body>
                <h1><?= $localization->get('companies') ?> - <?= date('Y-m-d') ?></h1>
                ${table.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 