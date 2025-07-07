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
                <?= $localization->get('edit') ?> <?= $localization->get('company') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('update') ?> <?= $localization->get('company') ?> <?= $localization->get('information') ?></p>
        </div>
        <div>
            <a href="/company/view/<?= $company['id'] ?>" class="btn btn-outline-secondary">
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
                        <i class="bi bi-building me-2"></i>
                        <?= htmlspecialchars($company['company_name']) ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/company/update/<?= $company['id'] ?>">
                        <div class="row">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('company') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">
                                        <?= $localization->get('company_name') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?= htmlspecialchars($company['company_name']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="company_contact" class="form-label">
                                        <?= $localization->get('company_contact') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_contact" name="company_contact" 
                                           value="<?= htmlspecialchars($company['company_contact']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_email" class="form-label">
                                        <?= $localization->get('company_email') ?>
                                    </label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" 
                                           value="<?= htmlspecialchars($company['company_email']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">
                                        <?= $localization->get('company_phone') ?>
                                    </label>
                                    <input type="tel" class="form-control" id="company_phone" name="company_phone" 
                                           value="<?= htmlspecialchars($company['company_phone']) ?>">
                                </div>
                            </div>

                            <!-- Address and Legal Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('address') ?> <?= $localization->get('and') ?> <?= $localization->get('legal') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">
                                        <?= $localization->get('company_address') ?>
                                    </label>
                                    <textarea class="form-control" id="company_address" name="company_address" rows="3"><?= htmlspecialchars($company['company_address']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="company_vat" class="form-label">
                                        <?= $localization->get('company_vat') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_vat" name="company_vat" 
                                           value="<?= htmlspecialchars($company['company_vat']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_registration" class="form-label">
                                        <?= $localization->get('company_registration') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_registration" name="company_registration" 
                                           value="<?= htmlspecialchars($company['company_registration']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_bank" class="form-label">
                                        <?= $localization->get('company_bank') ?>
                                    </label>
                                    <textarea class="form-control" id="company_bank" name="company_bank" rows="2"><?= htmlspecialchars($company['company_bank']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <?= $localization->get('status') ?>
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= $company['status'] === 'active' ? 'selected' : '' ?>>
                                            <?= $localization->get('active') ?>
                                        </option>
                                        <option value="inactive" <?= $company['status'] === 'inactive' ? 'selected' : '' ?>>
                                            <?= $localization->get('inactive') ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/company/view/<?= $company['id'] ?>" class="btn btn-outline-secondary">
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
    const form = document.querySelector('form');
    const companyNameInput = document.getElementById('company_name');
    const companyEmailInput = document.getElementById('company_email');
    const companyPhoneInput = document.getElementById('company_phone');

    // Real-time validation
    companyNameInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    companyEmailInput.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    companyPhoneInput.addEventListener('input', function() {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
        if (this.value && !phoneRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (companyNameInput.value.trim() === '') {
            e.preventDefault();
            companyNameInput.classList.add('is-invalid');
            companyNameInput.focus();
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
        
        localStorage.setItem('company_draft_<?= $company['id'] ?>', JSON.stringify(draftData));
        
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
    const savedDraft = localStorage.getItem('company_draft_<?= $company['id'] ?>');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.value === '') {
                input.value = draftData[key];
            }
        });
    }

    // Clear draft after successful save
    form.addEventListener('submit', function() {
        localStorage.removeItem('company_draft_<?= $company['id'] ?>');
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form').submit();
    }
    
    // Ctrl/Cmd + Z to cancel
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
        e.preventDefault();
        window.location.href = '/company/view/<?= $company['id'] ?>';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 