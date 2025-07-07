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
                <?= $localization->get('add') ?> <?= $localization->get('money') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('form_enter') ?> <?= $localization->get('money') ?> <?= $localization->get('form_required') ?></p>
        </div>
        <div>
            <a href="/money/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <!-- Money Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?= $localization->get('money') ?> <?= $localization->get('form_required') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/money/add" data-validate="true">
                        <div class="row">
                            <!-- Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">
                                    <?= $localization->get('amount') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" placeholder="0.00" required>
                                </div>
                                <div class="form-text">
                                    <span class="text-success" id="income_indicator" style="display: none;">
                                        <i class="bi bi-arrow-up-circle me-1"></i>
                                        <?= $localization->get('income') ?>
                                    </span>
                                    <span class="text-danger" id="expense_indicator" style="display: none;">
                                        <i class="bi bi-arrow-down-circle me-1"></i>
                                        <?= $localization->get('expense') ?>
                                    </span>
                                </div>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('amount')]) ?>
                                </div>
                            </div>

                            <!-- Currency -->
                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">
                                    <?= $localization->get('currency') ?>
                                </label>
                                <select class="form-select" id="currency" name="currency">
                                    <option value="EUR" selected>EUR - Euro</option>
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CHF">CHF - Swiss Franc</option>
                                    <option value="JPY">JPY - Japanese Yen</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                    <option value="AUD">AUD - Australian Dollar</option>
                                </select>
                            </div>

                            <!-- Payment Date -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">
                                    <?= $localization->get('payment_date') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                       value="<?= date('Y-m-d') ?>" required>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('payment_date')]) ?>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">
                                    <?= $localization->get('payment_status') ?>
                                </label>
                                <select class="form-select" id="payment_status" name="payment_status">
                                    <option value="pending" selected><?= $localization->get('pending') ?></option>
                                    <option value="completed"><?= $localization->get('completed') ?></option>
                                    <option value="cancelled"><?= $localization->get('cancelled') ?></option>
                                    <option value="overdue"><?= $localization->get('overdue') ?></option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">
                                    <?= $localization->get('category') ?>
                                </label>
                                <select class="form-select" id="category" name="category">
                                    <option value=""><?= $localization->get('select') ?> <?= $localization->get('category') ?></option>
                                    <optgroup label="<?= $localization->get('income_categories') ?>">
                                        <option value="salary"><?= $localization->get('salary') ?></option>
                                        <option value="freelance"><?= $localization->get('freelance') ?></option>
                                        <option value="consulting"><?= $localization->get('consulting') ?></option>
                                        <option value="investment"><?= $localization->get('investment') ?></option>
                                        <option value="other_income"><?= $localization->get('other_income') ?></option>
                                    </optgroup>
                                    <optgroup label="<?= $localization->get('expense_categories') ?>">
                                        <option value="rent"><?= $localization->get('rent') ?></option>
                                        <option value="utilities"><?= $localization->get('utilities') ?></option>
                                        <option value="food"><?= $localization->get('food') ?></option>
                                        <option value="transportation"><?= $localization->get('transportation') ?></option>
                                        <option value="entertainment"><?= $localization->get('entertainment') ?></option>
                                        <option value="healthcare"><?= $localization->get('healthcare') ?></option>
                                        <option value="insurance"><?= $localization->get('insurance') ?></option>
                                        <option value="taxes"><?= $localization->get('taxes') ?></option>
                                        <option value="other_expense"><?= $localization->get('other_expense') ?></option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">
                                    <?= $localization->get('payment_method') ?>
                                </label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value=""><?= $localization->get('select') ?> <?= $localization->get('payment_method') ?></option>
                                    <option value="cash"><?= $localization->get('cash') ?></option>
                                    <option value="bank_transfer"><?= $localization->get('bank_transfer') ?></option>
                                    <option value="credit_card"><?= $localization->get('credit_card') ?></option>
                                    <option value="debit_card"><?= $localization->get('debit_card') ?></option>
                                    <option value="paypal"><?= $localization->get('paypal') ?></option>
                                    <option value="stripe"><?= $localization->get('stripe') ?></option>
                                    <option value="check"><?= $localization->get('check') ?></option>
                                    <option value="other"><?= $localization->get('other') ?></option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">
                                    <?= $localization->get('description') ?> <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3" 
                                          placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('description') ?>" required></textarea>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('description')]) ?>
                                </div>
                            </div>

                            <!-- Reference -->
                            <div class="col-md-6 mb-3">
                                <label for="reference" class="form-label">
                                    <?= $localization->get('reference') ?>
                                </label>
                                <input type="text" class="form-control" id="reference" name="reference" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('reference') ?>">
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">
                                    <?= $localization->get('notes') ?>
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" 
                                          placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('notes') ?>"></textarea>
                            </div>

                            <!-- Summary -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong><?= $localization->get('amount') ?>:</strong>
                                            <span id="display_amount">0.00 €</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?= $localization->get('currency') ?>:</strong>
                                            <span id="display_currency">EUR</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?= $localization->get('category') ?>:</strong>
                                            <span id="display_category">-</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?= $localization->get('payment_status') ?>:</strong>
                                            <span id="display_status"><?= $localization->get('pending') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="/money/overview" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                <?= $localization->get('cancel') ?>
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-info me-2" onclick="calculateTax()">
                                    <i class="bi bi-calculator me-1"></i>
                                    <?= $localization->get('calculate_tax') ?>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Calculator Modal -->
    <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taxModalLabel">
                        <i class="bi bi-calculator me-2"></i>
                        <?= $localization->get('tax_calculator') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gross_amount" class="form-label"><?= $localization->get('gross_amount') ?></label>
                            <input type="number" class="form-control" id="gross_amount" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tax_rate" class="form-label"><?= $localization->get('tax_rate') ?> (%)</label>
                            <input type="number" class="form-control" id="tax_rate" value="19" step="0.01">
                        </div>
                    </div>
                    <div class="alert alert-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><?= $localization->get('net_amount') ?>:</strong>
                                <div id="net_amount">0.00 €</div>
                            </div>
                            <div class="col-md-4">
                                <strong><?= $localization->get('tax_amount') ?>:</strong>
                                <div id="tax_amount">0.00 €</div>
                            </div>
                            <div class="col-md-4">
                                <strong><?= $localization->get('gross_amount') ?>:</strong>
                                <div id="gross_result">0.00 €</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $localization->get('close') ?></button>
                    <button type="button" class="btn btn-primary" onclick="applyTaxCalculation()"><?= $localization->get('apply') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and calculation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-validate="true"]');
    const amountInput = document.getElementById('amount');
    const currencySelect = document.getElementById('currency');
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('payment_status');
    const descriptionInput = document.getElementById('description');
    
    // Real-time updates
    function updateDisplay() {
        const amount = parseFloat(amountInput.value) || 0;
        const currency = currencySelect.value;
        const category = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
        const status = statusSelect.options[statusSelect.selectedIndex]?.text || '<?= $localization->get('pending') ?>';
        
        document.getElementById('display_amount').textContent = amount.toFixed(2) + ' ' + currency;
        document.getElementById('display_currency').textContent = currency;
        document.getElementById('display_category').textContent = category;
        document.getElementById('display_status').textContent = status;
        
        // Show income/expense indicator
        const incomeIndicator = document.getElementById('income_indicator');
        const expenseIndicator = document.getElementById('expense_indicator');
        
        if (amount > 0) {
            incomeIndicator.style.display = 'inline';
            expenseIndicator.style.display = 'none';
        } else if (amount < 0) {
            incomeIndicator.style.display = 'none';
            expenseIndicator.style.display = 'inline';
        } else {
            incomeIndicator.style.display = 'none';
            expenseIndicator.style.display = 'none';
        }
    }
    
    amountInput.addEventListener('input', updateDisplay);
    currencySelect.addEventListener('change', updateDisplay);
    categorySelect.addEventListener('change', updateDisplay);
    statusSelect.addEventListener('change', updateDisplay);
    
    // Form validation
    const requiredInputs = form.querySelectorAll('input[required], textarea[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredInputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showAlert('<?= $localization->get('error_validation') ?>', 'danger');
        }
    });
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            isValid = false;
        }
        
        if (field.type === 'number' && value) {
            const numValue = parseFloat(value);
            if (isNaN(numValue)) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (isValid && value) {
            field.classList.add('is-valid');
        }
        
        return isValid;
    }
    
    // Initial display update
    updateDisplay();
});

