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
                <?= $localization->get('add') ?> <?= $localization->get('company') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('add') ?> <?= $localization->get('new') ?> <?= $localization->get('company') ?> <?= $localization->get('to') ?> <?= $localization->get('system') ?></p>
        </div>
        <div>
            <a href="/company/overview" class="btn btn-outline-secondary">
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
                        <?= $localization->get('new') ?> <?= $localization->get('company') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/company/add" id="companyForm">
                        <div class="row">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('company') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">
                                        <?= $localization->get('company_name') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           required placeholder="<?= $localization->get('enter_company_name') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_contact" class="form-label">
                                        <?= $localization->get('company_contact') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_contact" name="company_contact" 
                                           placeholder="<?= $localization->get('contact_person') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_email" class="form-label">
                                        <?= $localization->get('company_email') ?>
                                    </label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" 
                                           placeholder="<?= $localization->get('company_email_placeholder') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">
                                        <?= $localization->get('company_phone') ?>
                                    </label>
                                    <input type="tel" class="form-control" id="company_phone" name="company_phone" 
                                           placeholder="<?= $localization->get('phone_number') ?>">
                                </div>
                            </div>

                            <!-- Address and Legal Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('address') ?> <?= $localization->get('and') ?> <?= $localization->get('legal') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">
                                        <?= $localization->get('company_address') ?>
                                    </label>
                                    <textarea class="form-control" id="company_address" name="company_address" 
                                              rows="3" placeholder="<?= $localization->get('full_address') ?>"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="company_vat" class="form-label">
                                        <?= $localization->get('company_vat') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_vat" name="company_vat" 
                                           placeholder="<?= $localization->get('vat_number') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_registration" class="form-label">
                                        <?= $localization->get('company_registration') ?>
                                    </label>
                                    <input type="text" class="form-control" id="company_registration" name="company_registration" 
                                           placeholder="<?= $localization->get('registration_number') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_bank" class="form-label">
                                        <?= $localization->get('company_bank') ?>
                                    </label>
                                    <textarea class="form-control" id="company_bank" name="company_bank" 
                                              rows="2" placeholder="<?= $localization->get('bank_details') ?>"></textarea>
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
                                        <option value="active" selected><?= $localization->get('active') ?></option>
                                        <option value="inactive"><?= $localization->get('inactive') ?></option>
                                    </select>
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
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillSampleData()">
                                                <?= $localization->get('fill_sample_data') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearForm()">
                                                <?= $localization->get('clear_form') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyFromClipboard()">
                                                <?= $localization->get('paste_from_clipboard') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/company/overview" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <?= $localization->get('cancel') ?>
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?> <?= $localization->get('company') ?>
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
function fillSampleData() {
    document.getElementById('company_name').value = 'Sample Company GmbH';
    document.getElementById('company_contact').value = 'Max Mustermann';
    document.getElementById('company_email').value = 'contact@samplecompany.de';
    document.getElementById('company_phone').value = '+49 123 456789';
    document.getElementById('company_address').value = 'Musterstraße 123\n12345 Musterstadt\nDeutschland';
    document.getElementById('company_vat').value = 'DE123456789';
    document.getElementById('company_registration').value = 'HRB 12345';
    document.getElementById('company_bank').value = 'Deutsche Bank\nIBAN: DE89 3704 0044 0532 0130 00\nBIC: COBADEFFXXX';
}

function clearForm() {
    if (confirm('<?= $localization->get('confirm_clear_form') ?>')) {
        document.getElementById('companyForm').reset();
    }
}

function copyFromClipboard() {
    navigator.clipboard.readText().then(text => {
        // Try to parse the clipboard content and fill relevant fields
        const lines = text.split('\n');
        if (lines.length > 0) {
            document.getElementById('company_name').value = lines[0];
        }
        if (lines.length > 1) {
            document.getElementById('company_contact').value = lines[1];
        }
        if (lines.length > 2) {
            document.getElementById('company_email').value = lines[2];
        }
    }).catch(err => {
        console.error('Failed to read clipboard: ', err);
        alert('<?= $localization->get('clipboard_access_denied') ?>');
    });
}

// Form validation and functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('companyForm');
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
        
        localStorage.setItem('company_draft_new', JSON.stringify(draftData));
        
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
    const savedDraft = localStorage.getItem('company_draft_new');
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
        localStorage.removeItem('company_draft_new');
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('companyForm').submit();
    }
    
    // Ctrl/Cmd + Z to cancel
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
        e.preventDefault();
        window.location.href = '/company/overview';
    }
    
    // Ctrl/Cmd + V to paste from clipboard
    if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
        e.preventDefault();
        copyFromClipboard();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 