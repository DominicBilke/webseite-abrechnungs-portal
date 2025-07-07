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
                <?= $localization->get('add') ?> <?= $localization->get('company') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('form_enter') ?> <?= $localization->get('company') ?> <?= $localization->get('form_required') ?></p>
        </div>
        <div>
            <a href="/company/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <!-- Company Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?= $localization->get('company') ?> <?= $localization->get('form_required') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/company/add" data-validate="true">
                        <div class="row">
                            <!-- Company Name -->
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">
                                    <?= $localization->get('company_name') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_name') ?>" 
                                       required>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('company_name')]) ?>
                                </div>
                            </div>

                            <!-- Company Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label">
                                    <?= $localization->get('company_phone') ?>
                                </label>
                                <input type="tel" class="form-control" id="company_phone" name="company_phone" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_phone') ?>">
                            </div>

                            <!-- Company Email -->
                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">
                                    <?= $localization->get('company_email') ?>
                                </label>
                                <input type="email" class="form-control" id="company_email" name="company_email" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_email') ?>">
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_email', ['field' => $localization->get('company_email')]) ?>
                                </div>
                            </div>

                            <!-- Company Contact -->
                            <div class="col-md-6 mb-3">
                                <label for="company_contact" class="form-label">
                                    <?= $localization->get('company_contact') ?>
                                </label>
                                <input type="text" class="form-control" id="company_contact" name="company_contact" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_contact') ?>">
                            </div>

                            <!-- Company VAT -->
                            <div class="col-md-6 mb-3">
                                <label for="company_vat" class="form-label">
                                    <?= $localization->get('company_vat') ?>
                                </label>
                                <input type="text" class="form-control" id="company_vat" name="company_vat" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_vat') ?>">
                            </div>

                            <!-- Company Registration -->
                            <div class="col-md-6 mb-3">
                                <label for="company_registration" class="form-label">
                                    <?= $localization->get('company_registration') ?>
                                </label>
                                <input type="text" class="form-control" id="company_registration" name="company_registration" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_registration') ?>">
                            </div>

                            <!-- Company Address -->
                            <div class="col-12 mb-3">
                                <label for="company_address" class="form-label">
                                    <?= $localization->get('company_address') ?>
                                </label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="3" 
                                          placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_address') ?>"></textarea>
                            </div>

                            <!-- Company Bank -->
                            <div class="col-12 mb-3">
                                <label for="company_bank" class="form-label">
                                    <?= $localization->get('company_bank') ?>
                                </label>
                                <textarea class="form-control" id="company_bank" name="company_bank" rows="3" 
                                          placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('company_bank') ?>"></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="/company/overview" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                <?= $localization->get('cancel') ?>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                <?= $localization->get('save') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-validate="true"]');
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    
    // Real-time validation
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Show error message
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= $localization->get('error_validation') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.insertBefore(alert, form.firstChild);
        }
    });
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        // Clear previous validation state
        field.classList.remove('is-valid', 'is-invalid');
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            isValid = false;
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        // If valid and has value, show valid state
        if (isValid && value) {
            field.classList.add('is-valid');
        }
        
        return isValid;
    }
});

// Auto-save functionality
let autoSaveTimeout;
const form = document.querySelector('form[data-validate="true"]');
const inputs = form.querySelectorAll('input, textarea');

inputs.forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic would go here
            console.log('Auto-saving...');
        }, 2000);
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 