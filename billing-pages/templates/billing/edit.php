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
                <i class="bi bi-pencil me-2"></i>
                <?= $localization->get('edit') ?> <?= $localization->get('invoice') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('update') ?> <?= $localization->get('invoice') ?> <?= $localization->get('information') ?></p>
        </div>
        <div>
            <a href="/billing/view/<?= $invoice['id'] ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-receipt me-2"></i>
                        <?= htmlspecialchars($invoice['invoice_number']) ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/billing/update/<?= $invoice['id'] ?>" id="invoiceForm">
                        <div class="row">
                            <!-- Invoice Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('invoice') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">
                                        <?= $localization->get('invoice_number') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                           value="<?= htmlspecialchars($invoice['invoice_number']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">
                                        <?= $localization->get('invoice_date') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" 
                                           value="<?= $invoice['invoice_date'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="due_date" class="form-label">
                                        <?= $localization->get('due_date') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" 
                                           value="<?= $invoice['due_date'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <?= $localization->get('status') ?>
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft" <?= $invoice['status'] === 'draft' ? 'selected' : '' ?>>
                                            <?= $localization->get('draft') ?>
                                        </option>
                                        <option value="sent" <?= $invoice['status'] === 'sent' ? 'selected' : '' ?>>
                                            <?= $localization->get('sent') ?>
                                        </option>
                                        <option value="paid" <?= $invoice['status'] === 'paid' ? 'selected' : '' ?>>
                                            <?= $localization->get('paid') ?>
                                        </option>
                                        <option value="overdue" <?= $invoice['status'] === 'overdue' ? 'selected' : '' ?>>
                                            <?= $localization->get('overdue') ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Client Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('client') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="client_name" class="form-label">
                                        <?= $localization->get('client_name') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" 
                                           value="<?= htmlspecialchars($invoice['client_name']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="client_email" class="form-label">
                                        <?= $localization->get('client_email') ?>
                                    </label>
                                    <input type="email" class="form-control" id="client_email" name="client_email" 
                                           value="<?= htmlspecialchars($invoice['client_email']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="client_address" class="form-label">
                                        <?= $localization->get('client_address') ?>
                                    </label>
                                    <textarea class="form-control" id="client_address" name="client_address" 
                                              rows="3"><?= htmlspecialchars($invoice['client_address']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Invoice Items -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><?= $localization->get('invoice') ?> <?= $localization->get('items') ?></h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    <?= $localization->get('add') ?> <?= $localization->get('item') ?>
                                </button>
                            </div>
                            
                            <div id="invoiceItems">
                                <?php foreach ($invoice_items as $index => $item): ?>
                                    <div class="row mb-3 item-row" data-index="<?= $index ?>">
                                        <div class="col-md-4">
                                            <label class="form-label"><?= $localization->get('description') ?></label>
                                            <input type="text" class="form-control" name="items[<?= $index ?>][description]" 
                                                   value="<?= htmlspecialchars($item['description']) ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label"><?= $localization->get('quantity') ?></label>
                                            <input type="number" class="form-control quantity" name="items[<?= $index ?>][quantity]" 
                                                   value="<?= $item['quantity'] ?>" step="1" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label"><?= $localization->get('unit_price') ?></label>
                                            <input type="number" class="form-control unit-price" name="items[<?= $index ?>][unit_price]" 
                                                   value="<?= $item['unit_price'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label"><?= $localization->get('total') ?></label>
                                            <input type="text" class="form-control item-total" value="<?= number_format($item['total'], 2) ?> €" readonly>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItem(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-6 text-end">
                                                <strong><?= $localization->get('subtotal') ?>:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span id="subtotal"><?= number_format($invoice['subtotal'], 2) ?> €</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-end">
                                                <strong><?= $localization->get('tax') ?> (19%):</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span id="tax"><?= number_format($invoice['tax'], 2) ?> €</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <strong><?= $localization->get('total') ?>:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <strong class="text-success fs-5" id="grandTotal"><?= number_format($invoice['total'], 2) ?> €</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="/billing/view/<?= $invoice['id'] ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <?= $localization->get('cancel') ?>
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?> <?= $localization->get('changes') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let itemIndex = <?= count($invoice_items) ?>;

// Add new item row
function addItem() {
    const itemsContainer = document.getElementById('invoiceItems');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-3 item-row';
    newRow.setAttribute('data-index', itemIndex);
    
    newRow.innerHTML = `
        <div class="col-md-4">
            <label class="form-label"><?= $localization->get('description') ?></label>
            <input type="text" class="form-control" name="items[${itemIndex}][description]" required>
        </div>
        <div class="col-md-2">
            <label class="form-label"><?= $localization->get('quantity') ?></label>
            <input type="number" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1" step="1" min="1" required>
        </div>
        <div class="col-md-2">
            <label class="form-label"><?= $localization->get('unit_price') ?></label>
            <input type="number" class="form-control unit-price" name="items[${itemIndex}][unit_price]" value="0.00" step="0.01" min="0" required>
        </div>
        <div class="col-md-2">
            <label class="form-label"><?= $localization->get('total') ?></label>
            <input type="text" class="form-control item-total" value="0.00 €" readonly>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItem(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    itemsContainer.appendChild(newRow);
    itemIndex++;
    
    // Add event listeners to new inputs
    const newInputs = newRow.querySelectorAll('.quantity, .unit-price');
    newInputs.forEach(input => {
        input.addEventListener('input', calculateItemTotal);
    });
}

// Remove item row
function removeItem(button) {
    const row = button.closest('.item-row');
    row.remove();
    calculateTotals();
}

// Calculate item total
function calculateItemTotal() {
    const row = this.closest('.item-row');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const total = quantity * unitPrice;
    
    row.querySelector('.item-total').value = total.toFixed(2) + ' €';
    calculateTotals();
}

// Calculate invoice totals
function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        subtotal += quantity * unitPrice;
    });
    
    const tax = subtotal * 0.19; // 19% tax
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' €';
    document.getElementById('tax').textContent = tax.toFixed(2) + ' €';
    document.getElementById('grandTotal').textContent = total.toFixed(2) + ' €';
}

// Form validation and functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('invoiceForm');
    
    // Add event listeners to existing inputs
    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.addEventListener('input', calculateItemTotal);
    });
    
    // Real-time validation
    const requiredInputs = form.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // Auto-save draft functionality
    let autoSaveTimer;
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                saveDraft();
            }, 2000);
        });
    });
    
    function saveDraft() {
        const formData = new FormData(form);
        const draftData = {};
        
        for (let [key, value] of formData.entries()) {
            draftData[key] = value;
        }
        
        localStorage.setItem('invoice_draft_<?= $invoice['id'] ?>', JSON.stringify(draftData));
        
        // Show auto-save indicator
        const indicator = document.createElement('div');
        indicator.className = 'position-fixed bottom-0 end-0 m-3';
        indicator.innerHTML = `
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <?= $localization->get('draft_saved') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 3000);
    }
    
    // Load draft on page load
    const savedDraft = localStorage.getItem('invoice_draft_<?= $invoice['id'] ?>');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.value === '') {
                input.value = draftData[key];
            }
        });
        calculateTotals();
    }
    
    // Clear draft after successful save
    form.addEventListener('submit', function() {
        localStorage.removeItem('invoice_draft_<?= $invoice['id'] ?>');
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('invoiceForm').submit();
    }
    
    // Ctrl/Cmd + Z to cancel
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
        e.preventDefault();
        window.location.href = '/billing/view/<?= $invoice['id'] ?>';
    }
    
    // Ctrl/Cmd + Enter to add item
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        addItem();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 