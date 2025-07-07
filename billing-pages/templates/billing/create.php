<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-plus-circle me-2"></i>
                <?= $localization->get('create') ?> <?= $localization->get('invoice') ?>
            </h1>
            <a href="/billing" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back_to_invoices') ?>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?= $localization->get('invoice_details') ?></h5>
            </div>
            <div class="card-body">
                <form action="/billing/create" method="POST" id="invoiceForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_id" class="form-label">
                                    <?= $localization->get('client') ?> *
                                </label>
                                <select class="form-select" id="client_id" name="client_id" required>
                                    <option value=""><?= $localization->get('select_client') ?></option>
                                    <?php foreach ($companies as $company): ?>
                                        <option value="<?= $company['id'] ?>">
                                            <?= htmlspecialchars($company['company_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">
                                    <?= $localization->get('invoice_date') ?> *
                                </label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">
                                    <?= $localization->get('due_date') ?>
                                </label>
                                <input type="date" class="form-control" id="due_date" name="due_date" 
                                       value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_terms" class="form-label">
                                    <?= $localization->get('payment_terms') ?>
                                </label>
                                <select class="form-select" id="payment_terms" name="payment_terms">
                                    <option value="30"><?= $localization->get('net_30_days') ?></option>
                                    <option value="14"><?= $localization->get('net_14_days') ?></option>
                                    <option value="7"><?= $localization->get('net_7_days') ?></option>
                                    <option value="0"><?= $localization->get('immediate') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <?= $localization->get('notes') ?>
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="<?= $localization->get('invoice_notes_placeholder') ?>"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Work Entries Selection -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?= $localization->get('select_work_entries') ?></h5>
            </div>
            <div class="card-body">
                <?php if (empty($workEntries)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-briefcase fs-1 text-muted mb-3"></i>
                        <h6 class="text-muted"><?= $localization->get('no_unbilled_work') ?></h6>
                        <p class="text-muted"><?= $localization->get('no_unbilled_work_description') ?></p>
                        <a href="/work/add" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            <?= $localization->get('add_work_entry') ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th><?= $localization->get('date') ?></th>
                                    <th><?= $localization->get('client') ?></th>
                                    <th><?= $localization->get('description') ?></th>
                                    <th><?= $localization->get('hours') ?></th>
                                    <th><?= $localization->get('rate') ?></th>
                                    <th><?= $localization->get('total') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($workEntries as $work): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="work_entries[]" 
                                                   value="<?= $work['id'] ?>" 
                                                   class="form-check-input work-entry-checkbox"
                                                   data-amount="<?= $work['work_total'] ?>">
                                        </td>
                                        <td><?= date('d.m.Y', strtotime($work['work_date'])) ?></td>
                                        <td><?= htmlspecialchars($work['company_name'] ?? $localization->get('no_client')) ?></td>
                                        <td><?= htmlspecialchars($work['work_description']) ?></td>
                                        <td><?= $work['work_hours'] ?></td>
                                        <td>€<?= number_format($work['work_rate'], 2) ?></td>
                                        <td>€<?= number_format($work['work_total'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Invoice Summary -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 1rem;">
            <div class="card-header">
                <h5 class="mb-0"><?= $localization->get('invoice_summary') ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><?= $localization->get('selected_items') ?>:</div>
                    <div class="col-6 text-end" id="selectedCount">0</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><?= $localization->get('subtotal') ?>:</div>
                    <div class="col-6 text-end" id="subtotal">€0.00</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><?= $localization->get('tax') ?> (19%):</div>
                    <div class="col-6 text-end" id="tax">€0.00</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6"><strong><?= $localization->get('total') ?>:</strong></div>
                    <div class="col-6 text-end"><strong id="total">€0.00</strong></div>
                </div>
                
                <button type="submit" form="invoiceForm" class="btn btn-primary w-100" 
                        id="createInvoiceBtn" disabled>
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('create_invoice') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.work-entry-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSummary();
});

// Individual checkbox change
document.querySelectorAll('.work-entry-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSummary);
});

// Update invoice summary
function updateSummary() {
    const checkboxes = document.querySelectorAll('.work-entry-checkbox:checked');
    const selectedCount = checkboxes.length;
    let subtotal = 0;
    
    checkboxes.forEach(checkbox => {
        subtotal += parseFloat(checkbox.dataset.amount);
    });
    
    const tax = subtotal * 0.19;
    const total = subtotal + tax;
    
    document.getElementById('selectedCount').textContent = selectedCount;
    document.getElementById('subtotal').textContent = '€' + subtotal.toFixed(2);
    document.getElementById('tax').textContent = '€' + tax.toFixed(2);
    document.getElementById('total').textContent = '€' + total.toFixed(2);
    
    // Enable/disable create button
    document.getElementById('createInvoiceBtn').disabled = selectedCount === 0;
}

// Auto-calculate due date based on payment terms
document.getElementById('payment_terms').addEventListener('change', function() {
    const invoiceDate = document.getElementById('invoice_date').value;
    if (invoiceDate) {
        const dueDate = new Date(invoiceDate);
        dueDate.setDate(dueDate.getDate() + parseInt(this.value));
        document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];
    }
});

// Initialize summary
updateSummary();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 