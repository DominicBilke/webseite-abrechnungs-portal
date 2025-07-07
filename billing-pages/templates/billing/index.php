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
                <?= $localization->get('nav_billing') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('manage') ?> <?= $localization->get('invoices') ?> <?= $localization->get('and') ?> <?= $localization->get('generate') ?> <?= $localization->get('reports') ?></p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="showGenerateInvoiceModal()">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
            </button>
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
                                <?= number_format($stats['total_invoices']) ?>
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
                                <?= number_format($stats['total_amount'], 2) ?> €
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
                                <?= number_format($stats['paid_invoices']) ?>
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
                                <?= $localization->get('pending') ?> <?= $localization->get('amount') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['pending_amount'], 2) ?> €
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

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        <?= $localization->get('quick_actions') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100" onclick="generateFromWork()">
                                <i class="bi bi-clock me-2"></i>
                                <?= $localization->get('from') ?> <?= $localization->get('work') ?>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-success w-100" onclick="generateFromCompany()">
                                <i class="bi bi-building me-2"></i>
                                <?= $localization->get('from') ?> <?= $localization->get('company') ?>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-info w-100" onclick="generateFromTour()">
                                <i class="bi bi-geo-alt me-2"></i>
                                <?= $localization->get('from') ?> <?= $localization->get('tour') ?>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-warning w-100" onclick="generateFromTask()">
                                <i class="bi bi-list-task me-2"></i>
                                <?= $localization->get('from') ?> <?= $localization->get('task') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            <?= $localization->get('recent') ?> <?= $localization->get('invoices') ?>
                        </h5>
                        <a href="/billing/all" class="btn btn-outline-secondary btn-sm">
                            <?= $localization->get('view_all') ?>
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($invoices)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted"><?= $localization->get('no') ?> <?= $localization->get('invoices') ?> <?= $localization->get('found') ?></h5>
                            <p class="text-muted"><?= $localization->get('start') ?> <?= $localization->get('by') ?> <?= $localization->get('generating') ?> <?= $localization->get('your') ?> <?= $localization->get('first') ?> <?= $localization->get('invoice') ?></p>
                            <button class="btn btn-primary" onclick="showGenerateInvoiceModal()">
                                <i class="bi bi-plus-circle me-1"></i>
                                <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col"><?= $localization->get('invoice_number') ?></th>
                                        <th scope="col"><?= $localization->get('client') ?></th>
                                        <th scope="col"><?= $localization->get('date') ?></th>
                                        <th scope="col"><?= $localization->get('due_date') ?></th>
                                        <th scope="col"><?= $localization->get('amount') ?></th>
                                        <th scope="col"><?= $localization->get('status') ?></th>
                                        <th scope="col" class="text-end"><?= $localization->get('actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($invoice['client_name']) ?></td>
                                            <td><?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></td>
                                            <td>
                                                <?php
                                                $dueDate = strtotime($invoice['due_date']);
                                                $today = time();
                                                $daysUntilDue = ceil(($dueDate - $today) / (60 * 60 * 24));
                                                $dueClass = $daysUntilDue < 0 ? 'text-danger' : ($daysUntilDue <= 7 ? 'text-warning' : 'text-muted');
                                                ?>
                                                <span class="<?= $dueClass ?>">
                                                    <?= date('d.m.Y', $dueDate) ?>
                                                    <?php if ($daysUntilDue < 0): ?>
                                                        <small class="badge bg-danger ms-1"><?= $localization->get('overdue') ?></small>
                                                    <?php elseif ($daysUntilDue <= 7): ?>
                                                        <small class="badge bg-warning ms-1"><?= $daysUntilDue ?> <?= $localization->get('days') ?></small>
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?= number_format($invoice['total'], 2) ?> €</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $invoice['status'] === 'paid' ? 'success' : ($invoice['status'] === 'sent' ? 'info' : ($invoice['status'] === 'overdue' ? 'danger' : 'secondary')) ?>">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Invoice Modal -->
<div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-labelledby="generateInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateInvoiceModalLabel">
                    <i class="bi bi-receipt me-2"></i>
                    <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="invoice_type" class="form-label"><?= $localization->get('invoice_type') ?></label>
                        <select class="form-select" id="invoice_type">
                            <option value=""><?= $localization->get('select') ?> <?= $localization->get('invoice_type') ?></option>
                            <option value="work"><?= $localization->get('work') ?> <?= $localization->get('invoice') ?></option>
                            <option value="company"><?= $localization->get('company') ?> <?= $localization->get('invoice') ?></option>
                            <option value="tour"><?= $localization->get('tour') ?> <?= $localization->get('invoice') ?></option>
                            <option value="task"><?= $localization->get('task') ?> <?= $localization->get('invoice') ?></option>
                            <option value="money"><?= $localization->get('money') ?> <?= $localization->get('invoice') ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="invoice_item" class="form-label"><?= $localization->get('select_item') ?></label>
                        <select class="form-select" id="invoice_item" disabled>
                            <option value=""><?= $localization->get('select') ?> <?= $localization->get('invoice_type') ?> <?= $localization->get('first') ?></option>
                        </select>
                    </div>
                </div>
                
                <div id="invoice_preview" class="mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <h6><?= $localization->get('invoice_preview') ?></h6>
                        <div id="preview_content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $localization->get('cancel') ?></button>
                <button type="button" class="btn btn-primary" id="generate_btn" disabled onclick="generateInvoice()">
                    <i class="bi bi-receipt me-1"></i>
                    <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Invoice generation functionality
document.addEventListener('DOMContentLoaded', function() {
    const invoiceTypeSelect = document.getElementById('invoice_type');
    const invoiceItemSelect = document.getElementById('invoice_item');
    const generateBtn = document.getElementById('generate_btn');
    
    invoiceTypeSelect.addEventListener('change', function() {
        const type = this.value;
        invoiceItemSelect.disabled = !type;
        invoiceItemSelect.innerHTML = '<option value=""><?= $localization->get('loading') ?>...</option>';
        
        if (type) {
            loadInvoiceItems(type);
        }
    });
    
    invoiceItemSelect.addEventListener('change', function() {
        const type = invoiceTypeSelect.value;
        const itemId = this.value;
        
        if (itemId) {
            loadInvoicePreview(type, itemId);
            generateBtn.disabled = false;
        } else {
            document.getElementById('invoice_preview').style.display = 'none';
            generateBtn.disabled = true;
        }
    });
});

function loadInvoiceItems(type) {
    fetch(`/billing/data/${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('invoice_item');
                select.innerHTML = '<option value=""><?= $localization->get('select') ?> <?= $localization->get('item') ?></option>';
                
                data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    
                    switch (type) {
                        case 'work':
                            option.textContent = `${item.work_description} - ${item.work_total}€ (${item.work_date})`;
                            break;
                        case 'company':
                            option.textContent = item.company_name;
                            break;
                        case 'tour':
                            option.textContent = `${item.tour_name} - ${item.tour_total}€ (${item.tour_date})`;
                            break;
                        case 'task':
                            option.textContent = `${item.task_name} - ${item.task_total}€`;
                            break;
                        case 'money':
                            option.textContent = `${item.description} - ${item.amount}€`;
                            break;
                    }
                    
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading invoice items:', error);
            document.getElementById('invoice_item').innerHTML = '<option value=""><?= $localization->get('error_loading') ?></option>';
        });
}

function loadInvoicePreview(type, itemId) {
    const previewDiv = document.getElementById('invoice_preview');
    const previewContent = document.getElementById('preview_content');
    
    // Show loading
    previewContent.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div><?= $localization->get('loading') ?>...';
    previewDiv.style.display = 'block';
    
    // Simulate preview loading (in real app, this would fetch actual data)
    setTimeout(() => {
        const select = document.getElementById('invoice_item');
        const selectedOption = select.options[select.selectedIndex];
        
        previewContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong><?= $localization->get('item') ?>:</strong><br>
                    ${selectedOption.textContent}
                </div>
                <div class="col-md-6">
                    <strong><?= $localization->get('type') ?>:</strong><br>
                    <?= $localization->get('${type}') ?> <?= $localization->get('invoice') ?>
                </div>
            </div>
        `;
    }, 500);
}

