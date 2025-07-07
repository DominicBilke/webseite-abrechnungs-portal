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
                <?= $localization->get('edit') ?> <?= $localization->get('money') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('update') ?> <?= $localization->get('money') ?> <?= $localization->get('entry') ?> <?= $localization->get('information') ?></p>
        </div>
        <div>
            <a href="/money/view/<?= $money['id'] ?>" class="btn btn-outline-secondary">
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
                        <?= htmlspecialchars($money['description']) ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/money/update/<?= $money['id'] ?>" id="moneyForm">
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
                                               value="<?= $money['amount'] ?>" step="0.01" required>
                                        <select class="form-select" id="currency" name="currency" style="max-width: 100px;">
                                            <option value="EUR" <?= $money['currency'] === 'EUR' ? 'selected' : '' ?>>EUR</option>
                                            <option value="USD" <?= $money['currency'] === 'USD' ? 'selected' : '' ?>>USD</option>
                                            <option value="GBP" <?= $money['currency'] === 'GBP' ? 'selected' : '' ?>>GBP</option>
                                            <option value="CHF" <?= $money['currency'] === 'CHF' ? 'selected' : '' ?>>CHF</option>
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
                                              rows="3" required><?= htmlspecialchars($money['description']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">
                                        <?= $localization->get('payment_date') ?>
                                    </label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="<?= $money['payment_date'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">
                                        <?= $localization->get('payment_method') ?>
                                    </label>
                                    <select class="form-select" id="payment_method" name="payment_method">
                                        <option value=""><?= $localization->get('select_payment_method') ?></option>
                                        <option value="bank_transfer" <?= $money['payment_method'] === 'bank_transfer' ? 'selected' : '' ?>>
                                            <?= $localization->get('bank_transfer') ?>
                                        </option>
                                        <option value="credit_card" <?= $money['payment_method'] === 'credit_card' ? 'selected' : '' ?>>
                                            <?= $localization->get('credit_card') ?>
                                        </option>
                                        <option value="paypal" <?= $money['payment_method'] === 'paypal' ? 'selected' : '' ?>>
                                            <?= $localization->get('paypal') ?>
                                        </option>
                                        <option value="cash" <?= $money['payment_method'] === 'cash' ? 'selected' : '' ?>>
                                            <?= $localization->get('cash') ?>
                                        </option>
                                        <option value="check" <?= $money['payment_method'] === 'check' ? 'selected' : '' ?>>
                                            <?= $localization->get('check') ?>
                                        </option>
                                        <option value="other" <?= $money['payment_method'] === 'other' ? 'selected' : '' ?>>
                                            <?= $localization->get('other') ?>
                                        </option>
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
                                        <option value="salary" <?= $money['category'] === 'salary' ? 'selected' : '' ?>>
                                            <?= $localization->get('salary') ?>
                                        </option>
                                        <option value="freelance" <?= $money['category'] === 'freelance' ? 'selected' : '' ?>>
                                            <?= $localization->get('freelance') ?>
                                        </option>
                                        <option value="consulting" <?= $money['category'] === 'consulting' ? 'selected' : '' ?>>
                                            <?= $localization->get('consulting') ?>
                                        </option>
                                        <option value="rent" <?= $money['category'] === 'rent' ? 'selected' : '' ?>>
                                            <?= $localization->get('rent') ?>
                                        </option>
                                        <option value="utilities" <?= $money['category'] === 'utilities' ? 'selected' : '' ?>>
                                            <?= $localization->get('utilities') ?>
                                        </option>
                                        <option value="groceries" <?= $money['category'] === 'groceries' ? 'selected' : '' ?>>
                                            <?= $localization->get('groceries') ?>
                                        </option>
                                        <option value="transportation" <?= $money['category'] === 'transportation' ? 'selected' : '' ?>>
                                            <?= $localization->get('transportation') ?>
                                        </option>
                                        <option value="entertainment" <?= $money['category'] === 'entertainment' ? 'selected' : '' ?>>
                                            <?= $localization->get('entertainment') ?>
                                        </option>
                                        <option value="health" <?= $money['category'] === 'health' ? 'selected' : '' ?>>
                                            <?= $localization->get('health') ?>
                                        </option>
                                        <option value="education" <?= $money['category'] === 'education' ? 'selected' : '' ?>>
                                            <?= $localization->get('education') ?>
                                        </option>
                                        <option value="other" <?= $money['category'] === 'other' ? 'selected' : '' ?>>
                                            <?= $localization->get('other') ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_status" class="form-label">
                                        <?= $localization->get('payment_status') ?>
                                    </label>
                                    <select class="form-select" id="payment_status" name="payment_status">
                                        <option value="pending" <?= $money['payment_status'] === 'pending' ? 'selected' : '' ?>>
                                            <?= $localization->get('pending') ?>
                                        </option>
                                        <option value="completed" <?= $money['payment_status'] === 'completed' ? 'selected' : '' ?>>
                                            <?= $localization->get('completed') ?>
                                        </option>
                                        <option value="cancelled" <?= $money['payment_status'] === 'cancelled' ? 'selected' : '' ?>>
                                            <?= $localization->get('cancelled') ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="reference" class="form-label">
                                        <?= $localization->get('reference') ?>
                                    </label>
                                    <input type="text" class="form-control" id="reference" name="reference" 
                                           value="<?= htmlspecialchars($money['reference']) ?>">
                                    <div class="form-text"><?= $localization->get('reference_help') ?></div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">
                                        <?= $localization->get('notes') ?>
                                    </label>
                                    <textarea class="form-control" id="notes" name="notes" 
                                              rows="4"><?= htmlspecialchars($money['notes']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Preview -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-<?= $money['amount'] >= 0 ? 'success' : 'danger' ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <strong><?= $localization->get('amount_preview') ?>:</strong>
                                            <span id="amountPreview" class="fs-5">
                                                <?= ($money['amount'] >= 0 ? '+' : '') . number_format($money['amount'], 2) ?> <?= $money['currency'] ?>
                                            </span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <small class="text-muted">
                                                <?= $money['amount'] >= 0 ? $localization->get('income') : $localization->get('expense') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/money/view/<?= $money['id'] ?>" class="btn btn-outline-secondary">
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
// Form validation and functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('moneyForm');
    const amountInput = document.getElementById('amount');
    const currencySelect = document.getElementById('currency');
    const descriptionInput = document.getElementById('description');
    const amountPreviewSpan = document.getElementById('amountPreview');

    // Real-time amount preview
    function updateAmountPreview() {
        const amount = parseFloat(amountInput.value) || 0;
        const currency = currencySelect.value;
        const sign = amount >= 0 ? '+' : '';
        amountPreviewSpan.textContent = sign + amount.toFixed(2) + ' ' + currency;
        
        // Update alert color
        const alert = amountPreviewSpan.closest('.alert');
        alert.className = 'alert alert-' + (amount >= 0 ? 'success' : 'danger');
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
        
        localStorage.setItem('money_draft_<?= $money['id'] ?>', JSON.stringify(draftData));
        
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
    const savedDraft = localStorage.getItem('money_draft_<?= $money['id'] ?>');
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
        localStorage.removeItem('money_draft_<?= $money['id'] ?>');
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
        window.location.href = '/money/view/<?= $money['id'] ?>';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 