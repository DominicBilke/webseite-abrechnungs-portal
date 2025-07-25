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
                <i class="bi bi-plus-circle me-2"></i>
                <?= $localization->get('add') ?> <?= $localization->get('money') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('add') ?> <?= $localization->get('new') ?> <?= $localization->get('money') ?> <?= $localization->get('entry') ?></p>
        </div>
        <div>
            <a href="/money/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-cash-coin me-2"></i>
                        <?= $localization->get('new') ?> <?= $localization->get('money') ?> <?= $localization->get('entry') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/money/add" id="moneyForm">
                        <div class="row">
                            <!-- Money Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('money') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="amount" class="form-label">
                                        <?= $localization->get('amount') ?> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               value="0.00" step="0.01" required>
                                        <select class="form-select" id="currency" name="currency" style="max-width: 100px;">
                                            <option value="EUR" selected>EUR</option>
                                            <option value="USD">USD</option>
                                            <option value="GBP">GBP</option>
                                            <option value="CHF">CHF</option>
                                        </select>
                                    </div>
                                    <div class="form-text">
                                        <?= $localization->get('positive_for_income') ?>, <?= $localization->get('negative_for_expense') ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <?= $localization->get('description') ?> <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="3" required placeholder="<?= $localization->get('describe_transaction') ?>"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">
                                        <?= $localization->get('payment_date') ?>
                                    </label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">
                                        <?= $localization->get('payment_method') ?>
                                    </label>
                                    <select class="form-select" id="payment_method" name="payment_method">
                                        <option value=""><?= $localization->get('select_payment_method') ?></option>
                                        <option value="bank_transfer"><?= $localization->get('bank_transfer') ?></option>
                                        <option value="credit_card"><?= $localization->get('credit_card') ?></option>
                                        <option value="paypal"><?= $localization->get('paypal') ?></option>
                                        <option value="cash"><?= $localization->get('cash') ?></option>
                                        <option value="check"><?= $localization->get('check') ?></option>
                                        <option value="other"><?= $localization->get('other') ?></option>
                                    </select>
                                </div>
                            </div>

                            <!-- Category and Status -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('category') ?> <?= $localization->get('and') ?> <?= $localization->get('status') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="category" class="form-label">
                                        <?= $localization->get('category') ?>
                                    </label>
                                    <select class="form-select" id="category" name="category">
                                        <option value=""><?= $localization->get('select_category') ?></option>
                                        <option value="salary"><?= $localization->get('salary') ?></option>
                                        <option value="freelance"><?= $localization->get('freelance') ?></option>
                                        <option value="consulting"><?= $localization->get('consulting') ?></option>
                                        <option value="rent"><?= $localization->get('rent') ?></option>
                                        <option value="utilities"><?= $localization->get('utilities') ?></option>
                                        <option value="groceries"><?= $localization->get('groceries') ?></option>
                                        <option value="transportation"><?= $localization->get('transportation') ?></option>
                                        <option value="entertainment"><?= $localization->get('entertainment') ?></option>
                                        <option value="health"><?= $localization->get('health') ?></option>
                                        <option value="education"><?= $localization->get('education') ?></option>
                                        <option value="other"><?= $localization->get('other') ?></option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_status" class="form-label">
                                        <?= $localization->get('payment_status') ?>
                                    </label>
                                    <select class="form-select" id="payment_status" name="payment_status">
                                        <option value="pending"><?= $localization->get('pending') ?></option>
                                        <option value="completed"><?= $localization->get('completed') ?></option>
                                        <option value="cancelled"><?= $localization->get('cancelled') ?></option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="reference" class="form-label">
                                        <?= $localization->get('reference') ?>
                                    </label>
                                    <input type="text" class="form-control" id="reference" name="reference" 
                                           placeholder="<?= $localization->get('transaction_reference') ?>">
                                    <div class="form-text"><?= $localization->get('reference_help') ?></div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">
                                        <?= $localization->get('notes') ?>
                                    </label>
                                    <textarea class="form-control" id="notes" name="notes" 
                                              rows="4" placeholder="<?= $localization->get('additional_notes') ?>"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Preview -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <strong><?= $localization->get('amount_preview') ?>:</strong>
                                            <span id="amountPreview" class="fs-5">0.00 EUR</span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <small class="text-muted">
                                                <span id="transactionType"><?= $localization->get('income') ?></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><?= $localization->get('quick_actions') ?></h6>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="setAmount(100)">
                                                100 €
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="setAmount(500)">
                                                500 €
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="setAmount(1000)">
                                                1000 €
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="setAmount(-50)">
                                                -50 €
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="setAmount(-100)">
                                                -100 €
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="setAmount(-500)">
                                                -500 €
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/money/overview" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <?= $localization->get('cancel') ?>
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?> <?= $localization->get('money') ?>
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
// Quick action functions
function setAmount(amount) {
    document.getElementById('amount').value = amount;
    updateAmountPreview();
}

// Form validation and functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('moneyForm');
    const amountInput = document.getElementById('amount');
    const currencySelect = document.getElementById('currency');
    const descriptionInput = document.getElementById('description');
    const amountPreviewSpan = document.getElementById('amountPreview');
    const transactionTypeSpan = document.getElementById('transactionType');

    // Real-time amount preview
    function updateAmountPreview() {
        const amount = parseFloat(amountInput.value) || 0;
        const currency = currencySelect.value;
        const sign = amount >= 0 ? '+' : '';
        amountPreviewSpan.textContent = sign + amount.toFixed(2) + ' ' + currency;
        
        // Update alert color and transaction type
        const alert = amountPreviewSpan.closest('.alert');
        alert.className = 'alert alert-' + (amount >= 0 ? 'success' : 'danger');
        transactionTypeSpan.textContent = amount >= 0 ? '<?= $localization->get('income') ?>' : '<?= $localization->get('expense') ?>';
    }

    amountInput.addEventListener('input', updateAmountPreview);
    currencySelect.addEventListener('change', updateAmountPreview);

    // Real-time validation
    amountInput.addEventListener('input', function() {
        if (!this.value || parseFloat(this.value) === 0) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
        updateAmountPreview();
    });

    descriptionInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        if (!amountInput.value || parseFloat(amountInput.value) === 0) {
            amountInput.classList.add('is-invalid');
            isValid = false;
        }

        if (descriptionInput.value.trim() === '') {
            descriptionInput.classList.add('is-invalid');
            isValid = false;
        }

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
        
        localStorage.setItem('money_draft_new', JSON.stringify(draftData));
        
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
    const savedDraft = localStorage.getItem('money_draft_new');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.value === '') {
                input.value = draftData[key];
            }
        });
        updateAmountPreview();
    }

    // Clear draft after successful save
    form.addEventListener('submit', function() {
        localStorage.removeItem('money_draft_new');
    });

    // Initialize amount preview
    updateAmountPreview();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('moneyForm').submit();
    }
    
    // Ctrl/Cmd + Z to cancel
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
        e.preventDefault();
        window.location.href = '/money/overview';
    }
    
    // Number keys for quick amounts
    if (e.key >= '1' && e.key <= '9' && !e.ctrlKey && !e.metaKey) {
        const amount = parseInt(e.key) * 100;
        document.getElementById('amount').value = amount;
        updateAmountPreview();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 