function generateInvoice() {
    const type = document.getElementById('invoice_type').value;
    const itemId = document.getElementById('invoice_item').value;
    
    if (type && itemId) {
        window.location.href = `/billing/generate/${type}/${itemId}`;
    }
}

// Quick action functions
function showGenerateInvoiceModal() {
    const modal = new bootstrap.Modal(document.getElementById('generateInvoiceModal'));
    modal.show();
}

function generateFromWork() {
    document.getElementById('invoice_type').value = 'work';
    document.getElementById('invoice_type').dispatchEvent(new Event('change'));
    showGenerateInvoiceModal();
}

function generateFromCompany() {
    document.getElementById('invoice_type').value = 'company';
    document.getElementById('invoice_type').dispatchEvent(new Event('change'));
    showGenerateInvoiceModal();
}

function generateFromTour() {
    document.getElementById('invoice_type').value = 'tour';
    document.getElementById('invoice_type').dispatchEvent(new Event('change'));
    showGenerateInvoiceModal();
}

function generateFromTask() {
    document.getElementById('invoice_type').value = 'task';
    document.getElementById('invoice_type').dispatchEvent(new Event('change'));
    showGenerateInvoiceModal();
}

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
    if (confirm('<?= $localization->get('confirm_delete_invoice') ?>')) {
        fetch(`/billing/delete/${id}`, {
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

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N to generate new invoice
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        showGenerateInvoiceModal();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 