// Tax calculator functionality
function calculateTax() {
    const taxModal = new bootstrap.Modal(document.getElementById('taxModal'));
    taxModal.show();
    
    // Pre-fill with current amount
    const currentAmount = document.getElementById('amount').value;
    if (currentAmount) {
        document.getElementById('gross_amount').value = currentAmount;
        updateTaxCalculation();
    }
}

function updateTaxCalculation() {
    const grossAmount = parseFloat(document.getElementById('gross_amount').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 19;
    
    const netAmount = grossAmount / (1 + taxRate / 100);
    const taxAmount = grossAmount - netAmount;
    
    document.getElementById('net_amount').textContent = netAmount.toFixed(2) + ' €';
    document.getElementById('tax_amount').textContent = taxAmount.toFixed(2) + ' €';
    document.getElementById('gross_result').textContent = grossAmount.toFixed(2) + ' €';
}

function applyTaxCalculation() {
    const netAmount = parseFloat(document.getElementById('net_amount').textContent);
    document.getElementById('amount').value = netAmount.toFixed(2);
    document.getElementById('amount').dispatchEvent(new Event('input'));
    
    // Add tax info to notes
    const notesInput = document.getElementById('notes');
    const taxRate = document.getElementById('tax_rate').value;
    const taxAmount = document.getElementById('tax_amount').textContent;
    
    if (notesInput.value) {
        notesInput.value += `\nTax calculation: ${taxRate}% tax = ${taxAmount}`;
    } else {
        notesInput.value = `Tax calculation: ${taxRate}% tax = ${taxAmount}`;
    }
    
    // Close modal
    const taxModal = bootstrap.Modal.getInstance(document.getElementById('taxModal'));
    taxModal.hide();
}

// Event listeners for tax calculator
document.getElementById('gross_amount').addEventListener('input', updateTaxCalculation);
document.getElementById('tax_rate').addEventListener('input', updateTaxCalculation);

// Utility functions
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Auto-save functionality
let autoSaveTimeout;
const form = document.querySelector('form[data-validate="true"]');
const inputs = form.querySelectorAll('input, textarea, select');

inputs.forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic would go here
            console.log('Auto-saving money entry...');
        }, 3000);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form[data-validate="true"]').submit();
    }
    
    // Ctrl/Cmd + T to open tax calculator
    if ((e.ctrlKey || e.metaKey) && e.key === 't') {
        e.preventDefault();
        calculateTax();
    }
});

// Category suggestions based on amount
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    const categorySelect = document.getElementById('category');
    
    // Auto-suggest category based on amount
    if (amount > 0) {
        // Positive amount - suggest income categories
        if (amount > 1000 && categorySelect.value === '') {
            categorySelect.value = 'salary';
        }
    } else if (amount < 0) {
        // Negative amount - suggest expense categories
        if (Math.abs(amount) > 500 && categorySelect.value === '') {
            categorySelect.value = 'rent';
        }
    }
    
    // Update display
    categorySelect.dispatchEvent(new Event('change'));
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 